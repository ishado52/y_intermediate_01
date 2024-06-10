<?php
// DB接続
try {
    $dbh = new PDO('mysql:host=localhost;dbname=grade_management', 'root', 'root');
} catch (Exception $e) {
    print $e;
}
