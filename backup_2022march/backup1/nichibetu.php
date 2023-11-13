<?php
//確認用のパスワードフォーム実装版(オリジナル)
//本番環境ならこちら

//

    session_start(); //セッションのスタート
    session_regenerate_id();
    require ('./join/functionlist.php');//DBの呼び出し

    if (isset($_GET['action']) and $_GET['action'] === 'rewrite' and isset($_SESSION['form'])) {
        //↑上が肝
        $form = $_SESSION['form'];
    } else {
        $form = [  //配列$formは連想配列にnameを使うと宣言
            'member_id' => '',  //name->member_id
            'date' => '',  //member_id -> date
            'len' => '',   //password -> len
            'jishalen' => '',   //newpasswd -> jishalen
            'jishakaisuu' => '',
            'jiin' => '',
            'nenryou' => '',
            'uriage' => ''
        ];  //空のフォームの配列
    }

    if (isset($_SESSION['name']) and isset($_SESSION['id'])) {//login.phpから'name'をセッションで受け取る
		$name = $_SESSION['name'];
        $id = $_SESSION['id'];
	} else {
		header('location: ./login.php');// イコールindex.php
		exit();
    }

    //$form = [  //配列$formは連想配列にnameを使うと宣言
        //'name' => '',
        //'email' => '',
        //'password' => ''
    //];  //空のフォームの配列
    $error = [];  //空のエラーの配列
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //もし送信ボタンが押されたら
        $form['member_id'] = filter_input(INPUT_POST,'member_id',FILTER_SANITIZE_NUMBER_INT);
        //name属性を受け取り、配列formに格納する。
        if ($form['member_id'] === '') {  //もし、配列form['name']が空なら
            $error['member_id'] = 'blank' ;  //error['name']はbrankとする。
        //ここでechoとすると、Web上の右上で表示してしまう
        }
        
        $form['date'] = filter_input(INPUT_POST,'date',FILTER_SANITIZE_NUMBER_INT);
        if ($form['date'] === '') {
            $error['date'] = 'blank';
        }

        //ここまでは合っているはず
    
        $form['len'] = filter_input(INPUT_POST,'len',FILTER_SANITIZE_NUMBER_INT);
        if ($form['len'] === '') {
            $error['len'] = 'blank';
        }
    
        $form['jishalen'] = filter_input(INPUT_POST,'jishalen',FILTER_SANITIZE_NUMBER_INT);
        if ($form['jishalen'] === '') {
            $error['jishalen'] = 'blank';
        }

        $form['jishakaisuu'] = filter_input(INPUT_POST,'jishakaisuu',FILTER_SANITIZE_NUMBER_INT);
        if ($form['jishakaisuu'] === '') {
            $error['jishakaisuu'] = 'blank';
        }
    
        $form['jiin'] = filter_input(INPUT_POST,'jiin',FILTER_SANITIZE_NUMBER_INT);
        if ($form['jiin'] === '') {
            $error['jiin'] = 'blank';
        }

        $form['nenryou'] = filter_input(INPUT_POST,'nenryou',FILTER_SANITIZE_NUMBER_INT);
        if ($form['nenryou'] === '') {
            $error['nenryou'] = 'blank';
        }

        $form['uriage'] = filter_input(INPUT_POST,'uriage',FILTER_SANITIZE_NUMBER_INT);
        if ($form['uriage'] === '') {
            $error['uriage'] = 'blank';
        }
    
        //エラーが無っかたら

        if (empty($error)) {
            $_SESSION['form'] = $form;//配列$formの値をcheck.phpに送る
            header('location: ./conform.php');
            exit();
        }
    }
    
    //require ('./functionlist.php');

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>日別表の入力</title>

    <link rel="stylesheet" href="./style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>日別表の入力</h1>
    </div>

    <div id="content">
        <p>次の項目に必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>組合番号(下4桁:半角数字)<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="member_id" size="35" maxlength="255" 
                    value="<?php echo h($form['member_id']);?>"/>
                    <?php if (isset($error['member_id']) and $error['member_id'] === 'blank'):?>
                        <p class="error">* 組合番号を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>西暦月(6桁:半角数字)<span class="required">必須</span></dt>
                <dt><p>(例)2022年の4月は202204とします</p></dt>
                <dd>
                    <input type="text" name="date" size="10" maxlength="20" 
                    value="<?php echo h($form['date']);?>"/>
                    <?php if (isset($error['date']) and $error['date'] === 'blank'):?>
                        <p class="error">* 西暦月を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>走行距離<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="len" size="10" maxlength="20" 
                    value="<?php echo h($form['len']);?>"/>
                    <?php if (isset($error['len']) and $error['len'] === 'blank'):?>
                        <p class="error">* 走行距離を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>実車距離<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="jishalen" size="10" maxlength="20" 
                    value="<?php echo h($form['jishalen']);?>"/>
                    <?php if (isset($error['jishalen']) and $error['jishalen'] === 'blank'):?>
                        <p class="error">* 実車距離を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>乗車回数<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="jishakaisuu" size="10" maxlength="20" 
                    value="<?php echo h($form['jishakaisuu']);?>"/>
                    <?php if (isset($error['jishakaisuu']) and $error['jishakaisuu'] === 'blank'):?>
                        <p class="error">* 乗車回数を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>乗車人数<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="jiin" size="10" maxlength="20" 
                    value="<?php echo h($form['jiin']);?>"/>
                    <?php if (isset($error['jiin']) and $error['jiin'] === 'blank'):?>
                        <p class="error">* 乗車人数を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>消費燃料量<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="nenryou" size="10" maxlength="20" 
                    value="<?php echo h($form['nenryou']);?>"/>
                    <?php if (isset($error['nenryou']) and $error['nenryou'] === 'blank'):?>
                        <p class="error">* 消費燃料量を入力してください</p>
                    <?php endif;?>
                </dd>
                <dt>総売上額<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="uriage" size="10" maxlength="20" 
                    value="<?php echo h($form['uriage']);?>"/>
                    <?php if (isset($error['uriage']) and $error['uriage'] === 'blank'):?>
                        <p class="error">* 総売上額を入力してください</p>
                    <?php endif;?>
                </dd>
            </dl>
            <div><input type="submit" value="入力内容を確認する"/></div>
        </form>
    </div>
</body>

</html>