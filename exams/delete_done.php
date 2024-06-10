<?php
require('../login_check.php');
require('../db/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];

        $sql = 'DELETE FROM exams WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);

        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    header('Location: index.php');
    exit;
}
?>
