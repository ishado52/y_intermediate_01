<!-- ログイン状態確認 -->
<?php require('../login_check.php'); ?>
<!-- DB接続 -->
<?php require('../db/dbconnect.php'); ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-生徒追加</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    $myclass = isset($_SESSION['class']) ? $_SESSION['class'] : [];
    $position = isset($_SESSION['position']) ? $_SESSION['position'] : '';
    try {
        $sql = 'SELECT * FROM classes';
        if ($position !== 'principal') {
            $sql .= ' WHERE id IN (' . implode(',', $myclass) . ')';
        }
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $dbh = null;
    } catch (Exception $e) {
        print $e->getMessage();
    }
    ?>
    <header class="bg-primary text-white text-center py-3 mb-4">
        <h1>生徒追加</h1>
    </header>

    <main class="container">
        <h2>生徒追加</h2>
        <a href="index.php" class="btn btn-secondary mb-3">生徒一覧</a><br />
        <form method="post" action="create_done.php">
            <div class="mb-3">
                <label class="form-label">クラス</label>
                <select class="form-select" name="class">
                    <?php while ($class = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <option value="<?php print $class['id'] ?>"><?php print $class['year']; ?> <?php print $class['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">学籍番号</label>
                <input class="form-control" type="number" name="number" required>
            </div>
            <div class="mb-3">
                <label class="form-label">氏名</label>
                <input class="form-control" type="text" name="name" required>
            </div>
            <input class="btn btn-primary" type="submit" value="登録">
        </form>
    </main>
</body>

</html>
