<!-- ログイン状態確認 -->
<?php require('../login_check.php');?>
<?php require('../db/dbconnect.php')?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-テスト作成</title>
</head>
<body>
<?php
    try{

        $year = $_POST['year'];
        $name = $_POST['name'];

        $year = htmlspecialchars($year,ENT_QUOTES,'UTF-8');
        $name = htmlspecialchars($name,ENT_QUOTES,'UTF-8');

        // SQL文
        $sql = 'INSERT INTO tests(year,name,created_at,updated_at) VALUES (?,?,NOW(),NOW())';
        $stmt = $dbh ->prepare($sql);
        $data[] = $year;
        $data[] = $name;
        $stmt -> execute($data);

        $dbh = null;

        print '学年'.$year.'<br/>';
        print 'テスト名'.$name;

    }catch(Exception $e){
        print $e;
        exit();
    };
    ?>

    <header>
        ヘッダー
    </header>

    <main>
        <h2>テスト作成</h2>
        <p>テストを作成しました。</p>
        <a href="index.php">テスト一覧</a>
    </main>
</body>
</html>