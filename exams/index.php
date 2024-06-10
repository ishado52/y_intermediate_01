<?php
require('../login_check.php');
require('../db/dbconnect.php');
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績管理アプリ-テスト結果</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    $myclass = isset($_SESSION['class']) ? $_SESSION['class'] : [];
    $position = isset($_SESSION['position']) ? $_SESSION['position'] : '';
    $student_name = isset($_GET['student_name']) ? htmlspecialchars($_GET['student_name'], ENT_QUOTES, 'UTF-8') : null;
    $student_number = isset($_GET['student_number']) ? htmlspecialchars($_GET['student_number'], ENT_QUOTES, 'UTF-8') : null;
    try {
        // テストを取得
        $sql01 = 'SELECT * FROM tests';
        $stmt01 = $dbh->prepare($sql01);
        $stmt01->execute();

        // 検索条件でテスト結果を取得
        $sql02 = 'SELECT exams.id, tests.name AS test_name, students.name AS student_name, 
                         exams.english, exams.math, exams.science, exams.social_studies, 
                         exams.japanese, exams.total 
                  FROM exams 
                  JOIN tests ON tests.id = exams.test_id 
                  JOIN students ON students.id = exams.student_id 
                  WHERE 1=1';

        $params = [];
        if ($position !== 'principal') {
            $sql02 .= ' AND students.class_id IN (' . implode(',', $myclass) . ')';
        }

        if ($student_name) {
            $sql02 .= ' AND students.name = :student_name';
            $params[':student_name'] = $student_name;
        } elseif ($student_number) {
            $sql02 .= ' AND students.number = :student_number';
            $params[':student_number'] = $student_number;
        }

        $stmt02 = $dbh->prepare($sql02);
        foreach ($params as $param => $value) {
            $stmt02->bindValue($param, $value, PDO::PARAM_STR);
        }
        $stmt02->execute();
        $dbh = null;
    } catch (Exception $e) {
        echo 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
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
        <h2>テスト結果</h2>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <a href="../index.php" class="btn btn-secondary">トップページ</a>
                <a href="create.php" class="btn btn-primary">成績登録</a>
                <a href="download.php" class="btn btn-light">CSVダウンロード</a>
            </div>
        </div>
        <hr />
        <h3>ランキング</h3>
        <?php while ($test = $stmt01->fetch(PDO::FETCH_ASSOC)) : ?>
            <a href="ranking.php?test=<?php echo htmlspecialchars($test['name'], ENT_QUOTES, 'UTF-8'); ?>&year=<?php echo htmlspecialchars($test['year'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-link">
            <?php echo htmlspecialchars($test['year'], ENT_QUOTES, 'UTF-8'); ?>年
            <?php echo htmlspecialchars($test['name'], ENT_QUOTES, 'UTF-8'); ?>
            </a>
        <?php endwhile; ?>
        <hr />
        <h3>生徒検索</h3>
        <form class="mb-3" id="filter">
            <div class="form-check form-check-inline">
                <input type="radio" name="param" value="name" checked class="form-check-input js-btn_radio">
                <label class="form-check-label">氏名</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="param" value="number" class="form-check-input js-btn_radio">
                <label class="form-check-label">学籍番号</label>
            </div>
            <div class="input-group">
                <input type="text" name="text" id="js-text_field" class="form-control">
                <button type="button" id="js-btn_submit" class="btn btn-primary">検索</button>
            </div>
        </form>
        <hr />
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>テスト</th>
                    <th>氏名</th>
                    <th>英語</th>
                    <th>数学</th>
                    <th>理科</th>
                    <th>社会</th>
                    <th>国語</th>
                    <th>合計</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($exam = $stmt02->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($exam['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['test_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['student_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['english'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['math'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['science'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['social_studies'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['japanese'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($exam['total'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo htmlspecialchars($exam['id'], ENT_QUOTES, 'UTF-8'); ?>" data-test_name="<?php echo htmlspecialchars($exam['test_name'], ENT_QUOTES, 'UTF-8'); ?>" data-student_name="<?php echo htmlspecialchars($exam['student_name'], ENT_QUOTES, 'UTF-8'); ?>" data-english="<?php echo htmlspecialchars($exam['english'], ENT_QUOTES, 'UTF-8'); ?>" data-math="<?php echo htmlspecialchars($exam['math'], ENT_QUOTES, 'UTF-8'); ?>" data-science="<?php echo htmlspecialchars($exam['science'], ENT_QUOTES, 'UTF-8'); ?>" data-social_studies="<?php echo htmlspecialchars($exam['social_studies'], ENT_QUOTES, 'UTF-8'); ?>" data-japanese="<?php echo htmlspecialchars($exam['japanese'], ENT_QUOTES, 'UTF-8'); ?>" data-total="<?php echo htmlspecialchars($exam['total'], ENT_QUOTES, 'UTF-8'); ?>">編集</button></td>
                        <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo htmlspecialchars($exam['id'], ENT_QUOTES, 'UTF-8'); ?>">削除</button></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <!-- モーダル -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="edit_done.php" id="edit_form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">編集</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">テスト名</label>
                            <input type="text" class="form-control" id="edit-test_name" name="test_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">氏名</label>
                            <input type="text" class="form-control" id="edit-student_name" name="student_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">英語</label>
                            <input type="number" class="form-control" id="edit-english" name="english">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">数学</label>
                            <input type="number" class="form-control" id="edit-math" name="math">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">理科</label>
                            <input type="number" class="form-control" id="edit-science" name="science">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">社会</label>
                            <input type="number" class="form-control" id="edit-social_studies" name="social_studies">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">国語</label>
                            <input type="number" class="form-control" id="edit-japanese" name="japanese">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">合計</label>
                            <input type="number" class="form-control" id="edit-total" name="total" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="delete_done.php" id="delete-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">削除確認</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="delete-id">
                        <p>この成績を削除してもよろしいですか？</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-danger">削除</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        document.getElementById('edit-id').value = button.getAttribute('data-id');
        document.getElementById('edit-test_name').value = button.getAttribute('data-test_name');
        document.getElementById('edit-student_name').value = button.getAttribute('data-student_name');
        document.getElementById('edit-english').value = button.getAttribute('data-english');
        document.getElementById('edit-math').value = button.getAttribute('data-math');
        document.getElementById('edit-science').value = button.getAttribute('data-science');
        document.getElementById('edit-social_studies').value = button.getAttribute('data-social_studies');
        document.getElementById('edit-japanese').value = button.getAttribute('data-japanese');
        document.getElementById('edit-total').value = button.getAttribute('data-total');
    });


    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        document.getElementById('delete-id').value = button.getAttribute('data-id');
    });


    const editEnglish = document.getElementById('edit-english');
    const editMath = document.getElementById('edit-math');
    const editScience = document.getElementById('edit-science');
    const editSocialStudies = document.getElementById('edit-social_studies');
    const editJapanese = document.getElementById('edit-japanese');
    const editTotal = document.getElementById('edit-total');

    const calculateEditTotal = () => {
        const english = parseInt(editEnglish.value) || 0;
        const math = parseInt(editMath.value) || 0;
        const science = parseInt(editScience.value) || 0;
        const socialStudies = parseInt(editSocialStudies.value) || 0;
        const japanese = parseInt(editJapanese.value) || 0;

        editTotal.value = english + math + science + socialStudies + japanese;
    };

    editEnglish.addEventListener('input', calculateEditTotal);
    editMath.addEventListener('input', calculateEditTotal);
    editScience.addEventListener('input', calculateEditTotal);
    editSocialStudies.addEventListener('input', calculateEditTotal);
    editJapanese.addEventListener('input', calculateEditTotal);


    const editForm = document.getElementById('edit-form');
    editForm.addEventListener('submit', (event) => {
        event.preventDefault(); 

        const formData = new FormData(editForm);
        const url = editForm.getAttribute('action');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            } else {
                alert('エラーが発生しました: ' + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            alert('リクエストが失敗しました');
        };

        xhr.send(formData);
    });

    
    const deleteForm = document.getElementById('delete-form');
    deleteForm.addEventListener('submit', (event) => {
        event.preventDefault(); 
        

        const formData = new FormData(deleteForm);
        const url = deleteForm.getAttribute('action');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
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


</script>


</body>

</html>
