<?php
    session_start();
	//$post = $_POST['token'];
	//$session = $_SESSION['token'];
	//var_dump($post,$session);
    session_destroy();
   
?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>不正なリクエストの画面</title>

	<link rel="stylesheet" href="./style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<font size = "7" color="ff0000">不正なリクエストです!!!</font>
</div>

<div id="content">
<p>不正なリクエストです!!!</p>
<p>管理者に直ちに連絡してください!!!</p>
</div>

</div>
</body>
</html>
