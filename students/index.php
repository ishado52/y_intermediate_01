<!-- ログイン状態確認 -->
<?php require('../login_check.php'); ?>
<?php require('../db/dbconnect.php') ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-生徒一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php

$myclass = isset($_SESSION['class']) ? $_SESSION['class'] : [];
$position = isset($_SESSION['position']) ? $_SESSION['position'] : '';

    try {
        // 生徒一覧を取得
        $sql = 'SELECT students.id, students.number, students.name, classes.year, classes.name AS class, classes.id AS class_id FROM students JOIN classes ON classes.id = students.class_id';
        if ($position !== 'principal') {
            $sql .= ' WHERE students.class_id IN (' . implode(',', $myclass) . ')';
        }
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        // クラス一覧を取得
        $classSql = 'SELECT id, year, name FROM classes';
        $classStmt = $dbh->prepare($classSql);
        $classStmt->execute();

        // クラス情報を配列に格納
        $classes = [];
        while ($class = $classStmt->fetch(PDO::FETCH_ASSOC)) {
            $classes[] = $class;
        }

        $dbh = null;
    } catch (Exception $e) {
        print $e;
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
        <h2>生徒一覧</h2>
        <div class="mb-3">
            <a href="../index.php" class="btn btn-secondary">トップページ</a>
            <a href="create.php" class="btn btn-primary">生徒追加</a>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>学年</th>
                    <th>クラス</th>
                    <th>学籍番号</th>
                    <th>氏名</th>
                    <th>詳細</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($student = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php print $student['id']; ?></td>
                        <td><?php print $student['year']; ?></td>
                        <td><?php print $student['class']; ?></td>
                        <td><?php print $student['number']; ?></td>
                        <td><?php print $student['name']; ?></td>
                        <td><a href="detail.php?id=<?php print $student['id']; ?>" class="btn btn-light btn-sm">詳細</a></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_edit_<?php print $i; ?>">
                                編集
                            </button>
                            <div class="modal fade" id="modal_edit_<?php print $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">編集</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="edit_done.php">
                                                <div class="mb-3">
                                                    <label class="form-label">クラス</label>
                                                    <select name="class_id" class="form-select">
                                                        <?php foreach ($classes as $class) : ?>
                                                            <option value="<?php print $class['id']; ?>" <?php if ($class['id'] == $student['class_id']) print 'selected'; ?>>
                                                                <?php print htmlspecialchars($class['year'] . ' ' . $class['name'], ENT_QUOTES, 'UTF-8'); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">学籍番号</label>
                                                    <input type="number" name="number" class="form-control" value="<?php print htmlspecialchars($student['number'], ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">氏名</label>
                                                    <input type="text" name="name" class="form-control" value="<?php print htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>
                                                <input type="hidden" name="id" value="<?php print $student['id']; ?>">
                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-primary">変更</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal_delete_<?php print $i; ?>">
                                削除
                            </button>
                            <div class="modal fade" id="modal_delete_<?php print $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">削除確認</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>こちらの生徒を削除しますか？</p>
                                            <ul class="list-unstyled">
                                                <li>学年：<?php print $student['year'] ?></li>
                                                <li>クラス：<?php print $student['class'] ?></li>
                                                <li>学籍番号：<?php print $student['number'] ?></li>
                                                <li>氏名：<?php print $student['name'] ?></li>
                                            </ul>
                                            <form method="post" action="delete_done.php">
                                                <input type="hidden" name="id" value="<?php print $student['id']; ?>">
                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-danger">削除</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                    $i++;
                endwhile; ?>
            </tbody>
        </table>
    </main>

</body>
<script>
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // デフォルトのフォーム送信をキャンセル

            var formData = new FormData(form);
            var url = form.getAttribute('action');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log(response.message);
                        location.reload(); 
                    } else {
                        console.log(response.message);
                    }
                } else {
                    alert('エラーが発生しました: ' + xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.log('リクエストが失敗しました');
            };

            xhr.send(formData);
        });
    });
</script>



</html>
