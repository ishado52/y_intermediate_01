<!-- ログイン状態確認 -->
<?php require('../login_check.php');?>
<?php require('../db/dbconnect.php') ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-成績登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    $myclass = isset($_SESSION['class']) ? $_SESSION['class'] : [];
    $position = isset($_SESSION['position']) ? $_SESSION['position'] : '';
    $year = isset($_SESSION['year']) ? $_SESSION['year'] : '';


    try {
        $sql01 = 'SELECT id,name FROM tests WHERE 1';
        if ($position !== 'principal') {
            $sql01 .= ' AND year IN ('.$year.')';
        }
        $stmt01 = $dbh->prepare($sql01);
        $stmt01->execute();

        $sql02 = 'SELECT id,name FROM students WHERE 1';
        if ($position !== 'principal') {
            $sql02 .= ' AND students.class_id IN (' . implode(',', $myclass) . ')';
        }
        $stmt02 = $dbh->prepare($sql02);
        $stmt02->execute();

        $dbh = null;
    } catch (Exception $e) {
        print $e;
    }
    ?>
    <header>
        <!-- Bootstrapのナビゲーションバー -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">成績管理アプリ</a>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <h2>成績登録</h2>
        <!-- リンクをBootstrapのスタイルに合わせる -->
        <a href="index.php" class="btn btn-secondary">成績一覧</a><br />
        <form method="post" action="create_done.php" class="mt-3">
            <label>テスト</label>
            <!-- Bootstrapのセレクトボックス -->
            <select name="test_id" class="form-select">
                <?php while ($test = $stmt01->fetch(PDO::FETCH_ASSOC)) : ?>
                    <option value="<?php print $test['id'] ?>"><?php print $test['name'] ?></option>
                <?php endwhile; ?>
            </select><br />
            <label>生徒氏名</label>
            <!-- Bootstrapのセレクトボックス -->
            <select name="student_id" class="form-select">
                <?php while ($student = $stmt02->fetch(PDO::FETCH_ASSOC)) : ?>
                    <option value="<?php print $student['id'] ?>"><?php print $student['name'] ?></option>
                <?php endwhile; ?>
            </select><br />
            <label>英語</label>
            <input type="number" min="0" max="100" value="0" name="english" class="form-control js-score"><br />
            <label>数学</label>
            <input type="number" min="0" max="100" value="0" name="math" class="form-control js-score"><br />
            <label>理科</label>
            <input type="number" min="0" max="100" value="0" name="science" class="form-control js-score"><br />
            <label>社会</label>
            <input type="number" min="0" max="100" value="0" name="social_studies" class="form-control js-score"><br />
            <label>国語</label>
            <input type="number" min="0" max="100" value="0" name="japanese" class="form-control js-score"><br />
            <hr>
            <label>合計</label>
            <input type="number" readonly min="0" max="500" value="0" name="sum" id="js-sum" class="form-control"><br />

            <input type="submit" value="登録" class="btn btn-primary">
        </form>
    </main>

    <script>
        
        const scores = {
            'english': 0,
            'math': 0,
            'science': 0,
            'social_studies': 0,
            'japanese': 0
        }

        let sum = Object.values(scores).reduce((acc, curr) => acc + curr, 0)

        const input_values = document.querySelectorAll('.js-score')
        const sum_value = document.getElementById('js-sum')

        const change = (score, name) => {
            scores[name] = parseInt(score)
            sum = Object.values(scores).reduce((acc, curr) => acc + curr, 0)
            sum_value.value = sum
        }

        input_values.forEach(value => {
            value.addEventListener('input', () => change(value.value, value.name))
        })
    </script>
</body>

</html>
