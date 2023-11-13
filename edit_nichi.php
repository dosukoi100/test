<?php
//日別表入力画面(xtest/nichibetu.php)参照

//

    session_start(); //セッションのスタート
    session_regenerate_id();
    require ('./join/functionlist.php');//DBの呼び出し
    if (isset($_SESSION['id']) and isset($_SESSION['name']) and isset($_SESSION['member_id']) 
        and isset($_SESSION['nid']) and isset($_SESSION['date']) and isset($_SESSION['len']) 
        and isset($_SESSION['jishalen']) and isset($_SESSION['jishakaisuu']) and isset($_SESSION['jiin'])
        and isset($_SESSION['nenryou']) and isset($_SESSION['uriage'])) 
        {//login.phpから'name'をセッションで受け取る
		$id = $_SESSION['id'];
        $name = $_SESSION['name'];
		$member_id = $_SESSION['member_id'];
        $nid = $_SESSION['nid'];
        $date = $_SESSION['date'];
        $len = $_SESSION['len'];
        $jishalen = $_SESSION['jishalen'];
        $jishakaisuu = $_SESSION['jishakaisuu'];
        $jiin = $_SESSION['jiin'];
        $nenryou = $_SESSION['nenryou'];
        $uriage = $_SESSION['uriage'];
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }
    
    //var_dump($id,$member_id,$nid,$len,$uriage);

    $error = [];  //空のエラーの配列
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //もし送信ボタンが押されたら
        $remember_id = filter_input(INPUT_POST,'remember_id',FILTER_SANITIZE_NUMBER_INT);
        //name属性を受け取り、配列formに格納する。
        if ($remember_id === '') {  //もし、配列form['name']が空なら
            $error['remember_id'] = 'blank' ;  //error['name']はbrankとする。
        //ここでechoとすると、Web上の右上で表示してしまう
        }
        if (mb_strlen($remember_id) !== 4 ) {
            $error['lenmatch'] = 'notmatch';
        }

        $remember_id = (int)$remember_id;//$form_member_idのstr型をint型にする!

        if ($member_id !== $remember_id) {
            $error['match'] = 'failed';
            //membersテーブルのmember_idとこのファイルの入力が違う時の処理
        }
        
        $date = filter_input(INPUT_POST,'date',FILTER_SANITIZE_NUMBER_INT);
        if ($date === '') {
            $error['date'] = 'blank';
        }
        if (mb_strlen($date) !== 6) {
            $error['datelen'] = 'notmatch';
        }

        //ここまでは合っているはず
    
        $len = filter_input(INPUT_POST,'len',FILTER_SANITIZE_NUMBER_INT);
        if ($len === '') {
            $error['len'] = 'blank';
        }
    
        $jishalen = filter_input(INPUT_POST,'jishalen',FILTER_SANITIZE_NUMBER_INT);
        if ($jishalen === '') {
            $error['jishalen'] = 'blank';
        }

        $jishakaisuu = filter_input(INPUT_POST,'jishakaisuu',FILTER_SANITIZE_NUMBER_INT);
        if ($jishakaisuu === '') {
            $error['jishakaisuu'] = 'blank';
        }
    
        $jiin = filter_input(INPUT_POST,'jiin',FILTER_SANITIZE_NUMBER_INT);
        if ($jiin === '') {
            $error['jiin'] = 'blank';
        }

        $nenryou = filter_input(INPUT_POST,'nenryou',FILTER_SANITIZE_NUMBER_INT);
        if ($nenryou === '') {
            $error['nenryou'] = 'blank';
        }

        $uriage = filter_input(INPUT_POST,'uriage',FILTER_SANITIZE_NUMBER_INT);
        if ($uriage === '') {
            $error['uriage'] = 'blank';
        }
    
        //エラーが無っかたら

        if (empty($error)) {
            $db = dbconnect();
            $stmt = $db -> prepare('update x_nichibetu set date=?,len=?,
            jishalen=?,jishakaisuu=?,jiin=?,nenryou=?,uriage=? where id=? 
            and member_id=?');
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('iiiiiiiii',$date,$len,$jishalen,$jishakaisuu,
            $jiin,$nenryou,$uriage,$nid,$member_id);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            }
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['member_id'] = $member_id;
            header('location: ./edit_nichi_success.php');
            exit();
        }
    }
    
    //require ('./functionlist.php');
    //echo '||',$id,$name,$m_member_id,'||',$form['member_id']."\n";
    //var_dump($m_member_id); m_member_id
    //echo '||';
    //var_dump($form['member_id']);//fromのmember_idはstr型なのでint型に換える!
    //echo '$m_member_idは、',$m_member_id,'||';//$m_member_idはint型
    //echo '(string) $form_member_idは、',(string) $form_member_id;
    //$form_member_idをint型にして表示

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>日別表編集入力</title>

    <link rel="stylesheet" href="./style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>日別表の編集入力</h1>
    </div>

    <div id="content">
        <p>次の項目に必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>組合員番号(下4桁:半角数字)<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="remember_id" size="35" maxlength="255" 
                    value=""/>
                    <?php if (isset($error['remember_id']) and $error['remember_id'] === 'blank'):?>
                        <p class="error">* 組合番号を入力してください</p>
                    <?php endif;?>
                    <?php if (isset($error['match']) and $error['match'] === 'failed'):?>
                        <p class="error">* 組合番号が違います!組合番号を再度入れなおして下さい。</p>
                    <?php endif;?>
                    <?php if (isset($error['lenmatch']) and $error['lenmatch'] === 'notmatch'):?>
                        <p class="error">* 半角数字4桁で入力してください。</p>
                    <?php endif;?>
                </dd>
                <dt>西暦月(6桁:半角数字)<span class="required">必須</span></dt>
                <dt><p>(例)2022年の4月は202204とします</p></dt>
                <dd>
                    <input type="text" name="date" size="10" maxlength="20" 
                    value="<?php echo h($date);?>"/>
                    <?php if (isset($error['date']) and $error['date'] === 'blank'):?>
                        <p class="error">* 西暦月を入力してください</p>
                    <?php endif;?>
                    <?php if (isset($error['datelen']) and $error['datelen'] === 'notmatch'):?>
                        <p class="error">* 半角数字6桁で入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>走行距離<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="len" size="10" maxlength="20" 
                    value="<?php echo h($len);?>"/>
                    <?php if (isset($error['len']) and $error['len'] === 'blank'):?>
                        <p class="error">* 走行距離を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>実車距離<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="jishalen" size="10" maxlength="20" 
                    value="<?php echo h($jishalen);?>"/>
                    <?php if (isset($error['jishalen']) and $error['jishalen'] === 'blank'):?>
                        <p class="error">* 実車距離を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>乗車回数<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="jishakaisuu" size="10" maxlength="20" 
                    value="<?php echo h($jishakaisuu);?>"/>
                    <?php if (isset($error['jishakaisuu']) and $error['jishakaisuu'] === 'blank'):?>
                        <p class="error">* 乗車回数を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>乗車人数<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="jiin" size="10" maxlength="20" 
                    value="<?php echo h($jiin);?>"/>
                    <?php if (isset($error['jiin']) and $error['jiin'] === 'blank'):?>
                        <p class="error">* 乗車人数を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>消費燃料量<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="nenryou" size="10" maxlength="20" 
                    value="<?php echo h($nenryou);?>"/>
                    <?php if (isset($error['nenryou']) and $error['nenryou'] === 'blank'):?>
                        <p class="error">* 消費燃料量を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>総売上額<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="uriage" size="10" maxlength="20" 
                    value="<?php echo h($uriage);?>"/>
                    <?php if (isset($error['uriage']) and $error['uriage'] === 'blank'):?>
                        <p class="error">* 総売上額を入力してください</p>
                    <?php endif;?>
                </dd>
            </dl>
            <div><input type="submit" value="確定する"/></div>
        </form>
    </div>
</body>

</html>