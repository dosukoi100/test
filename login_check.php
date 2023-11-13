<?php 
    session_start();
    require('./join/functionlist.php');

    $post_token = $_POST['token'];
    $session_token = $_SESSION['token'];
    echo $post_token."<br>";
    echo $session_token;
    //このファイルは$_POSTと$_SESSIONの値を見るファイル
    //検証の際はformタグのアクション属性にこのファイル名(パス)を入れます。


?>