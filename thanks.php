<?php
	session_start();
	session_regenerate_id();
	if (isset($_SESSION['name']) and isset($_SESSION['id'])) {//login.phpから'name'をセッションで受け取る
		$name = $_SESSION['name'];
        $id = $_SESSION['id'];
		$m_member_id = $_SESSION['member_id'];
		require('./join/functionlist.php');
	} else {
		header('location: ./login.php');
		exit();
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>日別表の成功画面</title>

	<link rel="stylesheet" href="./style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>日別表の送信成功</h1>
</div>

<div id="content">
<p>お疲れ様です。<?php echo h($name);?>さん。日別表の登録が完了しました</p>
<p><a href="./genkin.php">現金出納帳の入力をする</a></p>
<p><a href="./logout.php">ログアウトする</a></p>
</div>

</div>
</body>
</html>
