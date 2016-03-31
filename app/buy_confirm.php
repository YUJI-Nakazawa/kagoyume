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

$cart = $_SESSION['cart'];
$usermail = $_SESSION['user'][0]['mail'];

?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <title>かごゆめ TOP</title>
        <link rel="stylesheet" type="text/css" href="../css/prototype.css"/>
    </head>
    <body>
        <?php 
        $mode = isset($_POST['mode']) ? $_POST['mode'] : "";
        if($mode != "BUY_CONFIRM"){
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
            $sum_price = 0;
            foreach($cart[$usermail] as $item_code => $item){
                foreach ($item as $key => $value) { ?>
                    <div><?php 
                        switch($key){
                            case 'item_image_s': ?>
                                <a href="<?php echo ITME; ?>?item_code=<?php echo $item['item_code']; ?>"><img src="<?php echo $item['item_image_s']; ?>" /></a>
                                <?php break;
                            case 'item_name': ?>
                                <h4><a href="<?php echo ITEM; ?>?item_code=<?php echo $item['item_code']; ?>"><?php echo $item['item_name']; ?></a></h4>
                                <?php break;
                            case 'item_price': ?>
                                <h5><?php echo $item['item_price']; ?>円</h5>
                                <?php 
                                $sum_price = $sum_price + $item['item_price']*$item['item_num'];
                                break;
                            case 'item_num': ?>
                                <h5>数量:<?php echo $item['item_num']; ?></h5>
                                <?php break;
                            } ?>
                    </div>
               <?php }
           }?>
        <h4><?php echo '商品小計:¥'.$sum_price; ?></h4>
        <form action="<?php echo BUY_COMPLETE; ?>" method="POST">
            発送方法:
            通常発送<input type="radio" name="sending_type" value="1" checked>
            お急ぎ便<input type="radio" name="sending_type" value="2">
            日時指定<input type="radio" name="sending_type" value="3"><br><br>
            <input type="submit" value="購入確定">
            <input type="hidden" name="sum_price" value="<?php echo $sum_price; ?>">
            <input type="hidden" name="mode" value="BUY_COMPLETE">
        </form>
        
        <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
        <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn2_105_17.gif" width="105" height="17" title="Webサービス by Yahoo! JAPAN" alt="Webサービス by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px"></a>
        <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    <?php } ?>
    </body>
</html>