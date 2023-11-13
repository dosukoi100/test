<?php
    session_start();
    require('./join/functionlist.php');
    $db = dbconnect();//頭に持ってきてどこからでも使えるように

    if (isset($_SESSION['name']) and isset($_SESSION['id'])) {
        //↑セッションmembersのnameとidがあれば↑
        $name = $_SESSION['name'];//こうしないとwarningが出る
        $id = $_SESSION['id'];
    } else {
        header('location: login.php');
        exit();
    } // ↑bbs/index.phpからまるパクリ↑

    $stmt = $db -> prepare('delete from posts where id=? and member_id=? limit 1;');
    //limit 1は一応のフェールセーフ
    if (!$stmt) {
        die($db -> error);
    }
    $id_from_bbs_index_php = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    //$id_from_bbs_index_phpはbbs/index.phpからくるpostsのid
    if (!$id_from_bbs_index_php) {
        header('location: index.php');  // 一応
    }
    $stmt -> bind_param('ii',$id_from_bbs_index_php,$id);//教材では$post_id
    //$idはmembersテーブルのid
    $success = $stmt -> execute();
    if (!$success) {
        die($db -> error);
    }


    header('Location: index.php'); exit();
?>
