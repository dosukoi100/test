<?php 
    //renichi.phpより流用。パスワード変更画面modify_passwd.phpを参照
    //現在xtest/success.phpには日別表変更のリンクは張っていません!
    //もし、パスワード再発行システムと同じ手順を踏むなら、xtest/reissue.phpから流用(password)も使用
    session_start();
    session_regenerate_id();
    require ('./join/functionlist.php');
    //xtest/modify_passwd.phpより流用

    if (isset($_SESSION['id']) and isset($_SESSION['name']) and isset($_SESSION['member_id'])) {//login.phpから'name'をセッションで受け取る
		$id = $_SESSION['id'];
        $name = $_SESSION['name'];
		$member_id = $_SESSION['member_id'];
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }
    
    //var_dump($id,$name,$member_id);

    $redate = '';//入力する$redateの値
    $remember_id ='';//入力する$remember_idの値
    $error = [];//$errorの初期化
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { //ログインボタンが押されたら
        $redate = filter_input(INPUT_POST,'redate',FILTER_SANITIZE_NUMBER_INT);
        $remember_id = filter_input(INPUT_POST,'remember_id',FILTER_SANITIZE_NUMBER_INT);
        //var_dump($redate,$remember_id);
        if ($redate ==='' ) {
            $error['blank'] = 'blank';
        } elseif ($remember_id ==='') {
            $error['blanks'] = 'blanks';
        } elseif (mb_strlen($redate) !== 6) {
            $error['match'] = 'notmatch';
        } elseif (mb_strlen($remember_id) !== 4) {//ココ注意->OKここではstr型として読まれる
            $error['match'] = 'nomatch';
        } elseif ($member_id !== (int)$remember_id) {//ここでint型にする
            $error['notmatch'] = 'notmatch';
        } else {//$member_id === (int)$remember_id
            $form = [//最初各項目が多いが項目数が多い時はリストでやった方が楽
                'cid' => '','member_id' => '','date' => '','suidou'=>'','shoumou_b'=>'',
                'shoumou_t'=>'','shoumou_p'=>'','jimu'=>'','gas'=>'','oil'=>'','lease'=>'',
                'repair'=>'','office'=>'','comm'=>'','tel'=>'','sys'=>'','book'=>'',
                'carins'=>'','groupfee'=>'','cartax'=>'','toll'=>'','exchange'=>'',
                'taxac'=>'','clean'=>'','basepay'=>'','repay'=>'','benefit'=>'','life'=>'',
                'life'=>'','stack'=>'','totalout'=>'','inget'=>'','chip'=>'','draw'=>'',
                'draw'=>'','other'=>'','totalin'=>''
            ];
            $db = dbconnect();
            $stmt = $db -> prepare('select id,member_id,date,suidou,shoumou_b,
            shoumou_t,shoumou_p,jimu,gas,oil,lease,repair,carcell,office,comm,
            tel,sys,book,carins,groupfee,cartax,toll,exchange,taxac,clean,basepay,
            repay,benefit,life,stack,totalout,inget,chip,draw,other,totalin 
            from cash where member_id = ? and date = ?;');
            //recash.phpのオリジナルは上から↑
            if (!$stmt) {
                die($db -> error);
            }
            $redate = (int)$redate;
            $remember_id = (int)$remember_id;
            $stmt -> bind_param('ii',$remember_id,$redate);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            }
            $stmt -> bind_result($form['cid'],$form['member_id'],$form['date'],
            $form['suidou'],$form['shoumou_b'],$form['shoumou_t'],
            $form['shoumou_p'],$form['jimu'],$form['gas'],$form['oil'],$form['lease'],
            $form['repair'],$form['carcell'],$form['office'],$form['comm'],$form['tel'],
            $form['sys'],$form['book'],$form['carins'],$form['groupfee'],$form['cartax'],
            $form['toll'],$form['exchange'],$form['taxac'],$form['clean'],
            $form['basepay'],$form['repay'],$form['benefit'],$form['life'],$form['stack'],
            $form['totalout'],$form['inget'],$form['chip'],$form['draw'],$form['other'],
            $form['totalin']);
            $success = $stmt -> fetch();
            if (!$success) {
                //die($db -> error);
                //dieでなく$errorに値を格納する
                $error['donotexist'] = 'donotexist';
                //もし、データベースに値が無かった場合の処理を作る。(上で対応済)
                //他のページに行かせて、注意・警告を出すなど
            }
            //var_dump($cid,$member_id,$date,$suidou,$shoumou_b,$shoumou_t,
            //$shoumou_p,$jimu,$gas,$oil,$lease,$repair,$carcell,$office,$comm,$tel,
            //$sys,$book,$carins,$groupfee,$cartax,$toll,$exchange,$taxac,$clean,
            //$basepay,$repay,$benefit,$life,$stack,$totalout,$inget,$chip,$draw,$other,
            //$totalin);//ここは次のファイル(edit_cash.phpでのvar_dump())
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['member_id'] = $member_id;
            $_SESSION['form'] = $form; 

            if (empty($error)) {
                header('location: ./edit_recash.php');
            }
            
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
    <title>現金出納帳変更画面</title>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>現金出納帳の変更</h1>
    </div>
    <div id="content">
        <div id="lead">
            <p><?php echo $name ;?>さん、変更したい年月と組合員番号を入れて下さい</p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>年月(半角数字6文字以上)</dt>
                <dd>
                    <input type="text" name="redate" size="35" maxlength="255" value="<?php echo $redate;?>"/>
                    <?php if (isset($error['blank']) and $error['blank'] === 'blank') :?>
                        <p class="error">* 年月を入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['match']) and $error['match'] === 'notmatch') :?>
                        <p class="error">* 半角数字6桁で入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['donotexist']) and $error['donotexist'] === 'donotexist') :?>
                        <p class="error">* 存在しない年月です。もう一度確認してください</p>
                    <?php endif; ?>
                </dd>
                <dt>組合員番号(半角数字4桁)</dt>
                <dd>
                    <input type="text" name="remember_id" size="35" maxlength="255" value="<?php echo $remember_id; ?>"/>
                    <?php if (isset($error['blanks']) and $error['blanks'] === 'blanks') :?>
                        <p class="error">* 組合員番号を入力してください</p>
                    <?php endif; ?>
                    <?php if (isset($error['match']) and $error['match'] === 'nomatch'): ?>
                        <p class="error">* 半角数字4桁で入力してください</p>
                    <?php endif ;?>
                    <?php if (isset($error['notmatch']) and $error['notmatch'] === 'notmatch'): ?>
                        <p class="error">* 不正な組合員番号です!!正しい組合員番号を入力してください!!</p>
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
