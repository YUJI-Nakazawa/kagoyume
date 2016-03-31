<?php

//DBへの接続を行う。成功ならPDOオブジェクトを、失敗なら中断、メッセージの表示を行う
function connect2kagoyume_db(){
    try{
        $pdo = new PDO('mysql:host=localhost; dbname=kagoyume_db; charset=utf8','root','root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $connect_e) {  // PDOExptionのインスタンス名を変更 $e → $connect_e
        die('DB接続に失敗しました。次記のエラーにより処理を中断します:'.$connect_e->getMessage());
    }
}

// レコードの挿入を行う。失敗した場合はエラー文を返却する
function insert_user($name, $password, $mail, $address){
    //d b接続を確立
    $insert_user_db = connect2kagoyume_db();
    //DBに全項目のある1レコードを登録するSQL
    $insert_user_sql = "INSERT INTO user_t(name,password,mail,address,total,newDate)"
            . "VALUES(:name,:password,:mail,:address,:total,:newDate)";
    // 現在時をdatetime型で取得
    $datetime =new DateTime();
    $date = $datetime->format('Y-m-d H:i:s');
    // クエリとして用意
    $insert_user_query = $insert_user_db->prepare($insert_user_sql);
    // SQL文にセッションから受け取った値＆現在時をバインド
    $insert_user_query->bindValue(':name',$name);
    $insert_user_query->bindValue(':password',$password);
    $insert_user_query->bindValue(':mail',$mail);
    $insert_user_query->bindValue(':address',$address);
    $insert_user_query->bindValue(':total',0);
    $insert_user_query->bindValue(':newDate',$date);
    // SQLを実行
    try{
        $insert_user_query->execute();
    } catch (PDOException $insert_user_e) { // PDOExptionのインスタンス名を変更 $e → $insert_e
        // 接続オブジェクトを初期化することでDB接続を切断
        $insert_user_db=null;
        return $insert_user_e->getMessage();
    }

    $insert_user_db=null;
    return null;
}

function login($mail = null, $password = null){
    // db接続を確立
    $select_user_db = connect2kagoyume_db();
    // use_tテーブルから引数として受け取ったユーザーネームとパスワードに合致するレコードの情報を選択する。
    $select_user_sql = "SELECT * FROM user_t WHERE mail = :mail AND password = :password";
    //クエリとして用意
    $select_user_query = $select_user_db->prepare($select_user_sql);
    // 変数のバインド
    $select_user_query -> bindValue(':mail', $mail);
    $select_user_query -> bindValue(':password', $password);
    // SQLを実行
    try{
        $select_user_query->execute();
    } catch (PDOException $select_user_e) {
        $select_user_db = null;
        return $select_user_e->getMessage();
    }
    // 該当のレコードを連想配列として返却
    $insert_user_db = null;
    return $select_user_query->fetchAll(PDO::FETCH_ASSOC);
}

function update_user($userID, $user){
    // db接続を確立
    $update_user_db = connect2kagoyume_db();
    // 指定したuserIDのあるレコードの情報を上書きするSQL
    $update_user_sql = "UPDATE user_t SET name=:name, password=:password, mail=:mail,".
                  " address=:address, newDate=:newDate WHERE userID=:userID";
    // 現在時をdatetime型で取得
    $datetime =new DateTime();
    $date = $datetime->format('Y-m-d H:i:s');
    // クエリとして用意
    $update_user_query = $update_user_db->prepare($update_user_sql);
    
    $update_user_query->bindValue(':userID',$userID);
    $update_user_query->bindValue(':name',$user['name_upd']);
    $update_user_query->bindValue(':password',$user['password_upd']);
    $update_user_query->bindValue(':mail',$user['mail_upd']);
    $update_user_query->bindValue(':address',$user['address_upd']);
    $update_user_query->bindValue(':newDate',$date);
        
    // SQLを実行
    try{
        $update_user_query->execute();
    } catch (PDOException $update_user_e) {
        $update_user_query=null;
        return $update_user_e->getMessage();
    }
    return null;
}

function delete_user($userID){
    // db接続を確立
    $delete_user_db = connect2kagoyume_db();
    
    // 指定したuserIDの1レコードを削除するSQL
    $delete_user_sql = "DELETE FROM user_t WHERE userID=:userID"; //DELEtE → DELETE
    //クエリとして用意
    $delete_user_query = $delete_user_db->prepare($delete_user_sql);
    $delete_user_query->bindValue(':userID', $userID);
    
    // SQLを実行
    try{
        $delete_user_query->execute();
    } catch (PDOException $delete_user_e) { // PDOExptionのインスタンス名を変更 $e → $delete_e
        $delete_user_query=null;
        return $delete_user_e->getMessage();
    }
    return null;
}

function select_total(){
    $userID = $_SESSION['user'][0]['userID'];
    // db接続を確立
    $select_total_db = connect2kagoyume_db();
    // ユーザーの購入総金額を取得するSQL
    $select_total_sql = "SELECT total FROM buy_t WHERE userID=:userID";
    // クエリとして用意
    $select_total_query = $select_total_db->prepare($select_total_sql);
    // 変数のバインド
    $select_total_query->bindValue(':userID', $userID);
    // SQLを実行
    try{
        $select_total_query->execute();
    } catch (PDOException $select_total_e) {
        $select_total_db = null;
        return $select_total_e->getMessage();
    }
    // 該当のレコードを連想配列として返却
    $select_total_db = null;
    return $select_total_query->fetchAll(PDO::FETCH_ASSOC);
}

function update_total($total){
    $userID = $_SESSION['user'][0]['userID'];
    // db接続を確立
    $update_total_db = connect2kagoyume_db();
    // 指定したuserIDのtotalを更新するSQL
    $update_total_sql = "UPDATE user_t SET total=:total WHERE userID=:userID";
    // クエリとして用意
    $update_total_query = $update_total_db->prepare($update_total_sql);
    $update_total_query->bindValue(':userID', $userID);
    $update_total_query->bindValue(':total', $total);
    // SQLを実行
    try{
        $update_total_query->execute();
    } catch (PDOException $update_total_e) { // PDOExptionのインスタンス名を変更 $e → $delete_e
        $update_total_query=null;
        return $update_total_e->getMessage();
    }
    return null;
}

function insert_buy($buy_info){
    // db接続を確立
    $insert_buy_db = connect2kagoyume_db();
    // 購入時の情報を挿入するSQL
    $insert_buy_sql = "INSERT INTO buy_t(userID, total, type, buyDate)"
                      ." VALUES(:userID, :total, :type, :buyDate)";
    // 現在時をdatetime型で取得
    $datetime =new DateTime();
    $date = $datetime->format('Y-m-d H:i:s');
    // クエリとして用意
    $insert_buy_query = $insert_buy_db->prepare($insert_buy_sql);
    // 変数のバインド
    $insert_buy_query->bindValue(':userID', $buy_info['userID']);
    $insert_buy_query->bindValue(':total', $buy_info['total']);
    $insert_buy_query->bindValue(':type', $buy_info['type']);
    $insert_buy_query->bindValue(':buyDate', $date);
    // SQLを実行
    try{
        $insert_buy_query->execute();
    } catch (PDOException $insert_buy_e) {
        // 接続オブジェクトを初期化することでDB接続を切断
        $insert_buy_db=null;
        return $insert_buy_e->getMessage();
    }
    $insert_buy_db=null;
    return null;
}

function mail_check(){
    // db接続を確立
    $mail_check_db = connect2kagoyume_db();
    // user_tのmailカラムの全要素をセレクトするSQL
    $mail_check_sql = "SELECT mail FROM user_t";
    // SQLを実行
    try{
        $mail_check_query = $mail_check_db->query($mail_check_sql);
    } catch (PDOException $mail_check_e) {
        // 接続オブジェクトを初期化することでDB接続を切断
        $mail_check_db = null;
        return $mail_check_e->getMessage();
    }
    $mail_check_db = null;
    return $mail_check_query->fetchAll(PDO::FETCH_ASSOC);
}
