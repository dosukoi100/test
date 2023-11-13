<?php
    //このファイルはCSVファイルを読込んでcashテーブルに格納します
    $dsn = 'mysql:dbname=x_test;host=localhost;port=8889;charset=utf8';

    $user = 'root';

    $passwd = 'root';

    try {
        $dbh = new PDO($dsn,$user,$passwd,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);

        $stmt = $dbh -> prepare('insert into cash (member_id,date,suidou,shoumou_b,
        shoumou_t,shoumou_p,jimu,gas,oil,lease,repair,carcell,office,comm,tel,sys,book,
        carins,groupfee,cartax,toll,exchange,taxac,clean,basepay,repay,benefit,life,stack,
        totalout,inget,chip,draw,other,totalin,created,modified) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
        ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');

        $file = new splfileobject('test1.csv','r');

        $lists = [];

        while (!$file -> eof()) {
            $lists = $file -> fgetcsv();
            //var_dump($lists)."<br>";検証用
            //$stmt -> bindParam(1,$lists[0],PDO::PARAM_INT);
            /*$lists[0]つまりcashテーブルのidはprimary key
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
            $stmt -> bindParam(11,$lists[11],PDO::PARAM_INT);
            $stmt -> bindParam(12,$lists[12],PDO::PARAM_INT);
            $stmt -> bindParam(13,$lists[13],PDO::PARAM_INT);
            $stmt -> bindParam(14,$lists[14],PDO::PARAM_INT);
            $stmt -> bindParam(15,$lists[15],PDO::PARAM_INT);
            $stmt -> bindParam(16,$lists[16],PDO::PARAM_INT);
            $stmt -> bindParam(17,$lists[17],PDO::PARAM_INT);
            $stmt -> bindParam(18,$lists[18],PDO::PARAM_INT);
            $stmt -> bindParam(19,$lists[19],PDO::PARAM_INT);
            $stmt -> bindParam(20,$lists[20],PDO::PARAM_INT);
            $stmt -> bindParam(21,$lists[21],PDO::PARAM_INT);
            $stmt -> bindParam(22,$lists[22],PDO::PARAM_INT);
            $stmt -> bindParam(23,$lists[23],PDO::PARAM_INT);
            $stmt -> bindParam(24,$lists[24],PDO::PARAM_INT);
            $stmt -> bindParam(25,$lists[25],PDO::PARAM_INT);
            $stmt -> bindParam(26,$lists[26],PDO::PARAM_INT);
            $stmt -> bindParam(27,$lists[27],PDO::PARAM_INT);
            $stmt -> bindParam(28,$lists[28],PDO::PARAM_INT);
            $stmt -> bindParam(29,$lists[29],PDO::PARAM_INT);
            $stmt -> bindParam(30,$lists[30],PDO::PARAM_INT);
            $stmt -> bindParam(31,$lists[31],PDO::PARAM_INT);
            $stmt -> bindParam(32,$lists[32],PDO::PARAM_INT);
            $stmt -> bindParam(33,$lists[33],PDO::PARAM_INT);
            $stmt -> bindParam(34,$lists[34],PDO::PARAM_INT);
            $stmt -> bindParam(35,$lists[35],PDO::PARAM_INT);
            $stmt -> bindParam(36,$lists[36],PDO::PARAM_INT);
            $stmt -> bindParam(37,$lists[37],PDO::PARAM_INT);
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