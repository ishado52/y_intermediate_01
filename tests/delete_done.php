<?php
require('../login_check.php');
require('../db/dbconnect.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {

        $id = $_POST['id'];


        $sql = 'DELETE FROM tests WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);

        // データベース接続を閉じる
        $dbh = null;

        // 成功メッセージ
        echo json_encode(['success' => true, 'message' => 'テストを削除しました']);
    } catch (Exception $e) {
        // エラーメッセージ
        echo json_encode(['success' => false, 'message' => 'エラー: ' . $e->getMessage()]);
    }
} else {
    
    echo json_encode(['success' => false, 'message' => 'エラー']);
}
?>
