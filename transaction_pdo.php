<?php
    $dsn = 'mysql:dbname=x_test;host=localhost;port=8889;charset=utf8';

    $user = 'root';

    $passwd = 'root';

    try {
        $dbh = new PDO($dsn,$user,$passwd,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);

        $dbh -> beginTransaction();

        $member_id = 1111;

        $stmt = $dbh -> prepare('update members set member_id = ? where name = "test1";');

        $stmt -> bindParam(1,$member_id,PDO::PARAM_INT);

        $result = $stmt -> execute();

        //var_dump($result);

        $dbh -> commit();

        echo '接続成功です';

    } catch (PDOException $e) {
        echo $e -> getMessage();
        echo '接続失敗です';
        $dbh -> rollBack();
    }

?>