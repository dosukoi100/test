<?php
    session_start();
    session_regenerate_id();
    require('./join/functionlist.php');

    if (isset($_SESSION['id']) and isset($_SESSION['name']) and 
        isset($_SESSION['member_id']) and isset($_SESSION['form']) ) {//login.phpから'name'をセッションで受け取る
		$id = $_SESSION['id'];
        $name = $_SESSION['name'];
		$remember_id = $_SESSION['member_id'];//セッションのmember_idをremember_idとする
        $form = $_SESSION['form'];
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }

    //var_dump($form);

    $error =[];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //もし送信ボタンが押されたら
        $form['member_id'] = filter_input(INPUT_POST,'member_id',FILTER_SANITIZE_NUMBER_INT);
        //name属性を受け取り、配列formに格納する。
        if ($form['member_id'] === '') {  //もし、配列form['name']が空なら
            $error['member_id'] = 'blank' ;  //error['name']はbrankとする。
        //ここでechoとすると、Web上の右上で表示してしまう
        }
        
        //$remember_id = (int)$remember_id;//$remember_idフォームの数字化
    
        if ($remember_id !== (int)$form['member_id']) {
            $error['match'] = 'failed';
        }

        //echo '||'.(int)$form['member_id'];
        //var_dump($form['member_id']);

        if (mb_strlen($form['member_id']) !== 4 ) {
            $error['lenmatch'] = 'notmatch';
        }

        $form['date'] = filter_input(INPUT_POST,'date',FILTER_SANITIZE_NUMBER_INT);
        if ($form['date'] === '') {
            $error['date'] = 'blank';
        }//上二つは必須の項目の際に使う
        if (mb_strlen($form['date']) !== 6) {
            $error['datelen'] = 'notmatch';
        }
        
        //ここからは必須の項目でない場合の処理
        $form['suidou'] = filter_input(INPUT_POST,'suidou',FILTER_SANITIZE_NUMBER_INT);

        $form['shoumou_b'] = filter_input(INPUT_POST,'shoumou_b',FILTER_SANITIZE_NUMBER_INT);
    
        $form['shoumou_t'] = filter_input(INPUT_POST,'shoumou_t',FILTER_SANITIZE_NUMBER_INT);

        $form['shoumou_p'] = filter_input(INPUT_POST,'shoumou_p',FILTER_SANITIZE_NUMBER_INT);
    
        $form['jimu'] = filter_input(INPUT_POST,'jimu',FILTER_SANITIZE_NUMBER_INT);

        $form['gas'] = filter_input(INPUT_POST,'gas',FILTER_SANITIZE_NUMBER_INT);

        $form['oil'] = filter_input(INPUT_POST,'oil',FILTER_SANITIZE_NUMBER_INT);

        $form['lease'] = filter_input(INPUT_POST,'lease',FILTER_SANITIZE_NUMBER_INT);

        $form['repair'] = filter_input(INPUT_POST,'repair',FILTER_SANITIZE_NUMBER_INT);

        $form['carcell'] = filter_input(INPUT_POST,'carcell',FILTER_SANITIZE_NUMBER_INT);

        $form['office'] = filter_input(INPUT_POST,'office',FILTER_SANITIZE_NUMBER_INT);

        $form['comm'] = filter_input(INPUT_POST,'comm',FILTER_SANITIZE_NUMBER_INT);

        $form['tel'] = filter_input(INPUT_POST,'tel',FILTER_SANITIZE_NUMBER_INT);

        $form['sys'] = filter_input(INPUT_POST,'sys',FILTER_SANITIZE_NUMBER_INT);

        $form['book'] = filter_input(INPUT_POST,'book',FILTER_SANITIZE_NUMBER_INT);

        $form['carins'] = filter_input(INPUT_POST,'carins',FILTER_SANITIZE_NUMBER_INT);

        $form['groupfee'] = filter_input(INPUT_POST,'groupfee',FILTER_SANITIZE_NUMBER_INT);

        $form['cartax'] = filter_input(INPUT_POST,'cartax',FILTER_SANITIZE_NUMBER_INT);

        $form['toll'] = filter_input(INPUT_POST,'toll',FILTER_SANITIZE_NUMBER_INT);

        $form['exchange'] = filter_input(INPUT_POST,'exchange',FILTER_SANITIZE_NUMBER_INT);

        $form['taxac'] = filter_input(INPUT_POST,'taxac',FILTER_SANITIZE_NUMBER_INT);

        $form['clean'] = filter_input(INPUT_POST,'clean',FILTER_SANITIZE_NUMBER_INT);

        $form['basepay'] = filter_input(INPUT_POST,'basepay',FILTER_SANITIZE_NUMBER_INT);

        $form['repay'] = filter_input(INPUT_POST,'repay',FILTER_SANITIZE_NUMBER_INT);

        $form['benefit'] = filter_input(INPUT_POST,'benefit',FILTER_SANITIZE_NUMBER_INT);

        $form['life'] = filter_input(INPUT_POST,'life',FILTER_SANITIZE_NUMBER_INT);

        $form['stack'] = filter_input(INPUT_POST,'stack',FILTER_SANITIZE_NUMBER_INT);

        $form['totalout'] = filter_input(INPUT_POST,'totalout',FILTER_SANITIZE_NUMBER_INT);

        $form['inget'] = filter_input(INPUT_POST,'inget',FILTER_SANITIZE_NUMBER_INT);

        $form['chip'] = filter_input(INPUT_POST,'chip',FILTER_SANITIZE_NUMBER_INT);

        $form['draw'] = filter_input(INPUT_POST,'draw',FILTER_SANITIZE_NUMBER_INT);

        $form['other'] = filter_input(INPUT_POST,'other',FILTER_SANITIZE_NUMBER_INT);

        $form['totalin'] = filter_input(INPUT_POST,'totalin',FILTER_SANITIZE_NUMBER_INT);

        //エラーが無っかたら

        if (empty($error)) {
            $db = dbconnect();
            $stmt = $db -> prepare('update cash set date=?,suidou=?,shoumou_b=?,
            shoumou_t=?,shoumou_p=?,jimu=?,gas=?,oil=?,lease=?,repair=?,carcell=?,
            office=?,comm=?,tel=?,sys=?,book=?,carins=?,groupfee=?,cartax=?,
            toll=?,exchange=?,taxac=?,clean=?,basepay=?,repay=?,benefit=?,
            life=?,stack=?,totalout=?,inget=?,chip=?,draw=?,other=?,totalin=? where 
            id=? and member_id=?;');
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii',$form['date'],
            $form['suidou'],$form['shoumou_b'],$form['shoumou_t'],
            $form['shoumou_p'],$form['jimu'],$form['gas'],$form['oil'],$form['lease'],
            $form['repair'],$form['carcell'],$form['office'],$form['comm'],$form['tel'],
            $form['sys'],$form['book'],$form['carins'],$form['groupfee'],$form['cartax'],
            $form['toll'],$form['exchange'],$form['taxac'],$form['clean'],$form['basepay'],
            $form['repay'],$form['benefit'],$form['life'],$form['stack'],$form['totalout'],
            $form['inget'],$form['chip'],$form['draw'],$form['other'],$form['totalin'],
            $form['cid'],$form['member_id']);

            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            }
            if (isset($_SESSION['member_id'])) {
                $member_id = $_SESSION['member_id'];//remember_idからmember_idに戻す
            }
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['member_id'] = $member_id;//edit_recash_success.phpにセッションを渡す
            header('location: ./edit_recash_success.php');
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>現金出納帳変更の入力</title>

    <link rel="stylesheet" href="./style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>現金出納帳変更の入力</h1>
    </div>

    <div id="content">
        <p>次の項目に必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>組合員番号(下4桁:半角数字)<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="member_id" size="35" maxlength="255" 
                    value=""/>
                    <?php if (isset($error['remember_id']) and $error['remember_id'] === 'blank'):?>
                        <p class="error">* 組合員番号を入力してください</p>
                    <?php endif;?>
                    <?php if (isset($error['match']) and $error['match'] === 'failed'):?>
                        <p class="error">* 組合員番号が違います!組合員番号を再度入れなおして下さい。</p>
                    <?php endif;?>
                    <?php if (isset($error['lenmatch']) and $error['lenmatch'] === 'notmatch'):?>
                        <p class="error">* 組合員番号(下4桁)で入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>西暦月(6桁:半角数字)<span class="required">必須</span></dt>
                <dt><p>(例)2022年4月は202204と入力してください</p></dt>
                <dd>
                    <input type="text" name="date" size="10" maxlength="20" 
                    value="<?php echo h($form['date']);?>"/>
                    <?php if (isset($error['date']) and $error['date'] === 'blank'):?>
                        <p class="error">* 西暦月を入力してください</p>
                    <?php endif;?>
                    <?php if (isset($error['datelen']) and $error['datelen'] === 'notmatch'):?>
                        <p class="error">* 西暦月(6桁:半角数字)で入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>水道光熱費(635)</dt>
                <dd>
                    <input type="text" name="suidou" size="10" maxlength="20" 
                    value="<?php echo h($form['suidou']);?>"/>
                </dd>
                <dt>消耗部品費(634:部品・消耗品)</dt>
                <dd>
                    <input type="text" name="shoumou_b" size="10" maxlength="20" 
                    value="<?php echo h($form['shoumou_b']);?>"/>
                </dd>
                <dt>消耗部品費(634:タイヤ・チューブ)</dt>
                <dd>
                    <input type="text" name="shoumou_t" size="10" maxlength="20" 
                    value="<?php echo h($form['shoumou_t']);?>"/>
                </dd>
                <dt>消耗部品費(634:エレメント・パーツ他)</dt>
                <dd>
                    <input type="text" name="shoumou_p" size="10" maxlength="20" 
                    value="<?php echo h($form['shoumou_p']);?>"/>
                </dd>
                <dt>事務用消耗品費(633)</dt>
                <dd>
                    <input type="text" name="jimu" size="10" maxlength="20" 
                    value="<?php echo h($form['jimu']);?>"/>
                </dd>
                <dt>燃料油脂費(645:LPG・ガソリン代)</dt>
                <dd>
                    <input type="text" name="gas" size="10" maxlength="20" 
                    value="<?php echo h($form['gas']);?>"/>
                </dd>
                <dt>燃料油脂費(645:オイル代)</dt>
                <dd>
                    <input type="text" name="oil" size="10" maxlength="20" 
                    value="<?php echo h($form['oil']);?>"/>
                </dd>
                <dt>リース料(646)</dt>
                <dd>
                    <input type="text" name="lease" size="10" maxlength="20" 
                    value="<?php echo h($form['lease']);?>"/>
                </dd>
                <dt>車輌修繕費(632)</dt>
                <dd>
                    <input type="text" name="repair" size="10" maxlength="20" 
                    value="<?php echo h($form['repair']);?>"/>
                </dd>
                <dt>賃借料(631:車庫代)</dt>
                <dd>
                    <input type="text" name="carcell" size="10" maxlength="20" 
                    value="<?php echo h($form['carcell']);?>"/>
                </dd>
                <dt>賃借料(631:営業所家賃)</dt>
                <dd>
                    <input type="text" name="office" size="10" maxlength="20" 
                    value="<?php echo h($form['office']);?>"/>
                </dd>
                <dt>交際費(639)</dt>
                <dd>
                    <input type="text" name="comm" size="10" maxlength="20" 
                    value="<?php echo h($form['comm']);?>"/>
                </dd>
                <dt>通信費(631:電話代・切手等)</dt>
                <dd>
                    <input type="text" name="tel" size="10" maxlength="20" 
                    value="<?php echo h($form['tel']);?>"/>
                </dd>
                <dt>通信費(631:システム基本料)</dt>
                <dd>
                    <input type="text" name="sys" size="10" maxlength="20" 
                    value="<?php echo h($form['sys']);?>"/>
                </dd>
                <dt>図書印刷費(644)</dt>
                <dd>
                    <input type="text" name="book" size="10" maxlength="20" 
                    value="<?php echo h($form['book']);?>"/>
                </dd>
                <dt>損害保険料(640:交通共済・自賠責)</dt>
                <dd>
                    <input type="text" name="carins" size="10" maxlength="20" 
                    value="<?php echo h($form['carins']);?>"/>
                </dd>
                <dt>租税公課(638:組合武課金)</dt>
                <dd>
                    <input type="text" name="groupfee" size="10" maxlength="20" 
                    value="<?php echo h($form['groupfee']);?>"/>
                </dd>
                <dt>租税公課(638:自動車税・重量税・事業税)</dt>
                <dd>
                    <input type="text" name="cartax" size="10" maxlength="20" 
                    value="<?php echo h($form['cartax']);?>"/>
                </dd>
                <dt>旅費交通費(636)</dt>
                <dd>
                    <input type="text" name="toll" size="10" maxlength="20" 
                    value="<?php echo h($form['toll']);?>"/>
                </dd>
                <dt>諸手数料(637:換金手数料・印鑑証明)</dt>
                <dd>
                    <input type="text" name="exchange" size="10" maxlength="20" 
                    value="<?php echo h($form['exchange']);?>"/>
                </dd>
                <dt>諸手数料(642:税理士顧問料)</dt>
                <dd>
                    <input type="text" name="taxac" size="10" maxlength="20" 
                    value="<?php echo h($form['taxac']);?>"/>
                </dd>
                <dt>雑費(655)</dt>
                <dd>
                    <input type="text" name="clean" size="10" maxlength="20" 
                    value="<?php echo h($form['clean']);?>"/>
                </dd>
                <dt>借入金(340)</dt>
                <dd>
                    <input type="text" name="basepay" size="10" maxlength="20" 
                    value="<?php echo h($form['basepay']);?>"/>
                </dd>
                <dt>支払利息(643)</dt>
                <dd>
                    <input type="text" name="repay" size="10" maxlength="20" 
                    value="<?php echo h($form['repay']);?>"/>
                </dd>
                <dt>福利厚生費(627)</dt>
                <dd>
                    <input type="text" name="benefit" size="10" maxlength="20" 
                    value="<?php echo h($form['benefit']);?>"/>
                </dd>
                <dt>事業主・貸(260:生活費)</dt>
                <dd>
                    <input type="text" name="life" size="10" maxlength="20" 
                    value="<?php echo h($form['life']);?>"/>
                </dd>
                <dt>事業主・貸(260:組合事業費積立金)</dt>
                <dd>
                    <input type="text" name="stack" size="10" maxlength="20" 
                    value="<?php echo h($form['stack']);?>"/>
                </dd>
                <dt>支払合計</dt>
                <dd>
                    <input type="text" name="totalout" size="10" maxlength="20" 
                    value="<?php echo h($form['totalout']);?>"/>
                </dd>
                <dt>売上(410)</dt>
                <dd>
                    <input type="text" name="inget" size="10" maxlength="20" 
                    value="<?php echo h($form['inget']);?>"/>
                </dd>
                <dt>チップ(410)</dt>
                <dd>
                    <input type="text" name="chip" size="10" maxlength="20" 
                    value="<?php echo h($form['chip']);?>"/>
                </dd>
                <dt>事業主・借(380)</dt>
                <dd>
                    <input type="text" name="draw" size="10" maxlength="20" 
                    value="<?php echo h($form['draw']);?>"/>
                </dd>
                <dt>雑収入(260)</dt>
                <dd>
                    <input type="text" name="other" size="10" maxlength="20" 
                    value="<?php echo h($form['other']);?>"/>
                </dd>
                <dt>入金合計</dt>
                <dd>
                    <input type="text" name="totalin" size="10" maxlength="20" 
                    value="<?php echo h($form['totalin']);?>"/>
                </dd>
                <dd>
                    <?php unset($form); ?>
                    <?php //var_dump($name,$id,$remember_id,$member_id); ?>
                </dd>
            </dl>
            <div><input type="submit" value="現金出納帳の訂正を確認する"/></div>
        </form>
    </div>
</body>

</html>

