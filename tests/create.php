<!-- ログイン状態確認 -->
<?php require('../login_check.php'); ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-テスト作成</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <header class="bg-primary text-white text-center py-3 mb-4">
        <h1>成績管理アプリ</h1>
    </header>

    <main class="container">
        <h2>テスト作成</h2>
        <a href="index.php" class="btn btn-secondary mb-3">テスト一覧</a><br />
        <form method="post" action="create_done.php">
            <div class="mb-3">
                <label class="form-label">学年</label>
                <input class="form-control" type="number" name="year" required>
            </div>
            <div class="mb-3">
                <label class="form-label">テスト名</label>
                <input class="form-control" type="text" name="name" required>
            </div>
            <input class="btn btn-primary" type="submit" value="登録">
        </form>
    </main>
</body>

</html>
