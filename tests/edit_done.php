<?php
require('../login_check.php');
require('../db/dbconnect.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
    
        $year = $_POST['year'];
        $name = $_POST['name'];
        $id = $_POST['id'];

        $year = htmlspecialchars($year, ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        $sql = 'UPDATE tests SET year=?, name=?, updated_at=NOW() WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$year, $name, $id]);

        $dbh = null;

        // 成功メッセージ
        echo json_encode(['success' => true, 'message' => 'テストを編集しました']);
    } catch (Exception $e) {
        // エラーメッセージ
        echo json_encode(['success' => false, 'message' => 'エラー: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'エラー']);
}
?>
