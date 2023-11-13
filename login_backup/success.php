<?php
    session_start();
    session_regenerate_id();
    if (isset($_SESSION['name']) and isset($_SESSION['id']) and isset($_SESSION['member_id'])) {//login.phpから'name'をセッションで受け取る
		$name = $_SESSION['name'];
        $id = $_SESSION['id'];
		$member_id = $_SESSION['member_id'];
        require('./join/functionlist.php');
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ログイン成功後の画面</title>

	<link rel="stylesheet" href="./style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>ログインが成功しました。</h1>
</div>

<div id="content">
<p>こんにちは<?php echo h($name);?>さん。項目を選んで下さい</p>
<p><a href="./nichibetu.php">日別表の入力をする</a></p>
<p><a href="./genkin.php">現金出納帳の入力をする</a></p>
<p><a href="./logout.php">ログアウトする</a></p>
<p><a href="./modify_passwd.php">パスワードを変更する</a></p>
</div>

</div>
</body>
</html>
