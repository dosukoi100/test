<?php 
    session_start();
    require ('./join/functionlist.php');
    //パスワードを再発行するためのファイル。login.phpからの流用
    //URLは複雑にすることを推奨


    $name = '';//$member_idを$nameにする
    $member_id_post ='';//$passwordを$member_id_postにする
    $error = [];//$errorの初期化
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { //ログインボタンが押されたら
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        $member_id_post = filter_input(INPUT_POST,'member_id_post',FILTER_SANITIZE_NUMBER_INT);
        if ($name === '' or $member_id_post === '') {//どちらかが空なら
            $error['login'] = 'blank';//$error['login']を'blank'とする
        } else {
            $db = dbconnect();
            $stmt = $db -> prepare('select id,name,member_id from members where name = ? and member_id = ? limit 1;');
            //DBに不正アクセスされても1つのレコードしか取れない
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('si',$name,$member_id_post);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            } 
            $stmt -> bind_result($id,$name,$member_id);//DBのid,name,passwordを取得して各変数に格納
            $stmt -> fetch();

            //var_dump($hash);//$hashの中身の確認用

            if ((int)$member_id_post === $member_id) {//filter_inputのFILTER_SANITIZE_NUMBER_INTでも値は'str型'である!!!
                session_regenerate_id();  //セッションIDを再生成
                $_SESSION['id'] = $id;  //DBのidを$idとしてセッションで値を渡す
                $_SESSION['name'] = $name;
                $_SESSION['member_id'] = $member_id; //DBのnameを$nameとしてセッションで値を渡す
                header('location: ./rereissue.php');
            } else {
                $error['login'] = 'failed';
            }
            //var_dump($member_id);//中身の確認用
            //var_dump($member_id_post);//中身の確認用
        }
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>パスワード再発行(1)</title>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>パスワード再発行(1)</h1>
    </div>
    <div id="content">
        <div id="lead">
            <p>御名前と組合員番号(下4桁)を記入してください。</p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>御名前</dt>
                <dd>
                    <input type="text" name="name" size="35" maxlength="255" value="<?php echo h($name);?>"/>
                    <?php if (isset($error['login']) and $error['login'] === 'blank') :?>
                        <?php //$error['login']を初期化して、blankなら↑ ?>
                        <p class="error">* 御名前をご記入ください</p>
                    <?php endif; ?>
                </dd>
                <dt>組合員番号</dt>
                <dd>
                    <input type="text" name="member_id_post" size="35" maxlength="255" value=""/>
                        <?php if (isset($error['login']) and $error['login'] === 'blank') :?>
                            <?php //$error['login']を初期化して、blankなら↑ ?>
                            <p class="error">* 組合員番号(下4桁)をご記入ください</p>
                        <?php endif; ?>
                        <?php if (isset($error['login']) and $error['login'] === 'failed'): ?>
                            <p class="error">* 組合員番号が正しくありません。正しくご記入ください。</p>
                        <?php endif ;?>
                </dd>
            </dl>
            <div>
                <input type="submit" value="パスワード再発行画面"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
