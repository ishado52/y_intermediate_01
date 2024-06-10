<!-- ログイン状態確認 -->
<?php require('../login_check.php');?>
<?php require('../db/dbconnect.php')?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-生徒削除</title>
</head>
<body>
<?php

try {
    $id = $_GET['id'];

    $sql = 'SELECT year,class,number,name FROM students WHERE id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $id;
    $stmt->execute($data);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbh = null;
    
} catch (Exception $e) {
    print $e;
}
?>

    <header>
        ヘッダー
    </header>

    <main>
        <h2>生徒編集</h2>
        <a href="index.php">生徒一覧</a><br/>
        <p>こちらの生徒を削除します</p>
        学年：<?php print $test['year'] ?><br/>
        クラス：<?php print $test['class'] ?><br/>
        出席番号：<?php print $test['number'] ?><br/>
        氏名：<?php print $test['name'] ?><br/>
       <form method="post" action="delete_done.php">
        <input type="hidden" name="id" value="<?php print $id;?>">
        <input type="submit" value="削除">
       </form>
    </main>
</body>
</html>