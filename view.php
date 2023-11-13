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
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    //セッションのidを受け取る
    if (!$id) {  //エラー処理
        header('location: index.php');
        exit();
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
        <p>&laquo;<a href="index.php">一覧にもどる</a></p>

        <?php 
                $stmt = $db -> prepare('select p.id,p.member_id,p.message,p.created,m.name,m.picture 
                from posts as p ,members as m where p.id = ? and m.id = p.member_id order by p.id desc;');
                //p.idを指定して1件だけ取ってくる。(p.id=?の部分)
                if (!$stmt) {
                    die($db -> error);
                }
                $stmt -> bind_param('i',$id);//bind_paramさせる
                $success = $stmt -> execute();
                if (!$success) {
                    die($db -> error);
                }
                $stmt -> bind_result($id,$member_id,$message,$created,$name,$picture);
                if ($stmt -> fetch())://もし、$stmtが取れたら(詳細なので1つ)
                    //bbs/index.phpはwhile文       
            ?>
        <div class="msg">
            <?php if ($picture):?>
                <img src="./join/member_picture/<?php echo h($picture);?>" width="48" height="48" alt=""/>
            <?php endif;?>
            <p><?php echo h($message);?><span class="name">[<?php echo h($name);?>]</span></p>
            <p class="day"><a href="view.php?id="><?php echo h($created);?></a>
            <?php if ($_SESSION['id'] === $member_id):?>
                [<a href="delete.php?id=<?php echo h($id);?>" style="color: #F33;">削除</a>]
            <?php endif;?>
            </p>
        </div>
        <?php else://$stmtが取れてなかったら?>
            <p>その投稿は削除されたか、URLが間違えています</p>
        <?php endif;?>
    </div>
</div>
</body>

</html>