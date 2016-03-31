<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
// log.txtへ書き込み
log_write();
//ログインしていない場合はログイン画面へ遷移
if(empty($_SESSION['user']) ){?>
        <meta http-equiv="refresh" content="0;URL='<?php echo LOGIN ?>' ">
<?php }

$usermail = $_SESSION['user'][0]['mail'];
$userhistory = $_SESSION['history'][$usermail];
var_dump($userhistory);

?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <title>ユーザー情報画面</title>
        <link rel="stylesheet" type="text/css" href="../css/prototype.css"/>
    </head>
    <body>
        <h1>ユーザー情報</h1>
        名前:<?php echo $_SESSION['user'][0]['name'];?><br>
        メールアドレス:<?php echo $_SESSION['user'][0]['mail'];?><br>
        住所:<?php echo $_SESSION['user'][0]['address'];?><br>
        総購入金額:<?php echo $_SESSION['user'][0]['address']; ?><br>
        登録日時:<?php echo date('Y年n月j日　G時i分s秒', strtotime($_SESSION['user'][0]['newDate'])); ?><br>
        
        <form action="<?php echo MY_UPDATE; ?>" method="POST">
            <input type="hidden" name="mode" value="UPDATE">
            <input type="submit" name="update" value="ユーザー情報を変更する" style="width:100px">
        </form>
        <form action="<?php echo MY_DELETE; ?>" method="POST">
            <input type="hidden" name="mode" value="DELETE">
            <input type="submit" name="delete" value="退会する" style="width:100px">
        </form>
        <br>
    <?php return_top(); ?>
    
    </body>
</html>