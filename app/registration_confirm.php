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
      <title>登録確認画面</title>
</head>
  <body>
    <?php
    //入力画面から「確認画面へ」ボタンを押した場合のみ処理を行う
    $mode = isset($_POST['mode']) ? $_POST['mode'] : "";
    if($mode != "REGISTRATION_CONFIRM"){
        echo 'アクセスルートが不正です。もう一度トップページからやり直してください<br>';
    }else{
        //ポストの存在チェックとセッションに値を格納しつつ、連想配列にポストされた値を格納
        $confirm_values = array(
                                'name' => bind_p2s('name'),
                                'password' => bind_p2s('password'),
                                'password_conf' => bind_p2s('password_conf'),
                                'mail' => bind_p2s('mail'),
                                'mail_conf' => bind_p2s('mail_conf'),
                                'address' => bind_p2s('address'));
        //1つでも未入力項目があったら表示しない
        if(!in_array(null, $confirm_values, true)){
            if($confirm_values['mail'] == $confirm_values['mail_conf'] 
               && $confirm_values['password'] == $confirm_values['password_conf']){
                   $input_check = true;
                   ?>
                   <h1>登録確認画面</h1><br>
                   名前:<?php echo $confirm_values['name'];?><br>
                   メールアドレス:<?php echo $confirm_values['mail'];?><br>
                   <!-- パスワード:セキュリティのため表示されません<br> -->
                   住所:<?php echo $confirm_values['address'];?><br>

                   上記の内容で登録します。よろしいですか？

                   <form action="<?php echo REGISTRATION_COMPLETE ?>" method="POST">
                       <!-- 登録確認画面から遷移したことを示すフラグを渡す -->
                       <input type="hidden" name="mode" value="REGISTRATION_COMPLETE" >
                       <input type="submit" name="yes" value="はい">
                   </form>
                   <form action="<?php echo REGISTRATION ?>" method="POST">
                       <!-- 登録確認画面から遷移したことを示すフラグを渡す -->
                       <input type="hidden" name="mode" value="REINPUT" >
                       <input type="submit" name="no" value="いいえ">
                   </form>
                   <?php
            }
        }
        //未入力項目があるときに以下の処理をする
        else {
            $input_check = false;
            ?>
            <h1>入力項目が不完全です</h1><br>
            再度入力を行ってください<br>
            <h3>不完全な項目</h3>
            <?php
            //連想配列内の未入力項目を検出して表示
            foreach ($confirm_values as $key => $value){
                if($value == null){
                    if($key == 'name'){
                        echo '名前';
                    }
                    if($key == 'mail'){
                        echo 'メールアドレス';
                    }
                    if($key == 'mail_conf'){
                        echo 'メールアドレス(確認用)';
                    }
                    if($key == 'password'){
                        echo 'パスワード';
                    }
                    if($key == 'password_conf'){
                        echo 'パスワード(確認用)';
                    }
                    if($key == 'address'){
                        echo '住所';
                    }
                    echo 'が未入力です<br>';
                }
            }
        }
        //メールアドレスが確認用と異なる場合NGとする。
        if($confirm_values['mail'] != $confirm_values['mail_conf']){
            $input_check = false;
            echo 'メールアドレスの入力に誤りがあります。';
        }
        // 既に同じメールアドレスが登録されている場合はNGとする。
        else{
            foreach(mail_check() as $mails ){
                if( in_array( $confirm_values['mail'], $mails ) ){
                    $input_check = false;
                    echo 'そのメールアドレスは既に使用されております。';
                    break;
                }
            }
        }
        //パスワードが確認用と異なる場合NGとする。
        if($confirm_values['password'] != $confirm_values['password_conf']){
            $input_check = false;
            echo 'パスワードの入力に誤りがあります。';
        }
        
        if($input_check != true){
        ?>
            <form action="<?php echo REGISTRATION; ?>" method="POST">
                <input type="hidden" name="mode" value="REINPUT" >
                <input type="submit" name="no" value="登録画面へ戻る">
            </form>
            <br>
    <?php
        }
    }
    echo return_top();
    ?>
</body>
</html>