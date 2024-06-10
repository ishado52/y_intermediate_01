<?php require('db/dbconnect.php')?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了</title>
</head>
<body>
<?php
try {
    $mail = $_POST['mail'];
    $pass = $_POST['pass'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $grade = $_POST['grade']; 
    $class = $_POST['class']; 

    $mail = htmlspecialchars($mail, ENT_QUOTES, 'UTF-8');
    $pass = htmlspecialchars($pass, ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $position = htmlspecialchars($position, ENT_QUOTES, 'UTF-8');

    $pass = md5($pass);

    $dbh->beginTransaction();

    $sql = 'INSERT INTO teachers (mail, password, name, position) VALUES (?, ?, ?, ?)';
    $stmt = $dbh->prepare($sql);
    $data = array($mail, $pass, $name, $position);
    $stmt->execute($data);

    $teacher_id = $dbh->lastInsertId();

    if ($position === 'chief') {
        $sql = 'SELECT id FROM classes WHERE year = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$grade]);
        $class_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($class_rows) {
            $sql = 'INSERT INTO teacher_classes (teacher_id, class_id) VALUES (?, ?)';
            $stmt = $dbh->prepare($sql);
            
            foreach ($class_rows as $class_row) {
                $class_id = $class_row['id'];
                $stmt->execute([$teacher_id, $class_id]);
            }
        } else {
            throw new Exception();
        }
    } elseif ($position === 'general') {
        $sql = 'SELECT id FROM classes WHERE year = ? AND name = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$grade, $class]);
        $class_row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($class_row) {
            
            $class_id = $class_row['id'];
            $sql = 'INSERT INTO teacher_classes (teacher_id, class_id) VALUES (?, ?)';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$teacher_id, $class_id]);
        } else {
            throw new Exception();
        }
    }

    $dbh->commit();

    $dbh = null;
} catch (Exception $e) {
    print $e->getMessage();
    $dbh->rollBack();
}
?>
<p>登録完了しました</p>
<a href="login.php">ログインページ</a>
</body>
</html>
