<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
// log.txtへ書き込み
log_write();
//ログインしていない場合はログイン画面へ遷移
if( empty($_SESSION['user']) ){?>
        <meta http-equiv="refresh" content="0;URL='<?php echo LOGIN ?>' ">
<?php }

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
      <title>削除確認画面</title>
</head>
  <body>
    <h1>退会確認</h1>
    以下のユーザーをマジで削除します。よろしいですか？<br>
    名前:<?php echo $user[0]['name'];?><br>
    メールアドレス:<?php echo $user[0]['mail'];?><br>
    住所:<?php echo $user[0]['address'];?><br>
    登録日時:<?php echo date('Y年n月j日　G時i分s秒', strtotime($user[0]['newDate'])); ?><br>

    <form action="<?php echo MY_DELETE_RESULT; ?>" method="POST">
        <input type="hidden" name="mode" value="MY_DELETE_RESULT">
        <input type="submit" name="YES" value="はい"style="width:100px">
    </form>
    <form action="<?php echo ROOT_URL; ?>" method="POST">
        <input type="submit" name="NO" value="いいえ" style="width:100px">
    </form>
    <br>
</body>
</html>