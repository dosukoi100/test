<?php 
    session_start();
    require ('./join/functionlist.php');


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
            $stmt = $db -> prepare('select id,name,member_id,password,failed_count,locked_time from members where member_id = ? limit 1;');
            //DBに不正アクセスされても1つのレコードしか取れない
            if (!$stmt) {
                die($db -> error);
            }
            $stmt -> bind_param('i',$member_id);
            $result = $stmt -> execute();
            if (!$result) {
                die($db -> error);
            } 
            $stmt -> bind_result($id,$name,$member_id,$hash,$failed_count,$locked_time);//DBのid,name,passwordを取得して各変数に格納
            $stmt -> fetch();
            //var_dump($member_id);
            //var_dump($hash.'|'.$failed_count.'|'.$locked_time);//$hash$failed_count$locked_timeの中身の確認用
            if (password_verify($password,$hash) and $failed_count <= 2) {//フォームのパスワードとDBのパスワードが同じなら
                session_regenerate_id();  //セッションIDを再生成
                $_SESSION['id'] = $id;  //DBのidを$idとしてセッションで値を渡す
                $_SESSION['name'] = $name;
                $_SESSION['member_id'] = $member_id; //DBのnameを$nameとしてセッションで値を渡す
                $db = dbconnect();
                $recount = 0;
                $reset_time = null;
                $stmt = $db -> prepare('update members set failed_count=?,locked_time=? where member_id=?;');
                //DBに不正アクセスされても1つのレコードしか取れない
                if (!$stmt) {
                    die($db -> error);
                }
                $stmt -> bind_param('iii',$recount,$reset_time,$member_id);
                $result = $stmt -> execute();
                if (!$result) {
                    die($db -> error);
                }
                header('location: ./success.php');
            } elseif (!(password_verify($password,$hash)) and $failed_count <= 1) {
                $error['login'] = 'failed';
            } elseif ($failed_count >= 2) {
                //$error['lock'] = 'lock';//$error配列に'lock'を定義
                if (($locked_time != null) and ($locked_time - strtotime('now') <= 0)) {
                    $db = dbconnect();
                    $recount = 0;
                    $reset_time = null;
                    $stmt = $db -> prepare('update members set failed_count=?,locked_time=? where member_id=?;');
                    //DBに不正アクセスされても1つのレコードしか取れない
                    if (!$stmt) {
                        die($db -> error);
                    }
                    $stmt -> bind_param('iii',$recount,$reset_time,$member_id);
                    $result = $stmt -> execute();
                    if (!$result) {
                        die($db -> error);
                    }
                    if (password_verify($password,$hash)) {//フォームのパスワードとDBのパスワードが同じなら
                        session_regenerate_id();  //セッションIDを再生成
                        $_SESSION['id'] = $id;  //DBのidを$idとしてセッションで値を渡す
                        $_SESSION['name'] = $name;
                        $_SESSION['member_id'] = $member_id; //DBのnameを$nameとしてセッションで値を渡す
                        header('location: ./success.php');
                    } else {
                        $error['login'] = 'failed';
                    }
                } else { 
                    //echo 'failed_countの数が3以上になりました!'."<br>";
                    $locked_time = strtotime('now') + 60;//数字の部分がアカウント停止時間
                    //echo strtotime('now').'||'.$locked_time;
                    //var_dump($locked_time);int型
                    //membersテーブルのlocked_timeに$locked_timeを格納
                    $db = dbconnect(); //＊同じファイルでprepareを2回以上使うときはもう一度セット
                    $stmt = $db -> prepare("UPDATE members SET locked_time = ? WHERE member_id = ?;");
                    if (!$stmt) {
                        die($db -> error);
                    }
                    //print_r($db->errorInfo());
                    $stmt -> bind_param('ii',$locked_time,$member_id);
                    $stmt ->execute();
                    //var_dump($failed_count,$locked_time);
                    $_SESSION['failed_count'] = $failed_count;
                    $_SESSION['locked_time'] = $locked_time;
                    header('location: ./locked.php');
                    //2022.4.14改変
                }
            }

            //var_dump($member_id);

            if (isset($error['login']) and $error['login'] === 'failed') {
                //$failed_countの値に1を加える
                //$member_id = (int)$member_id;
                //failed_countの値を格納
                $db = dbconnect(); //＊同じファイルでprepareを2回以上使うときはもう一度セット
                $stmt = $db -> prepare("UPDATE members SET failed_count = failed_count + 1 WHERE member_id = ?;");
                if (!$stmt) {
                    die($db -> error);
                }
                //print_r($db->errorInfo());
                $stmt -> bind_param('i',$member_id);
                $stmt ->execute();

                //ログイン失敗時のfailed_countを取得する
                $db = dbconnect();
                $stmt = $db -> prepare('select id,name,member_id,password,failed_count,locked_time from members where member_id = ? limit 1;');
                //DBに不正アクセスされても1つのレコードしか取れない
                if (!$stmt) {
                die($db -> error);
                }
                $stmt -> bind_param('i',$member_id);
                $result = $stmt -> execute();
                if (!$result) {
                    die($db -> error);
                } 
                $stmt -> bind_result($id,$name,$member_id,$hash,$failed_count,$locked_time);//DBの各値を各変数に格納
                $stmt -> fetch();
                //echo $failed_count;//$failed_countの値を確認
            }

            //アカウントロックタイム内か外れているか

            /*if (($locked_time != null) and ($locked_time - strtotime('now') <= 0)) {
                $db = dbconnect();
                $recount = 0;
                $reset_time = null;
                $stmt = $db -> prepare('update members set failed_count=?,locked_time=? where member_id=?;');
                //DBに不正アクセスされても1つのレコードしか取れない
                if (!$stmt) {
                    die($db -> error);
                }
                $stmt -> bind_param('iii',$recount,$reset_time,$member_id);
                $result = $stmt -> execute();
                if (!$result) {
                    die($db -> error);
                }
            }*/   
        }
    }

    //var_dump($member_id);
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
            <p>パスワードを３回連続で失敗するとアカウントロックが発生します!</p>
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
                    <?php //↓ここはいらない↓;?>
                    <?php if (isset($error['lock']) and $error['lock'] === 'lock'): ?>
                        <p class="error">* ログイン失敗回数が3回を超えました。アカウントをロックします!!!</p>
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
