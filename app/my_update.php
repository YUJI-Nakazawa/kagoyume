<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
// log.txtへ書き込み
log_write();
//現在のユーザー情報を変数に格納する。
$name = $_SESSION['user'][0]['name'];
$password = $_SESSION['user'][0]['password'];
$mail = $_SESSION['user'][0]['mail'];
$address = $_SESSION['user'][0]['address'];

//フォーム再入力時の初期値
$name_upd = form_value('name_upd');
$password_upd = form_value('password_upd');
$mail_upd = form_value('mail_upd');
$address_upd = form_value('address_upd');

//指定したセッションの中身が存在していればセッションの値を、なければ更新前の値を変数に格納する。
$name_upd = isset($name_upd) ? $name_upd : $name;
$password_upd = isset($password_upd)? $password_upd : $password;
$password_conf_upd = isset($password_conf_upd)? $password_conf_upd : $password;
$mail_upd = isset($mail_upd) ? $mail_upd : $mail;
$mail_conf_upd = isset($mail_conf_upd) ? $mail_conf_upd : $mail;
$address_upd = isset($address_upd) ? $address_upd : $address;

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
      <title>ユーザー情報更新画面</title>
</head>
<body>
    <form action="<?php echo MY_UPDATE_RESULT ?>" method="POST">
        名前:
        <input type="text" name="name_upd" value="<?php echo $name_upd; ?>">
        <br><br>
        
        メールアドレス:
        <input type="text" name="mail_upd" value="<?php echo $mail_upd; ?>"><br>
        メールアドレス(確認用):
        <input type="text" name="mail_conf_upd" value="<?php echo $mail_upd; ?>">
        <br>
        <br>
        
        パスワード:
        <input type="password" name="password_upd" value="<?php echo $password_upd; ?>"><br>
        パスワード(確認用):
        <input type="password" name="password_conf_upd" value="<?php echo $password_conf_upd; ?>">
        <br>
        <br>
        
        住所:
        <input type="text" name="address_upd" value="<?php echo $address_upd; ?>">
        <br>
        <br>

        <!-- 登録画面から遷移したことを示すフラグを渡す -->
        <input type="hidden" name="mode" value="MY_UPDATE_RESULT">
        <input type="submit" name="btnSubmit" value="更新する"><br>
        
        <a href="<?php echo MY_DATA; ?>">戻る</a>
    </form>
</body>
</html>