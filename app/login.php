<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
//Yahpp API
require_once '../common/common.php';//共通ファイル読み込み(使用する前に、appidを指定してください。)
// log.txtへ書き込み
log_write();
$mode = !empty($_POST['mode']) ? $_POST['mode'] : "";
$lastpage = !empty($_POST['lastpage']) ? $_POST['lastpage'] : ROOT_URL;

return_top();
if( !empty($_POST['mail']) && !empty($_POST['password']) ){
    $login_result = login($_POST['mail'], $_POST['password']);
    // login()の戻り値が配列、かつ中身が空でない場合、ログイン成功とし、戻り値(ユーザーの全情報)を配列とする。
    if( is_array($login_result) && !empty($login_result) ){
        $_SESSION['user'] = $login_result;
        $usermail = $_SESSION['user'][0]['mail'];
        $cart = !empty($_SESSION['cart']) ? $_SESSION['cart'] : array();
        // 配列$cartにメールアドレスをキー名とした配列が存在していなかった場合、その配列を追加
        if( !array_key_exists($usermail, $cart) ){
            $items = array();
            $cart = array_merge( $cart, array($usermail => $items) );
            $_SESSION['cart'] = $cart;
        }
        if($lastpage != 'http://localhost/kagoyume/app/login.php' ){ ?>
             <meta http-equiv="refresh" content="0;URL='<?php echo $lastpage; ?>' ">
  <?php }
         else{ ?>
             
             <meta http-equiv="refresh" content="0;URL='<?php echo ROOT_URL; ?>' ">
   <?php }
    }
    else{
        echo 'メールアドレスもしくはパスワードが間違っています。再度入力してください。';
    }
}
elseif($mode == 'REINPUT'){
    echo 'メールアドレスとパスワードを入力してください。';
}
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <title>ログイン</title>
        <link rel="stylesheet" type="text/css" href="../css/prototype.css"/>
    </head>
    <body>
        
        <form action="<?php echo LOGIN; ?>" method="POST">
            メールアドレス:<input type="text" name="mail" value="">
            パスワード:<input type="password" name="password" value="">
            <input type="submit" value="ログイン">
            <input type="hidden" name="lastpage" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
            <input type="hidden" name="mode" value="REINPUT">
        </form>
        <a href="<?php echo REGISTRATION ?>">新規ユーザー登録はこちら</a>
    </body>
</html>