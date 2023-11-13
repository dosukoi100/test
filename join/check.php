<?php 
	session_start();
	session_regenerate_id();
	if (isset($_SESSION['form'])) {//もし、初期値セッションのformがあれば
		$form = $_SESSION['form'];
	} else {
		header('location: .');// イコールindex.php
		exit();
	}
	//var_dump($_SESSION['form']);
	require ('functionlist.php');
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//dbconnect();何故か呼び出せないのでDBを直接呼び出す
		//$db = new mysqli('localhost:8889','root','root','mini_bbs');
		$db = dbconnect();
		if (!$db) {
			die($db -> error);
		}
		$stmt = $db -> prepare('insert into members (name,member_id,password,picture) values(?,?,?,?);');
		if (!$stmt) {
			die($db -> error);
		}
		$password = password_hash($form['password'],PASSWORD_DEFAULT);
		//パスワードのハッシュ化
		$stmt -> bind_param('siss',$form['name'],$form['member_id'],$password,$form['image']);
		$result = $stmt -> execute();
		if (!$result) {
			die($db -> error);
		}

		unset($_SESSION['form']);//セッションを閉じる
		header('location: thanks.php');
		

	}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>会員登録</h1>
		</div>

		<div id="content">
			<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
			<form action="" method="post">
				<dl>
					<dt>名前</dt>
					<dd><?php echo h($form['name']); ?></dd>
					<dt>組合員番号</dt>
					<dd><?php echo h($form['member_id']); ?></dd>
					<dt>パスワード</dt>
					<dd>
						【表示されません】
					</dd>
					<dt>写真など</dt>
					<dd>
							<img src="../join/member_picture/<?php echo h($form['image']);?>" width="100" alt="" />
					</dd>
				</dl>
				<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
			</form>
		</div>

	</div>
</body>

</html>