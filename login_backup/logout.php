<?php
    session_start();

    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['member_id']);

    header('Location: login.php'); exit();//デフォルトはこの1行
?>
