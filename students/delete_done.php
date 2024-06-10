<?php
require('../login_check.php');
require('../db/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $id = $_POST['id'];

        // SQL文
        $sql = 'DELETE FROM students WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);

        $dbh = null;

        // 削除成功
        $response = [
            'success' => true,
            'message' => '生徒を削除しました。',
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (Exception $e) {
        // エラーメッセージ
        $response = [
            'success' => false,
            'message' => 'エラー',
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    
    $response = [
        'success' => false,
        'message' => 'エラー',
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
