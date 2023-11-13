<?php
    //このファイルはCSVファイルを読込んでx_nichibetuテーブルに格納します
    $dsn = 'mysql:dbname=x_test;host=localhost;port=8889;charset=utf8';

    $user = 'root';

    $passwd = 'root';

    try {
        $dbh = new PDO($dsn,$user,$passwd,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);

        $stmt = $dbh -> prepare('insert into x_nichibetu 
        (member_id,date,len,jishalen,jishakaisuu,jiin,nenryou,uriage,created,modified) 
        values(?,?,?,?,?,?,?,?,?,?);');

        $file = new splfileobject('test2.csv','r');

        $lists = [];

        while (!$file -> eof()) {
            $lists = $file -> fgetcsv();
            //var_dump($lists)."<br>";//検証用
            //$stmt -> bindParam(1,$lists[0],PDO::PARAM_INT);
            /*$lists[0]つまりx_nichibetuテーブルのidはprimary key
            //でauto_incrementなので$lists[1]つまりmember_idから始める
            //ので $stmt -> bindParam(1,$lists[1],PDO::PARAM_INT);から
            始める*/
            $stmt -> bindParam(1,$lists[1],PDO::PARAM_INT);
            $stmt -> bindParam(2,$lists[2],PDO::PARAM_INT);
            $stmt -> bindParam(3,$lists[3],PDO::PARAM_INT);
            $stmt -> bindParam(4,$lists[4],PDO::PARAM_INT);
            $stmt -> bindParam(5,$lists[5],PDO::PARAM_INT);
            $stmt -> bindParam(6,$lists[6],PDO::PARAM_INT);
            $stmt -> bindParam(7,$lists[7],PDO::PARAM_INT);
            $stmt -> bindParam(8,$lists[8],PDO::PARAM_INT);
            $stmt -> bindParam(9,$lists[9],PDO::PARAM_INT);
            $stmt -> bindParam(10,$lists[10],PDO::PARAM_INT);
           
            $result =$stmt -> execute();
            if (!$result) {
                die($dbh -> error);
            }

        }
    } catch (PDOException $e) {
        echo $e -> getMessage();
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fromcsv_todb</title>
</head>
<body>
    <?php if ($result) {
        echo '成功です';
    }
    ?>
       
</body>
</html>