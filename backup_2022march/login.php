<?php 
    require ('./join/functionlist.php');
    session_start();


    $member_id = '';//$emailの初期化
    $password ='';//$passwordの初期化
    $error = [];//$errorの初期化
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { //ログインボタンが押されたら
        $member_id = filter_input(INPUT_POST,'member_id',FILTER_SANITIZE_NUMBER_INT);
        $password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
        if ($member_id === '' or $password === '') {//どちらかが空なら
            $error['login'] = 'blank';//$error['login']を'blank'とする
        } else {
            $db = dbconnect();
            $stmt = $db -> prepare('select id,name,password from members where member_id = ? limit 1;');
            //DBに不正アクセスされても1つのレコードしか取れない
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('i',$member_id);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            } 
            $stmt -> bind_result($id,$name,$hash);//DBのid,name,passwordを取得して各変数に格納
            $stmt -> fetch();

            //var_dump($hash);//$hashの中身の確認用

            if (password_verify($password,$hash)) {//フォームのパスワードとDBのパスワードが同じなら
                session_regenerate_id();  //セッションIDを再生成
                $_SESSION['id'] = $id;  //DBのidを$idとしてセッションで値を渡す
                $_SESSION['name'] = $name; //DBのnameを$nameとしてセッションで値を渡す
                header('location: ./nichibetu.php');
            } else {
                $error['login'] = 'failed';
            }
        }
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <title>ログインする</title>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>ログインする</h1>
    </div>
    <div id="content">
        <div id="lead">
            <p>組合員番号(下4桁)とパスワードを記入してログインしてください。</p>
            <p>入会手続きがまだの方はこちらからどうぞ。</p>
            <p>&raquo;<a href="join/">入会手続きをする</a></p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>組合員番号</dt>
                <dd>
                    <input type="text" name="member_id" size="35" maxlength="255" value="<?php echo h($member_id);?>"/>
                    <?php if (isset($error['login']) and $error['login'] === 'blank') :?>
                        <?php //$error['login']を初期化して、blankなら↑ ?>
                        <p class="error">* 組合員番号(下4桁)とパスワードをご記入ください</p>
                    <?php endif; ?>
                    <?php if (isset($error['login']) and $error['login'] === 'failed'): ?>
                        <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
                    <?php endif ;?>
                </dd>
                <dt>パスワード</dt>
                <dd>
                    <input type="password" name="password" size="35" maxlength="255" value=""/>
                </dd>
            </dl>
            <div>
                <input type="submit" value="ログインする"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
