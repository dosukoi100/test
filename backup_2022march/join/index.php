<?php
//確認用のパスワードフォーム実装版(オリジナル)
//本番環境ならこちら
    session_start(); //セッションのスタート
    require ('functionlist.php');//DBの呼び出し

    if (isset($_GET['action']) and $_GET['action'] === 'rewrite' and isset($_SESSION['form'])) {
        //↑上が肝
        $form = $_SESSION['form'];
    } else {
        $form = [  //配列$formは連想配列にnameを使うと宣言
            'name' => '',
            'member_id' => '',
            'password' => '',
            'newpasswd' => ''
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
    
        $form['member_id'] = filter_input(INPUT_POST,'member_id',FILTER_SANITIZE_NUMBER_INT);
        if ($form['member_id'] === '') {
            $error['member_id'] = 'blank';
        } else {
            $db = dbconnect();//一応$db = dbconnect();とする
            $stmt = $db -> prepare('select count(*) from members where member_id = ?;');
            //?のemailの値が何個あるか
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('i',$form['member_id']);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            }
            $stmt -> bind_result($cnt);//count(*)の値(個数)を変数$cntに格納
            $stmt -> fetch();
            //var_dump($cnt); $cntが渡ってきてるか重複が何個あるかの確認
            if ($cnt >= 1) {//もし、$cntが1以上(重複)していれば
                $error['member_id'] = 'stack'; 
            }
        }
    
        $form['password'] = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
        if ($form['password'] === '') {
            $error['password'] = 'blank';
        }
    
        $form['newpasswd'] = filter_input(INPUT_POST,'newpasswd',FILTER_SANITIZE_STRING);
        if ($form['password'] !== $form['newpasswd']) {
            $error['newpasswd'] = 'notverify';
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
                <dt>お名前<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="name" size="35" maxlength="255" 
                    value="<?php echo h($form['name']);?>"/>
                    <?php if (isset($error['name']) and $error['name'] === 'blank'):?>
                        <p class="error">* お名前を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>組合番号(下4桁:半角数字)<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="member_id" size="10" maxlength="20" 
                    value="<?php echo h($form['member_id']);?>"/>
                    <?php if (isset($error['member_id']) and $error['member_id'] === 'blank'):?>
                        <p class="error">* 組合番号を入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['member_id']) and $error['member_id'] === 'stack'):?>
                        <p class="error">* 指定された組合番号はすでに登録されています</p>
                    <?php endif;?>
                <dt>パスワード(4桁以上:半角英数字)<span class="required">必須</span></dt>
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
                <dt>確認用パスワード(4桁以上:半角英数字)<span class="required">必須</span></dt>
                <dd>
                    <input type="password" name="newpasswd" size="10" maxlength="20" 
                    value="<?php echo h($form['newpasswd']);?>"/>
                    <?php if ($form['password'] !== $form['newpasswd']):?>
                        <p class="error">* パスワードが一致しません</p>
                    <?php endif;?>
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