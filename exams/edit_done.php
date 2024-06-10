<?php
require('../login_check.php');
require('../db/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $english = $_POST['english'];
        $math = $_POST['math'];
        $science = $_POST['science'];
        $social_studies = $_POST['social_studies'];
        $japanese = $_POST['japanese'];
        $total = $_POST['total'];

        $sql = 'UPDATE exams SET english=?, math=?, science=?, social_studies=?, japanese=?, total=? WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$english, $math, $science, $social_studies, $japanese, $total, $id]);

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
