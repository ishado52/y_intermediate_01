<?php
session_start();
session_regenerate_id(true);
if(!(isset($_SESSION['login']))):
?>
ログインしてください<br/>
<a href="login.php">ログインページへ</a>
<?php exit()?>
<?php endif; ?>