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

?>
