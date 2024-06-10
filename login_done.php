<?php require('db/dbconnect.php')?>

<?php
try {
    $mail = $_POST['mail'];
    $pass = $_POST['pass'];

    // htmlspecialchars()でエスケープ
    $mail = htmlspecialchars($mail, ENT_QUOTES, 'UTF-8');
    $pass = htmlspecialchars($pass, ENT_QUOTES, 'UTF-8');

    // md5()でパスワードをハッシュ化
    $pass = md5($pass);

    // SQL文
    $sql = 'SELECT 
    teachers.name, 
    teachers.position, 
    GROUP_CONCAT(teacher_classes.class_id) AS class_ids,
    GROUP_CONCAT(classes.year) AS class_years
FROM 
    teachers
LEFT JOIN 
    teacher_classes ON teacher_classes.teacher_id = teachers.id 
LEFT JOIN 
    classes ON classes.id = teacher_classes.class_id
WHERE 
    mail = ? AND password = ?
GROUP BY 
    teachers.id
';

    $stmt = $dbh->prepare($sql);
    $stmt->execute([$mail, $pass]);

    // DB接続を閉じる
    $dbh = null;

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rec) {
        session_start();
        $_SESSION['login'] = 1;
        $_SESSION['mail'] = $mail;
        $_SESSION['name'] = $rec['name'];
        $_SESSION['position'] = $rec['position'];
        $_SESSION['year'] = $rec['class_years'];
        
        // カンマ区切りのクラスIDを配列に変換して保存
        $_SESSION['class'] = $rec['class_ids'] ? explode(',', $rec['class_ids']) : [];
        
        header('Location: index.php');
        exit();
    } else {
        echo 'ログイン失敗';
        exit();
    }
} catch (Exception $e) {
    echo 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit();
}
?>
