<?php 
    session_start();
    require('./join/functionlist.php');
    $db = dbconnect();//頭に持ってきてどこからでも使えるように

    if (isset($_SESSION['name']) and isset($_SESSION['id'])) {
        //↑セッションnameとidがあれば↑
        $name = $_SESSION['name'];//こうしないとwarningが出る
        $id = $_SESSION['id'];
    } else {
        header('location: login.php');
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $message = filter_input(INPUT_POST,'message',FILTER_SANITIZE_STRING);
        //ここは変数$messageで受け取る(配列でない)
        $stmt = $db -> prepare('insert into posts (message,member_id) values(?,?);');
        if (!$stmt) {
            die($db -> error);
        }
        $stmt -> bind_param('si',$message,$id);
        //filter_inputの$massageとbbs/login.phpから来たセッションidを
        //message,member_idに格納する
        $success = $stmt -> execute();
        if (!$success) {
            die($db -> error);
        }
        header('location: .');//遷移させて$messageのセッションを外す
        exit();  //$idと$nameのセッションは生きているのでbbs/login.phpには遷移しない
    }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ひとこと掲示板</title>

    <link rel="stylesheet" href="style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>ひとこと掲示板</h1>
    </div>
    <div id="content">
        <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
        <form action="" method="post">
            <dl>
                <dt><?php echo h($name); ?>さん、メッセージをどうぞ</dt>
                <dd>
                    <textarea name="message" cols="50" rows="5"></textarea>
                </dd>
            </dl>
            <div>
                <p>
                    <input type="submit" value="投稿する"/>
                </p>
            </div>
        </form>
            <?php 
                $stmt = $db -> prepare('select p.id,p.member_id,p.message,p.created,m.name,m.picture 
                from posts as p ,members as m where m.id = p.member_id order by p.id desc;');
                if (!$stmt) {
                    die($db -> error);
                }
                $success = $stmt -> execute();
                if (!$success) {
                    die($db -> error);
                }
                $stmt -> bind_result($id,$member_id,$message,$created,$name,$picture);
                while ($stmt -> fetch()):
            
            ?>
        <div class="msg">
            <?php if ($picture):?>
                <img src="./join/member_picture/<?php echo h($picture);?>" width="48" height="48" alt=""/>
            <?php endif;?>
            <p><?php echo h($message);?><span class="name">[<?php echo h($name);?>]</span></p>
            <p class="day"><a href="view.php?id=<?php echo h($id);?>"><?php echo h($created);?></a>
            <?php if ($_SESSION['id'] === $member_id):?>
                [<a href="delete.php?id=<?php echo h($id)//他でもそうですが$idはpostsのis;?>" style="color: #F33;">削除</a>]
            <?php endif; ?>
            </p>
        </div>
        <?php endwhile;?>
    </div>
</div>
</body>

</html>