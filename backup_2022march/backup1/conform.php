<?php 
	session_start();
	if (isset($_SESSION['form'])) {//もし、初期値セッションのformがあれば
		$form = $_SESSION['form'];
	} else {
		header('location: login.php');// イコールindex.php
		exit();
	}
	//var_dump($_SESSION['form']);
	require ('./join/functionlist.php');
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//dbconnect();何故か呼び出せないのでDBを直接呼び出す
		//$db = new mysqli('localhost:8889','root','root','mini_bbs');
		$db = dbconnect();
		if (!$db) {
			die($db -> error);
		}
		$stmt = $db -> prepare('insert into x_nichibetu (member_id,date,len,jishalen,jishakaisuu,jiin,nenryou,uriage) values(?,?,?,?,?,?,?,?);');
		if (!$stmt) {
			die($db -> error);
		}
		
		$stmt -> bind_param('iiiiiiii',$form['member_id'],$form['date'],$form['len'],$form['jishalen'],$form['jishakaisuu'],$form['jiin'],$form['nenryou'],$form['uriage']);

		$result = $stmt -> execute();
		if (!$result) {
			die($db -> error);
		}

		unset($_SESSION['form']);//セッションを閉じる
		header('location: thanks.php');
	}
	if (isset($_SESSION['name']) and isset($_SESSION['id'])) {//login.phpから'name'をセッションで受け取る
		$name = $_SESSION['name'];
        $id = $_SESSION['id'];
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
	<title>日別表確認画面</title>

	<link rel="stylesheet" href="./style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>日別表確認</h1>
		</div>

		<div id="content">
			<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
			<form action="" method="post">
				<dl>
					<dt>組合番号</dt>
					<dd><?php echo h($form['member_id']); ?></dd>
					<dt>西暦月</dt>
					<dd><?php echo h($form['date']); ?></dd>
					<dt>走行距離</dt>
					<dd><?php echo h($form['len']); ?></dd>
					<dt>実車距離</dt>
					<dd><?php echo h($form['jishalen']); ?></dd>
					<dt>乗車回数</dt>
					<dd><?php echo h($form['jishakaisuu']); ?></dd>
					<dt>乗車人数</dt>
					<dd><?php echo h($form['jiin']); ?></dd>
					<dt>消費燃料量</dt>
					<dd><?php echo h($form['nenryou']); ?></dd>
					<dt>総売上額</dt>
					<dd><?php echo h($form['uriage']); ?></dd>
				</dl>
				<div><a href="nichibetu.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
			</form>
		</div>

	</div>
</body>

</html>