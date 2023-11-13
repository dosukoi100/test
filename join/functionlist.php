<?php
function h($value) {
    return htmlspecialchars($value,ENT_QUOTES);
}

function dbconnect() {
    $db = new mysqli('localhost:8889','root','root','x_test');
    if (!$db) {
        die($db -> error);
    }
    return $db;
}

function hello() {
    echo 'hello world';
}

function get_csrf_token() {
    $TOKEN_LENGTH = 16; //16バイト=32ビット
    //ランダムな疑似バイナリを生成
    $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
    //ランダムな疑似バイナリを16進数にしてリターンで返す
    return bin2hex($bytes);
}

?>
