<?php 
    session_start();
    session_regenerate_id();
    require ('./join/functionlist.php');
    //パスワードを再発行するためのファイル。login.phpからの流用
    //URLは複雑にすることを推奨

    //xtest/success.phpよりセッションを取得するところを丸パクリ↓

    if (isset($_SESSION['id']) and isset($_SESSION['name']) and isset($_SESSION['member_id'])) {//login.phpから'name'をセッションで受け取る
		$id = $_SESSION['id'];
        $name = $_SESSION['name'];
		$member_id = $_SESSION['member_id'];
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }
    
    //var_dump($id,$name,$member_id);

    $password = '';//$member_idを$nameにする
    $passwd_veri ='';//$passwordを$member_id_postにする
    $error = [];//$errorの初期化
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { //ログインボタンが押されたら
        $password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_SPECIAL_CHARS);
        $passwd_veri = filter_input(INPUT_POST,'passwd_veri',FILTER_SANITIZE_SPECIAL_CHARS);
        var_dump($password,$passwd_veri);
        if ($password ==='' ) {
            $error['blank'] = 'blank';
            //var_dump($password,$passwd_veri);
        } elseif ($passwd_veri ==='') {
            $error['blanks'] = 'blanks';
        } elseif (mb_strlen($password) <= 3) {
            $error['passlen'] = 'short';
            //var_dump($password,$passwd_veri);
            //echo mb_strlen($password);
        } elseif (mb_strlen($passwd_veri) <= 3) {
            $error['passveri'] = 'short';
            //var_dump($password,$passwd_veri);
            //echo mb_strlen($passwd_veri);
        } elseif ($password !== $passwd_veri) {
            $error['notmatch'] = 'notmatch';
        } else {//$password === $passwd_veri
            //var_dump($password,$passwd_veri);
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $db = dbconnect();
            $stmt = $db -> prepare('update members set password = ? where member_id = ?;');
            //DBに不正アクセスされても1つのレコードしか取れない
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('si',$hash,$member_id);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            }
            $_SESSION['name'] = $name;
            header('location: ./reissue_success.php');
            //var_dump($password);//$hashの中身の確認用
        } 
    }
    

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>パスワード再発行(2)</title>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>パスワード再発行(2)</h1>
    </div>
    <div id="content">
        <div id="lead">
            <p><?php echo $name ;?>さん、パスワードを記入してください。</p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>パスワード(半角英数字4文字以上)</dt>
                <dd>
                    <input type="password" name="password" size="35" maxlength="255" value=""/>
                    <?php if (isset($error['blank']) and $error['blank'] === 'blank') :?>
                        <p class="error">* パスワードを入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['passlen']) and $error['passlen'] === 'short') :?>
                        <p class="error">* 4文字以上で入力してください</p>
                    <?php endif; ?>
                </dd>
                <dt>パスワード(確認用:半角英数字4文字以上)</dt>
                <dd>
                    <input type="password" name="passwd_veri" size="35" maxlength="255" value=""/>
                    <?php if (isset($error['blanks']) and $error['blanks'] === 'blanks') :?>
                        <p class="error">* パスワードを入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['notmatch']) and $error['notmatch'] === 'notmatch'): ?>
                        <p class="error">* 上下でパスワードが違います。正しく入れて下さい</p>
                    <?php endif ;?>
                    <?php if (isset($error['passveri']) and $error['passveri'] === 'short'): ?>
                        <p class="error">* 上下でパスワードが違います。正しく入れて下さい</p>
                    <?php endif ;?>
                </dd>
            </dl>
            <div>
                <input type="submit" value="確定する"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
