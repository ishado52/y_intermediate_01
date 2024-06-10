<!-- ログイン状態確認 -->
<?php require('../login_check.php');?>
<?php require('../db/dbconnect.php')?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-生徒追加</title>
</head>
<body>
<?php
    try{

        $class_id = $_POST['class'];
        $number = $_POST['number'];
        $name = $_POST['name'];

        $class_id = htmlspecialchars($class_id,ENT_QUOTES,'UTF-8');
        $number = htmlspecialchars($number,ENT_QUOTES,'UTF-8');
        $name = htmlspecialchars($name,ENT_QUOTES,'UTF-8');

        // SQL文
        $sql = 'INSERT INTO students(class_id,number,name,created_at,updated_at) VALUES (?,?,?,NOW(),NOW())';
        $stmt = $dbh ->prepare($sql);
        $data[] = $class_id;
        $data[] = $number;
        $data[] = $name;
        $stmt -> execute($data);

        $dbh = null;

        print 'クラス'.$class_id.'<br/>';
        print '出席番号'.$number.'<br/>';
        print '氏名'.$name;

    }catch(Exception $e){
        print $e;
        exit();
    };
    ?>


    <main>
        <h2>テスト作成</h2>
        <p>テストを作成しました。</p>
        <a href="index.php">テスト一覧</a>
    </main>
</body>
</html>