<?php
    $dsn = 'mysql:dbname=x_test;host=localhost;port=8889;charset=utf8';//CentOsならport=3306
    $user = 'root';
    $passwd = 'root'; //CentOsならChie_100

    try {
        $dbh = new PDO($dsn,$user,$passwd,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        echo '接続成功';
    } catch (PDOException $e) {
        echo '接続失敗'.$e -> getMessage();
    }

    $dbh = null;

?>