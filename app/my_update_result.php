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
    if($mode != "MY_UPDATE_RESULT"){
        echo 'アクセスルートが不正です。もう一度トップページからやり直してください<br>';
    }
    else{
        $userID = $_SESSION['user'][0]['userID'];
        
        // ポストの存在チェックとセッションに値を格納しつつ、連想配列にポストされた値を格納
        $confirm_values_upd = array(
                                'name_upd' => bind_p2s('name_upd'),
                                'password_upd' => bind_p2s('password_upd'),
                                'password_conf_upd' => bind_p2s('password_conf_upd'),
                                'mail_upd' => bind_p2s('mail_upd'),
                                'mail_conf_upd' => bind_p2s('mail_conf_upd'),
                                'address_upd' => bind_p2s('address_upd'));
                                
        //1つでも未入力項目があったら更新を実行しない
        if(!in_array(null, $confirm_values_upd, true)){
            if($confirm_values_upd['mail_upd'] == $confirm_values_upd['mail_conf_upd'] 
               && $confirm_values_upd['password_upd'] == $confirm_values_upd['password_conf_upd']){
               $input_check = true;
               $update_user_result = update_user($userID, $confirm_values_upd);
               
               if( !isset($update_user_result) ){?>
                    <h1>ユーザー情報更新完了</h1><br>
                    名前:<?php echo $confirm_values_upd['name_upd'];?><br>
                    メールアドレス:<?php echo $confirm_values_upd['mail_upd'];?><br>
                    住所:<?php echo $confirm_values_upd['address_upd'];?><br>
                    以上の内容で更新しました。
                    <?php
                    //情報を更新した段階で再ログイン
                    $login_result = login($confirm_values_upd['name_upd'], $confirm_values_upd['password_upd']);
                    var_dump($login_result);
                    if( !empty($login_result) ){
                        $_SESSION['user'] = $login_result; ?>
              <?php }
                    // セッションのクリア
                    unset($_SESSION['name_upd']);
                    unset($_SESSION['password_upd']);
                    unset($_SESSION['password_conf_upd']);
                    unset($_SESSION['mail_upd']);
                    unset($_SESSION['mail_conf_upd']);
                    unset($_SESSION['address_upd']);
               }
               else{
                   echo 'データの更新に失敗しました。次記のエラーにより処理を中断します:'.$update_user_result;
               }
            }
        }
        else{
            $input_check = false;
            ?>
            <h1>入力項目が不完全です</h1><br>
            再度入力を行ってください<br>
            <h3>不完全な項目</h3>
            <?php
            //連想配列内の未入力項目を検出して表示
            foreach ($confirm_values_upd as $key => $value){
                if($value == null){
                    if($key == 'name_upd'){
                        echo '名前';
                    }
                    if($key == 'mail_upd'){
                        echo 'メールアドレス';
                    }
                    if($key == 'mail_conf_upd'){
                        echo 'メールアドレス(確認用)';
                    }
                    if($key == 'password_upd'){
                        echo 'パスワード';
                    }
                    if($key == 'password_conf_upd'){
                        echo 'パスワード(確認用)';
                    }
                    if($key == 'address_upd'){
                        echo '住所';
                    }
                    echo 'が未入力です<br>';
                }
            }
        }
        //メールアドレスの入力が一回目と二回目で異なる場合以下の処理を行う。
        if($confirm_values_upd['mail_upd'] != $confirm_values_upd['mail_conf_upd']){
            $input_check = false;
            echo 'メールアドレスの入力に誤りがあります。';
        }
        //メールアドレスの入力が一回目と二回目で異なる場合以下の処理を行う。
        if($confirm_values_upd['password_upd'] != $confirm_values_upd['password_conf_upd']){
            $input_check = false;
            echo 'パスワードの入力に誤りがあります。';
        }
        if($input_check != true){
            ?>
                <form action="<?php echo MY_UPDATE; ?>" method="POST">
                    <input type="hidden" name="mode" value="REINPUT" >
                    <input type="submit" name="no" value="更新画面へ戻る">
                </form>
                <br>
  <?php }
    }

    echo return_top();
    ?>
  </body>
</html>