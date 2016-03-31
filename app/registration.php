<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
// log.txtへ書き込み
log_write();
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
      <title>新規登録画面</title>
</head>
<body>
    <form action="<?php echo REGISTRATION_CONFIRM ?>" method="POST">
        名前:
        <input type="text" name="name" value="<?php echo form_value('name'); ?>">
        <br><br>
        
        メールアドレス:
        <input type="text" name="mail" value="<?php echo form_value('mail'); ?>"><br>
        メールアドレス(確認用):
        <input type="text" name="mail_conf" value="<?php echo form_value('mail_conf'); ?>">
        <br>
        <br>
        
        パスワード:
        <input type="password" name="password" value=""><br>
        パスワード(確認用):
        <input type="password" name="password_conf" value="">
        <br>
        <br>
        
        住所:
        <input type="text" name="address" value="<?php echo form_value('address'); ?>">
        <br>
        <br>

        <!-- 登録画面から遷移したことを示すフラグを渡す -->
        <input type="hidden" name="mode" value="REGISTRATION_CONFIRM">
        <input type="submit" name="btnSubmit" value="確認画面へ">
    </form>
</body>
</html>
