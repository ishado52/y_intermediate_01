<!-- ログイン状態確認 -->
<?php require('../login_check.php');?>
<?php require('../db/dbconnect.php')?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-テスト編集</title>
</head>
<body>
<?php

try {
    $id = $_GET['id'];

    $sql = 'SELECT year,class,number,name FROM students WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $id;
    $stmt->execute($data);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbh = null;
    
} catch (Exception $e) {
    print $e;
}
?>

    <header>
        ヘッダー
    </header>

    <main>
        <h2>生徒情報編集</h2>
        <a href="index.php">生徒一覧</a><br/>
       <form method="post" action="edit_done.php">
        <label>学年</label>
        <input type="number" name="year" value="<?php print $student['year'] ?>">
        <label>クラス</label>
        <input type="text" name="class" value="<?php print $student['class'] ?>">
        <label>出席番号</label>
        <input type="number" name="number" value="<?php print $student['number'] ?>">
        <label>氏名</label>
        <input type="text" name="name" value="<?php print $student['name'] ?>">
        <input type="hidden" name="id" value="<?php print $id;?>">
        <input type="submit" value="変更">
       </form>
    </main>
</body>
</html>