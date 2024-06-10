<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタッフ登録</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2 class="mt-5 mb-4">スタッフ登録</h2>
        <form action="register_done.php" method="post">
            <div class="mb-3">
                <label for="mail" class="form-label">メールアドレス</label>
                <input type="text" class="form-control" id="mail" name="mail">
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">パスワード</label>
                <input type="password" class="form-control" id="pass" name="pass">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">氏名</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">役職</label>
                <select class="form-select" id="position" name="position">
                    <option value="general">一般</option>
                    <option value="chief">主任</option>
                    <option value="principal">校長</option>
                </select>
            </div>
            <div class="mb-3" id="grade-field" style="display: block;">
                <label for="grade" class="form-label">学年</label>
                <select class="form-select" id="grade" name="grade">
                    <option value="">学年を選択</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="mb-3" id="class-field" style="display: block;">
                <label for="class" class="form-label">クラス</label>
                <select class="form-select" id="class" name="class">
                    <option value="">クラスを選択</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">登録</button>
        </form>
        <div class="mt-3">
            <a href="login.php">登録済みの方はこちら</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
        document.getElementById('position').addEventListener('change', function() {
            var position = this.value;
            var gradeField = document.getElementById('grade-field');
            var classField = document.getElementById('class-field');
            
            if (position === 'general') {
                gradeField.style.display = 'block';
                classField.style.display = 'block';
            } else if (position === 'chief') {
                gradeField.style.display = 'block';
                classField.style.display = 'none';
            } else {
                gradeField.style.display = 'none';
                classField.style.display = 'none';
            }
        });
    </script>
</body>
</html>
