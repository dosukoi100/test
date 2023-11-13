<?php
    $dsn = 'mysql:dbname=x_test;host=localhost;port=8889;charset=utf8';//CentOsならport=3306
    $user = 'root';
    $passwd = 'root'; //CentOsならChie_100

    try {
        $dbh = new PDO($dsn,$user,$passwd,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
            //↑SQLインジェクション対策 prepareを使うと通常tureだがfalseにする
        ]);
        //echo '接続成功';
        $member_id = 159;
        $sql = 'select * from members where member_id = :member_id;';

        $stmt = $dbh -> prepare($sql);
        if (!$stmt) {
            die($dbh -> error);
        }

        $stmt -> bindValue(':member_id',$member_id,PDO::PARAM_INT);

        $stmt -> execute();
        
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            die($dbh -> error);
        }

        //var_dump($result);
        
        echo $result['id']."<br>";
        echo $result['name']."<br>";
        echo $result['member_id']."<br>";


    } catch (PDOException $e) {
        echo '接続失敗'.$e -> getMessage();
    }

    $dbh = null;

?>