<!-- ログイン状態確認 -->
<?php require('../login_check.php'); ?>
<?php require('../db/dbconnect.php') ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-生徒詳細</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo "<p>IDが指定されていません。</p>";
        exit();
    }

    $position = isset($_SESSION['position']) ? $_SESSION['position'] : '';
    try {

// 生徒の情報を取得
$sql_student = 'SELECT class_id FROM students WHERE id = ?';
$stmt_student = $dbh->prepare($sql_student);
$stmt_student->execute([$id]);
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

// ログインしているユーザーのクラス情報を取得
$myclass = isset($_SESSION['class']) ? $_SESSION['class'] : [];

// 生徒のクラスがログインユーザーのクラスと異なる場合、権限がないことを表示
if ($position !== 'principal' && !in_array($student['class_id'], $myclass)) {
    echo "<p>この生徒の詳細を表示する権限がありません。</p>";
    exit();
}


        $sql = 'SELECT students.name as student_name, tests.name as test_name, exams.english, exams.math, exams.science, exams.social_studies, exams.japanese, exams.total 
                FROM exams
                JOIN tests ON tests.id = exams.test_id
                JOIN students ON students.id = exams.student_id
                WHERE exams.student_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$id]);
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>エラー: " . $e->getMessage() . "</div>";
        exit();
    }
    ?>
    <header class="bg-primary text-white text-center py-3 mb-4">
        <h1>成績管理アプリ</h1>
    </header>

    <main class="container">
        <h2>生徒詳細</h2>
        <div class="mb-3">
            <a href="../index.php" class="btn btn-secondary">トップページ</a>
        </div>
        <?php
        $first_row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($first_row) {
            print "<h3>{$first_row['student_name']}</h3>";
        } else {
            print "<div>データが見つかりませんでした。</div>";
            exit();
        }
        ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>テスト名</th>
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
                // 1行目のデータを表示
                print "<tr class='js-test_data'>
                        <td class='js-test_data_name'>{$first_row['test_name']}</td>
                        <td class='js-test_data_english'>{$first_row['english']}</td>
                        <td class='js-test_data_math'>{$first_row['math']}</td>
                        <td class='js-test_data_science'>{$first_row['science']}</td>
                        <td class='js-test_data_social_studies'>{$first_row['social_studies']}</td>
                        <td class='js-test_data_japanese'>{$first_row['japanese']}</td>
                        <td class='js-test_data_total'>{$first_row['total']}</td>
                    </tr>";

                // 残りのデータを表示
                while ($exam = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr class='js-test_data'>
                        <td class='js-test_data_name'><?php echo htmlspecialchars($exam['test_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class='js-test_data_english'><?php echo htmlspecialchars($exam['english'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class='js-test_data_math'><?php echo htmlspecialchars($exam['math'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class='js-test_data_science'><?php echo htmlspecialchars($exam['science'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class='js-test_data_social_studies'><?php echo htmlspecialchars($exam['social_studies'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class='js-test_data_japanese'><?php echo htmlspecialchars($exam['japanese'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class='js-test_data_total'><?php echo htmlspecialchars($exam['total'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- グラフ -->
        <div class="mt-5">
            <canvas id="myChart"></canvas>
        </div>
    </main>


    <script>
        const ctx = document.getElementById('myChart');
        const testDatas = document.querySelectorAll('.js-test_data');
        const labels = [];
        const data = [];

        testDatas.forEach(testData => {
            labels.push(testData.querySelector('.js-test_data_name').textContent);
            data.push(parseInt(testData.querySelector('.js-test_data_total').textContent));
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '得点',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
