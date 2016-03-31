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
      <title>登録結果画面</title>
</head>
    <body>
    <?php
    //確認画面から「はい」ボタンを押したん場合のみ処理を行う。
    $mode = isset($_POST['mode']) ? $_POST['mode'] : "";
    if($mode != "REGISTRATION_COMPLETE"){
        echo 'アクセスルートが不正です。もう一度トップページからやり直してください<br>';
    }else{
        $name = $_SESSION['name'];
        $password = $_SESSION['password'];
        $mail = $_SESSION['mail'];
        $address = $_SESSION['address'];

        //データのDB挿入処理。エラーの場合のみエラー文がセットされる。成功すればnull
        $insert_user_result = insert_user($name, $password, $mail, $address);
        
        //エラーが発生しなければ表示を行う
        if(!isset($insert_user_result)){
        ?>
        <h1>ユーザー登録完了</h1><br>
        名前:<?php echo $name;?><br>
        メールアドレス:<?php echo $mail;?><br>
        住所:<?php echo $address;?><br>
        以上の内容で登録しました。<br>
        <?php
        // セッションのクリア
        unset($_SESSION['name']);
        unset($_SESSION['password']);
        unset($_SESSION['password_conf']);
        unset($_SESSION['mail']);
        unset($_SESSION['mail_conf']);
        unset($_SESSION['address']);
        }
        else{
            echo 'データの挿入に失敗しました。次記のエラーにより処理を中断します:'.$result;
        }
    }
    return_top();
    ?>
    </body>
</html>