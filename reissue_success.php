<?php
    session_start();
    session_regenerate_id();
    if (isset($_SESSION['name']) ) {//rereissue.phpから'name'をセッションで受け取る
		$name = $_SESSION['name'];
        require('./join/functionlist.php');
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }
    unset($_SESSION['name']);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>パスワード再発行成功後の画面</title>

	<link rel="stylesheet" href="./style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>パスワードの再発行が成功しました。</h1>
</div>

<div id="content">
<p>こんにちは<?php echo h($name);?>さん。パスワードの再発行が成功しました。</p>
<p><a href="./login.php">ログインする</a></p>
</div>

</div>
</body>
</html>
