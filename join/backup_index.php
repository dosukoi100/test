<?php
    session_start(); //セッションのスタート
    require ('functionlist.php');//DBの呼び出し

    if (isset($_GET['action']) and $_GET['action'] === 'rewrite' and isset($_SESSION['form'])) {
        //↑上が肝
        $form = $_SESSION['form'];
    } else {
        $form = [  //配列$formは連想配列にnameを使うと宣言
            'name' => '',
            'email' => '',
            'password' => ''
        ];  //空のフォームの配列
    }

    //$form = [  //配列$formは連想配列にnameを使うと宣言
        //'name' => '',
        //'email' => '',
        //'password' => ''
    //];  //空のフォームの配列
    $error = [];  //空のエラーの配列
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //もし送信ボタンが押されたら
        $form['name'] = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        //name属性を受け取り、配列formに格納する。
        if ($form['name'] === '') {  //もし、配列form['name']が空なら
            $error['name'] = 'blank' ;  //error['name']はbrankとする。
        //ここでechoとすると、Web上の右上で表示してしまう
        }
    
        $form['email'] = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
        if ($form['email'] === '') {
            $error['email'] = 'blank';
        } else {
            $db = dbconnect();//一応$db = dbconnect();とする
            $stmt = $db -> prepare('select count(*) from members where email = ?;');
            //?のemailの値が何個あるか
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('s',$form['email']);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            }
            $stmt -> bind_result($cnt);//count(*)の値(個数)を変数$cntに格納
            $stmt -> fetch();
            //var_dump($cnt); $cntが渡ってきてるか重複が何個あるかの確認
            if ($cnt >= 1) {//もし、$cntが1以上(重複)していれば
                $error['email'] = 'stack'; 
            }
        }
    
        $form['password'] = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
        if ($form['password'] === '') {
            $error['password'] = 'blank';
        }
    

        //画像のチェック

    
        $image = $_FILES['image'];//グローバル変数$_FILES使用
        if ($image['name'] !== '' and $image['error'] === 0) {
            $type = mime_content_type($image['tmp_name']);
            //$_FILESの'neme'に名前が有り、かつ、エラーで無ければ
            //var_dump($type);ファイルタイプ確認用
            if ($type !== 'image/jpeg' and $type !== 'image/png') {
                //$typeが'image/jpeg'以外かつ'image/png'なら
                $error['image'] = 'type';
                //配列$error['image']は'type'する
            }
        }
    
        //エラーが無っかたら

        if (empty($error)) {
            $_SESSION['form'] = $form;//配列$formの値をcheck.phpに送る
            // 画像のアップロード
            if ($image['name'] !== '') {
                $filename = date('YmdHis').'_'.$image['name'];
                //var_dump($filename);確認用
                if (!move_uploaded_file($image['tmp_name'],"../join/member_picture/".$filename)){
                //教材のパスは見ずらいのでjoin配下に新たにmember_pictureディレクトリを作成
                //join/pictureはcmd又はVScodeで作成
                //move_upoladed_file()が上手くいかなっかたらdieで落とす
                die ('ファイルのアプロードに失敗しました');
                }
                $_SESSION['form']['image'] = $filename;//セッションにformのimageを$filenameとする
            } else {
                $_SESSION['form']['image'] = '';//セッションにformのimageを空文字とする
            }
            header('location: check.php');
            exit();
        }
    }
    
    //require ('./functionlist.php');

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員登録</title>

    <link rel="stylesheet" href="../style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>会員登録</h1>
    </div>

    <div id="content">
        <p>次のフォームに必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>ニックネーム<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="name" size="35" maxlength="255" 
                    value="<?php echo h($form['name']);?>"/>
                    <?php if (isset($error['name']) and $error['name'] === 'blank'):?>
                        <p class="error">* ニックネームを入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>メールアドレス<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="email" size="35" maxlength="255" 
                    value="<?php echo h($form['email']);?>"/>
                    <?php if (isset($error['email']) and $error['email'] === 'blank'):?>
                        <p class="error">* メールアドレスを入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['email']) and $error['email'] === 'stack'):?>
                        <p class="error">* 指定されたメールアドレスはすでに登録されています</p>
                    <?php endif;?>
                <dt>パスワード<span class="required">必須</span></dt>
                <dd>
                    <input type="password" name="password" size="10" maxlength="20" 
                    value="<?php echo h($form['password']);?>"/>
                    <?php if (isset($error['password']) and $error['password'] === 'blank'):?>
                        <p class="error">* パスワードを入力してください</p>
                    <?php endif;?>
                    <?php if (mb_strlen($form['password']) <= 3): ?>
                        <p class="error">* パスワードは4文字以上で入力してください</p>
                    <?php endif; ?>
                </dd>
                <dt>写真など</dt>
                <dd>
                    <input type="file" name="image" size="35" value=""/>
                    <?php if (isset($error['image']) and $error['image'] === 'type'):?>
                        <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                    <?php endif; ?>
                    <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
                </dd>
            </dl>
            <div><input type="submit" value="入力内容を確認する"/></div>
        </form>
    </div>
</body>

</html>