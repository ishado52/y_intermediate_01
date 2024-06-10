<?php
    require('../login_check.php');
    require('../db/dbconnect.php');

    // レスポンス用の配列を初期化
    $response = array();

    try {
        // 受信したデータを取得
        $class = $_POST['class_id'];
        $number = $_POST['number'];
        $name = $_POST['name'];
        $id = $_POST['id'];

  
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        // SQL文
        $sql = 'UPDATE students SET class_id=?, number=?, name=?, updated_at=NOW() WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$class, $number, $name, $id]);

        // 成功時のメッセージ
        $response['success'] = true;
        $response['message'] = '生徒情報を更新しました';

    } catch (Exception $e) {
        // 失敗時のメッセージ
        $response['success'] = false;
        $response['message'] = 'エラー: ' . $e->getMessage();
    }

 
    header('Content-Type: application/json');
    echo json_encode($response);
?>
