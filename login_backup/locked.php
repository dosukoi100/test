<?php
    session_start();
    session_regenerate_id();
    if (isset($_SESSION['failed_count']) and isset($_SESSION['locked_time'])) {
		$failed_count = $_SESSION['failed_count'];
        $locked_time = $_SESSION['locked_time'];
        require('./join/functionlist.php');
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }

    unset($failed_count);
    unset($locked_time);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>アカウントロックの画面</title>

	<link rel="stylesheet" href="./style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<font size = "7" color="ff0000">現在アカウントロック中です!!!</font>
</div>

<div id="content">
<p>只今の時刻より１日間アカウントを停止します。</p>
<p>１日経ったら再度ログインしてください。</p>
<p>お急ぎの場合は管理者に連絡した下さい。(実費を請求する場合があります。)</p>
<p><a href="./login.php">ログインする</a></p>
</div>

</div>
</body>
</html>
