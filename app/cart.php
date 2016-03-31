<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
//Yahpp API
require_once '../common/common.php';//共通ファイル読み込み(使用する前に、appidを指定してください。)
// log.txtへ書き込み
log_write();
//ログインしていない場合はログイン画面へ遷移
if( empty($_SESSION['user']) ){?>
        <meta http-equiv="refresh" content="0;URL='<?php echo LOGIN ?>' ">
<?php }
//商品検索API
$hits_search = array();
$search_result = item_search_YAHOOAPI('query', 'sort', $sortOrder, $appid, "category_id", $categories);
$hits_search = $search_result[0];
$query = $search_result[1];
$sort = $search_result[2];
$category_id = $search_result[3];

$cart = $_SESSION['cart'];
var_dump($cart);
$usermail = $_SESSION['user'][0]['mail'];
if( !empty($_POST['change_num']) ){
    $cart[$usermail][ $_POST['change_num'] ]['item_num'] = $_POST['item_num'];
    $_SESSION['cart'] = $cart;
}
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <title>かごゆめ TOP</title>
        <link rel="stylesheet" type="text/css" href="../css/prototype.css"/>
    </head>
    <body>
        <!-- 全ページ共通ページヘッダ -->
        <?php page_head(); ?>
        <!-- 全ページ共通検索フォーム -->
        <?php search_form($sortOrder, $sort, $categories, $category_id, $query); ?>
        <!-- カートの中身を表示する処理 -->
        <?php
        if( empty($cart[$usermail]) ){?>
            <h2>カートの中身がありません。</h2>
    <?php }
        else{
            $sum_price = 0;
            foreach($cart[$usermail] as $item_code => $item){
                foreach ($item as $key => $value) { ?>
                        <?php 
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
                                <form action="<?php echo CART; ?>" method="POST">
                                    <input type="text" name="item_num" value="<?php echo $item['item_num']; ?>">
                                    <input type="hidden" name="change_num" value="<?php echo $item_code; ?>"> 
                                    <input type="submit" value="更新">
                                </form>
                                <?php 
                                break;
                        }

                }
        }
        echo '商品小計:¥'.$sum_price;
            ?>
            <form action="<?php echo BUY_CONFIRM; ?>" method='POST'>
                <input type="submit" value="購入手続きへ進む">
                <input type="hidden" name="mode" value="BUY_CONFIRM">
            </form>
  <?php } ?>

        <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
        <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn2_105_17.gif" width="105" height="17" title="Webサービス by Yahoo! JAPAN" alt="Webサービス by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px"></a>
        <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    </body>
</html>