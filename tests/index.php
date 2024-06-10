<!-- ログイン状態確認 -->
<?php require('../login_check.php'); ?>
<!-- DB接続 -->
<?php require('../db/dbconnect.php') ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-テスト一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php

    $position = $_SESSION['position'];

    try {
        $sql = 'SELECT * FROM tests WHERE 1';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

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
        <h2>年間テスト一覧</h2>
        <div class="mb-3">
            <a href="../index.php" class="btn btn-secondary">トップページ</a>
            <?php if($position=='principal'):?>
            <a href="create.php" class="btn btn-success">テスト作成</a>
            <?php endif; ?>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>学年</th>
                    <th>テスト名</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($test = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <?php $i++ ?>
                    <tr>
                        <td><?php print $test['id']; ?></td>
                        <td><?php print $test['year']; ?></td>
                        <td><?php print $test['name']; ?></td>
                        <td>
                        <?php if($position=='principal'):?> 
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_edit_<?php print $i; ?>">
                                編集
                            </button>
                            <?php else:?>
                                <button type="button" disabled class="btn btn-dark btn-sm" ?>
                                編集
                            </button>
            <?php endif; ?>
                           
                            <div class="modal fade" id="modal_edit_<?php print $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">テスト編集</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="edit_done.php">
                                                <div class="mb-3">
                                                    <label class="form-label">学年</label>
                                                    <input type="number" name="year" class="form-control" value="<?php print $test['year'] ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">テスト名</label>
                                                    <input type="text" name="name" class="form-control" value="<?php print $test['name'] ?>">
                                                </div>
                                                <input type="hidden" name="id" value="<?php print $test['id']; ?>">
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
                        <?php if($position=='principal'):?> 
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal_delete_<?php print $i; ?>">
                                削除
                            </button>
                            <?php else:?>
                                <button type="button" disabled class="btn btn-dark btn-sm" ?>
                                削除
                            </button>
                            <?php endif;?>
                            <div class="modal fade" id="modal_delete_<?php print $i; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">削除確認</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>こちらのテストを削除しますか？</p>
                                            <ul class="list-unstyled">
                                                <li>学年：<?php print $test['year'] ?></li>
                                                <li>テスト名：<?php print $test['name'] ?></li>
                                            </ul>
                                            <form method="post" action="delete_done.php">
                                                <input type="hidden" name="id" value="<?php print $test['id']; ?>">
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
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>


</body>
<script>
    // テストの編集
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

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
