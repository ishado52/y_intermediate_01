<!-- ログイン状態確認 -->
<?php require('../login_check.php');?>
<?php require('../db/dbconnect.php')?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-成績登録</title>
</head>
<body>
<?php
    try{

        $test_id = $_POST['test_id'];
        $student_id = $_POST['student_id'];
        $english = $_POST['english'];
        $math = $_POST['math'];
        $science = $_POST['science'];
        $social_studies = $_POST['social_studies'];
        $japanese = $_POST['japanese'];
        $total = $_POST['sum'];

        $test_id = htmlspecialchars($test_id,ENT_QUOTES,'UTF-8');
        $student_id = htmlspecialchars($student_id,ENT_QUOTES,'UTF-8');
        $english = htmlspecialchars($english,ENT_QUOTES,'UTF-8');
        $math = htmlspecialchars($math,ENT_QUOTES,'UTF-8');
        $science = htmlspecialchars($science,ENT_QUOTES,'UTF-8');
        $social_studies = htmlspecialchars($social_studies,ENT_QUOTES,'UTF-8');
        $japanese = htmlspecialchars($japanese,ENT_QUOTES,'UTF-8');
        $total = htmlspecialchars($total,ENT_QUOTES,'UTF-8');

        // SQL文
        $sql = 'INSERT INTO exams(test_id,student_id,english,math,science,social_studies,japanese,total,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,NOW(),NOW())';
        $stmt = $dbh ->prepare($sql);
        $data[] = $test_id;
        $data[] = $student_id;
        $data[] = $english;
        $data[] = $math;
        $data[] = $science;
        $data[] = $social_studies;
        $data[] = $japanese;
        $data[] = $total;
        $stmt -> execute($data);

        $dbh = null;


    }catch(Exception $e){
        print $e;
        exit();
    };
    ?>

    <header>
        ヘッダー
    </header>

    <main>
        <h2>成績登録</h2>
        <p>成績を登録しました。</p>
        <a href="index.php">成績一覧</a>
    </main>
</body>
</html>