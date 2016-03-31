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
      <title>更新結果画面</title>
</head>
  <body>
    <?php
    //入力画面から「確認画面へ」ボタンを押した場合のみ処理を行う
    $mode = isset($_POST['mode']) ? $_POST['mode'] : "";
    if($mode != "MY_DELETE_RESULT"){
        echo 'アクセスルートが不正です。もう一度トップページからやり直してください<br>';
    }
    else{
        $userID = $_SESSION['user'][0]['userID'];
        $delete_user_result = delete_user($userID);
        
        if( !isset($delete_user_result) ){
            echo 'ユーザーを削除しました。ご利用ありがとうございました。';
            unset($_SESSION['user']);
        }
        else{
            echo 'データの削除に失敗しました。次記のエラーにより処理を中断します:'.$delete_user_result;
        }
    }
    echo return_top();
    ?>
  </body>
</html>