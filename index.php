<!-- ログイン状態確認 -->
<?php require('login_check.php');
// echo 'あなたのクラス:'.implode(", ", $_SESSION['class']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-TOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body>
<header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">成績管理アプリ</a>
            </div>
        </nav>
    </header>
    <main class="container">
        <div class="text-center mb-4">
            <h2>メニュー</h2>
            <p><span class="fw-bold"><?php print htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8'); ?></span>としてログイン中</p>
        </div>
        <ul class="list-group">
            <li class="list-group-item"><a href="tests/index.php">テスト</a></li>
            <li class="list-group-item"><a href="students/index.php">生徒</a></li>
            <li class="list-group-item"><a href="exams/index.php">テスト結果</a></li>
            <li class="list-group-item"><a href="logout.php">ログアウト</a></li>
        </ul>
    </main>
</body>
</html>
