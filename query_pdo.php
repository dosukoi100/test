<?php
    $dsn = 'mysql:dbname=x_test;host=localhost;port=8889;charset=utf8';

    $user = 'root';

    $passwd = 'root';
    try {
    $dbh = new PDO($dsn,$user,$passwd,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    //echo '接続成功です';
    $sql ='select * from members;';
    $stmt = $dbh -> query($sql);
    if (!$stmt) {
        die($dbh -> error);
    }
    $result = $stmt -> fetchall(PDO::FETCH_ASSOC);
    //1つだけのレコードならfetch(PDO::FETCH_ASSOC)
    //全てのレコードならfetchall(PDO::FETCH_ASSOC)
    if (!$result) {
        die($dbh -> error);
    }
    //var_dump($result);ここでの値は配列でstr型で渡される
    foreach ($result as $r) {
        echo $r['id'].'||';//ここは$resultでなく$r
        echo $r['name'].'||';
        echo $r['member_id']."<br>";
        usleep(500000);
    }
    } catch (PDOException $e) {
        echo $e -> getMessage();
        echo '接続失敗です';
    }

    $dbh = null;


?>