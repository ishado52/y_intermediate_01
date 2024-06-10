<!-- ログイン状態確認 -->
<?php require('../login_check.php'); ?>
<!-- DB接続 -->
<?php require('../db/dbconnect.php') ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link href="https://use.fontawesome.com/releases/v6.2.0/css/all.css" rel="stylesheet">
</head>

<body>
    <?php

    $test = null;
    $year = null;
    $params = [];

    if (isset($_GET['test'])) {
        $test = $_GET['test'];
        $test = htmlspecialchars($test, ENT_QUOTES, 'UTF-8');
        $test_sql = ' WHERE tests.name = ?';
        $params[] = $test;
    } else {
        $test_sql = ' WHERE 1=1';
    }

    if (isset($_GET['year'])) {
        $year = $_GET['year'];
        $year = htmlspecialchars($year, ENT_QUOTES, 'UTF-8');
        $year_sql = ' AND tests.year = ?';
        $params[] = $year;
    } else {
        $year_sql = '';
    }

    try {
        $sql_template = 'SELECT exams.id, tests.name AS test_name, students.name AS student_name, exams.english, exams.math, exams.science, exams.social_studies, exams.japanese, exams.total 
            FROM exams 
            JOIN tests ON tests.id = exams.test_id 
            JOIN students ON students.id = exams.student_id' . $test_sql . $year_sql . 
            ' ORDER BY %s DESC 
            LIMIT 5';

        $order_columns = ['exams.total', 'exams.english', 'exams.math', 'exams.science', 'exams.social_studies', 'exams.japanese'];

        $stmt = [];
        foreach ($order_columns as $index => $column) {
            $sql_queries = sprintf($sql_template, $column);
            $stmt[$index] = $dbh->prepare($sql_queries);
            $stmt[$index]->execute($params);
        }

        $dbh = null;
    } catch (Exception $e) {
        print $e->getMessage();
    }
    ?>
      <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">成績管理アプリ</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h2>学年順位</h2>
        <div class="mb-3">
            <a href="ranking.php?test=<?php print $test; ?>&year=1" class="btn btn-outline-primary">1年</a>
            <a href="ranking.php?test=<?php print $test; ?>&year=2" class="btn btn-outline-primary">2年</a>
            <a href="ranking.php?test=<?php print $test; ?>&year=3" class="btn btn-outline-primary">3年</a>
        </div>

        <hr />

        <?php
        $subjects = ['総合', '英語', '数学', '理科', '社会', '国語'];
        foreach ($subjects as $index => $subject) : ?>
            <h3><?php print $subject ?>トップ5</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>順位</th>
                        <th>テスト名</th>
                        <th>氏名</th>
                        <th>英語</th>
                        <th>数学</th>
                        <th>理科</th>
                        <th>社会</th>
                        <th>国語</th>
                        <th>合計</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while (($exam = $stmt[$index]->fetch(PDO::FETCH_ASSOC)) && $i <= 5) : ?>
                        <tr <?php if($i<=3) : ?>style="font-weight: 900;"<?php endif?>>
                            <td><?php print $i; ?></td>
                            <td><?php print $exam['test_name']; ?></td>
                            <td><?php if($i<=3):?><i class="fas fa-crown"  <?php if($i==1){print 'style="color:#FFD43B"';}else if($i==2){print 'style="color:#B0B0B0"';}else if($i==3){print 'style="color:#Ac784D"';};?>></i><?php endif;?><?php print $exam['student_name']; ?></td>
                            <td><?php print $exam['english']; ?></td>
                            <td><?php print $exam['math']; ?></td>
                            <td><?php print $exam['science']; ?></td>
                            <td><?php print $exam['social_studies']; ?></td>
                            <td><?php print $exam['japanese']; ?></td>
                            <td><?php print $exam['total']; ?></td>
                        </tr>
                        <?php $i++; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endforeach ?>
    </main>

</body>

</html>
