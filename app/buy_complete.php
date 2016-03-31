<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
//Yahpp API
require_once '../common/common.php';//共通ファイル読み込み(使用する前に、appidを指定してください。)
// log.txtへ書き込み
log_write();
//商品検索API
$hits_search = array();
$search_result = item_search_YAHOOAPI('query', 'sort', $sortOrder, $appid, "category_id", $categories);
$hits_search = $search_result[0];
$query = $search_result[1];
$sort = $search_result[2];
$category_id = $search_result[3];

// 購入したカートの情報
$usermail = $_SESSION['user'][0]['mail'];
$items = $_SESSION['cart'][$usermail];
// テーブルbuy_tに挿入するユーザーの購入情報
$userID = $_SESSION['user'][0]['userID'];
$sending_type = $_POST['sending_type'];
$sum_price = $_POST['sum_price'];
$buy_info = array('userID' => $userID, 'type' => $sending_type, 'total' => $sum_price);
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <title>かごゆめ</title>
        <link rel="stylesheet" type="text/css" href="../css/prototype.css"/>
    </head>
    <body>
        <?php 
        $mode = isset($_POST['mode']) ? $_POST['mode'] : "";
        if($mode != "BUY_COMPLETE"){
            echo 'アクセスルートが不正です。もう一度トップページからやり直してください<br>';
            return_top();
        }
        else{?>
            <!-- 全ページ共通ページヘッダ -->
            <?php page_head(); ?>
            <!-- 全ページ共通検索フォーム -->
            <?php search_form($sortOrder, $sort, $categories, $category_id, $query); ?>
            <!-- カートの中身を表示する処理 -->
            <?php
            $insert_buy_result = insert_buy($buy_info);
            if( !isset($insert_buy_result) ){ ?>
                <!-- 購入完了 -->
                <h2>ご購入ありがとうございました。</h2>
                <?php 
                // カートの中身を空にする
                $_SESSION['cart'][$usermail] = array();
                // 購入履歴をセッションに保存
                $history = !empty($_SESSION['history']) ? $_SESSION['history'] : array();
                // 配列$cartにメールアドレスをキー名とした配列が存在していなかった場合、その配列を追加
                if( !array_key_exists($usermail, $history) ){
                    $items = array();
                    $history = array_merge( $history, array($usermail => $items) );
                }
                $history[$usermail] = array_merge( $history[$usermail], $items );
                $_SESSION['history'] = $history;
                // ユーザーの購入金額の履歴を取得し、総購入金額を算出
                $select_total_result = select_total();
                    if( is_array($select_total_result) && !empty($select_total_result) ){
                        $total_price = 0;
                        foreach($select_total_result as $value){
                            $sum_price = $value['total'];
                            // var_dump($sum_price);
                            $total_price = $total_price + $sum_price;
                        }
                        // ユーザーの総購入金額を更新
                        $update_total_result = update_total($total_price);
                        if( isset($update_total) ){
                            echo 'データの更新に失敗しました。次記のエラーにより処理を中断します:'.$update_total_result;
                        }
                    }
                    else{
                        echo '該当のユーザーは存在しません。';
                    }
                }
                else{
                    echo 'データの挿入に失敗しました。次記のエラーにより処理を中断します:'.$insert_buy_result;
                }
        ?>
        <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
        <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn2_105_17.gif" width="105" height="17" title="Webサービス by Yahoo! JAPAN" alt="Webサービス by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px"></a>
        <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    <?php } ?>
    </body>
</html>