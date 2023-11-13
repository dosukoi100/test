<?php 
	session_start();
	session_regenerate_id();
	if (isset($_SESSION['form'])) {//もし、初期値セッションのformがあれば
		$form = $_SESSION['form'];
	} else {
		header('location: login.php');
		exit();
	}
	//var_dump($_SESSION['form']);
	require ('./join/functionlist.php');

	if (isset($_SESSION['name']) and isset($_SESSION['id'])) {//login.phpから'name'をセッションで受け取る
		$name = $_SESSION['name'];
        $id = $_SESSION['id'];
	} else {
		header('location: ./login.php');
		exit();
    }

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//dbconnect();何故か呼び出せないのでDBを直接呼び出す
		//$db = new mysqli('localhost:8889','root','root','mini_bbs');
		$db = dbconnect();
		if (!$db) {
			die($db -> error);
		}
		$stmt = $db -> prepare('insert into cash (member_id,date,suidou,shoumou_b,
        shoumou_t,shoumou_p,jimu,gas,oil,lease,repair,carcell,office,comm,tel,sys,book,
        carins,groupfee,cartax,toll,exchange,taxac,clean,basepay,repay,benefit,life,stack,
        totalout,inget,chip,draw,other,totalin) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
        ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
		if (!$stmt) {
			die($db -> error);
		}
		
		$stmt -> bind_param('iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii',$form['member_id'],
        $form['date'],$form['suidou'],$form['shoumou_b'],$form['shoumou_t'],$form['shoumou_p'],
        $form['jimu'],$form['gas'],$form['oil'],$form['lease'],$form['repair'],
        $form['carcell'],$form['office'],$form['comm'],$form['tel'],$form['sys'],
        $form['book'],$form['carins'],$form['groupfee'],$form['cartax'],$form['toll'],
        $form['exchange'],$form['taxac'],$form['clean'],$form['basepay'],$form['repay'],
        $form['benefit'],$form['life'],$form['stack'],$form['totalout'],$form['inget'],
        $form['chip'],$form['draw'],$form['other'],$form['totalin']);

		$result = $stmt -> execute();
		if (!$result) {
			die($db -> error);
		}

		unset($_SESSION['form']);//セッションを閉じる
		header('location: thankyou.php');
		

	}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>現金出納帳確認画面</title>

	<link rel="stylesheet" href="./style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>現金出納帳確認</h1>
		</div>

		<div id="content">
			<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
			<form action="" method="post">
				<dl>
					<dt>組合番号</dt>
					<dd><?php echo h($form['member_id']); ?></dd>
					<dt>西暦月</dt>
					<dd><?php echo h($form['date']); ?></dd>
					<dt>水道光熱費(635)</dt>
					<dd><?php echo h($form['suidou']); ?></dd>
					<dt>消耗部品費(634:部品・消耗品)</dt>
					<dd><?php echo h($form['shoumou_b']); ?></dd>
					<dt>消耗部品費(634:タイヤ・チューブ)</dt>
					<dd><?php echo h($form['shoumou_t']); ?></dd>
					<dt>消耗部品費(634:エレメント・パーツ)</dt>
					<dd><?php echo h($form['shoumou_p']); ?></dd>
					<dt>事務用消耗品費(633)</dt>
					<dd><?php echo h($form['jimu']); ?></dd>
					<dt>燃料油脂費(645:LPG・ガソリン代)</dt>
					<dd><?php echo h($form['gas']); ?></dd>
                    <dt>燃料油脂費(645:オイル代)</dt>
					<dd><?php echo h($form['oil']); ?></dd>
					<dt>リース料(646)</dt>
					<dd><?php echo h($form['lease']); ?></dd>
                    <dt>車輌修繕費(632)</dt>
					<dd><?php echo h($form['repair']); ?></dd>
					<dt>賃借料(631:車庫代)</dt>
					<dd><?php echo h($form['carcell']); ?></dd>
					<dt>賃借料(631:事業所家賃)</dt>
					<dd><?php echo h($form['office']); ?></dd>
					<dt>交際費(639)</dt>
					<dd><?php echo h($form['comm']); ?></dd>
					<dt>通信費(641:電話代・切手等)</dt>
					<dd><?php echo h($form['tel']); ?></dd>
					<dt>通信費(641:システム基本料)</dt>
					<dd><?php echo h($form['sys']); ?></dd>
					<dt>図書印刷費(644)</dt>
					<dd><?php echo h($form['book']); ?></dd>
					<dt>損害保険料(640:交通共済・自賠責)</dt>
					<dd><?php echo h($form['carins']); ?></dd>
                    <dt>租税公課(638:組合武課金)</dt>
					<dd><?php echo h($form['groupfee']); ?></dd>
					<dt>租税公課(638:自動車税・重量税・事業税)</dt>
					<dd><?php echo h($form['cartax']); ?></dd>
                    <dt>旅費交通費(636)</dt>
					<dd><?php echo h($form['toll']); ?></dd>
					<dt>諸手数料(637:換金手数料・印鑑証明)</dt>
					<dd><?php echo h($form['exchange']); ?></dd>
					<dt>諸手数料(642:税理士顧問料)</dt>
					<dd><?php echo h($form['taxac']); ?></dd>
					<dt>雑費(655)</dt>
					<dd><?php echo h($form['clean']); ?></dd>
					<dt>借入金(340)</dt>
					<dd><?php echo h($form['basepay']); ?></dd>
					<dt>支払利息(643)</dt>
					<dd><?php echo h($form['repay']); ?></dd>
					<dt>福利厚生費(627)</dt>
					<dd><?php echo h($form['benefit']); ?></dd>
					<dt>事業主・貸(260:生活費)</dt>
					<dd><?php echo h($form['life']); ?></dd>
                    <dt>事業主・貸(260:組合事業費積立金)</dt>
					<dd><?php echo h($form['stack']); ?></dd>
					<dt>支払合計</dt>
					<dd><?php echo h($form['totalout']); ?></dd>
                    <dt>売上(410)</dt>
					<dd><?php echo h($form['inget']); ?></dd>
					<dt>チップ(410)</dt>
					<dd><?php echo h($form['chip']); ?></dd>
					<dt>事業主・借(380)</dt>
					<dd><?php echo h($form['draw']); ?></dd>
                    <dt>雑収入(260)</dt>
					<dd><?php echo h($form['other']); ?></dd>
					<dt>入金合計</dt>
					<dd><?php echo h($form['totalin']); ?></dd>
				</dl>
				<div><a href="genkin.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
			</form>
		</div>

	</div>
</body>

</html>