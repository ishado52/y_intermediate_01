<?php
require('../db/dbconnect.php');


header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="exam_results.csv"');

$output = fopen('php://output', 'w');


fputcsv($output, array('ID', 'テスト', '氏名', '英語', '数学', '理科', '社会', '国語', '合計'));

try {
    
    $sql = 'SELECT exams.id, tests.name AS test_name, students.name AS student_name, exams.english, exams.math, exams.science, exams.social_studies, exams.japanese, exams.total FROM exams,tests,students WHERE tests.id=exams.test_id AND students.id=exams.student_id';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    $dbh = null;
} catch (Exception $e) {
    echo 'エラー：' . $e->getMessage();
    exit();
}

fclose($output);
?>
