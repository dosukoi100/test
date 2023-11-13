<?php
    //このファイルはデータベースに接続してx_nichibetuテーブルのレコードを取得して
    //のレコードを取得してCSVファイルを作成します
    //fetchallを使いたいのでPDO(PHPデータオブジェクト)使用
    //VScodeのターミナルでは動かないのでHTMLを追加
    //サーバーのターミナルなら動く

    $dsn = 'mysql:dbname=x_test;host=localhost;port=8889;charset=utf8';

    $user = 'root';

    $passwd = 'root';
    try {
    $dbh = new PDO($dsn,$user,$passwd,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    $date = 202201;//ここは集計したい年月を指定

    $stmt = $dbh -> prepare('select * from x_nichibetu where date = ?;');

    $stmt -> bindParam(1,$date,PDO::PARAM_INT);

    $result = $stmt -> execute();
    } catch (PDOException $e) {
        echo $e -> getMessage();
    }

    $lists = [];//からのリストを用意
    $lists = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    //cashテーブルからレコードを引っ張て来てリスト化する

    $file = new splfileobject('test2.csv','a');
    //作成するCSVファイル名を指定して、モードを指定する

    foreach ($lists as $l) {
        echo $l['date'].'||';
        echo $l['len'].'||';
        echo $l['uriage'].'<br>';

        //上の3つは検証用

        $file -> fputcsv($l);

        //配列（リスト）$lの値を$file(test1.csv)に格納していく
        
    }
    /*require('./join/functionlist.php');

    $db = dbconnect();

    $date = 202201;//ここに取得したい年月を入れる

    $stmt = $db -> prepare('select * from cash where date = ?;');

    $stmt -> bind_param('i',$date);

    $result = $stmt -> execute();
    if (!$result) {
        die($db -> error);
    }

    $lists = [];
    $lists = $stmt -> fetch_all();

    foreach ($list as $l) {
        echo $l['date'].'||';
        echo $l['totalout'].'||';
        echo $l['totalin']."<br>";
    }
    */


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
</head>
<body>
    <?php var_dump($result);?>
</body>
</html>