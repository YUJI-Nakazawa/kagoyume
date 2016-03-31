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
$search_result = item_search_YAHOOAPI($sortOrder, $appid, $categories);
$hits_search = !empty($search_result[0]) ? $search_result[0] : $_SESSION['hits_search'];
$query = $search_result[1];
$sort = $search_result[2];
$category_id = $search_result[3];
//カートに商品(商品コード)を追加
$hits_detail = $_SESSION['hits_detail'];
$item_num = !empty($_POST['item_num']) ? $_POST['item_num'] : 1;
$item = array_merge($hits_detail, array('item_num' => $item_num) );
// カート配列に商品情報の配列を格納
$usermail = $_SESSION['user'][0]['mail'];
$cart = $_SESSION['cart'];
$cart[$usermail] = array_merge( $cart[$usermail], array($item['item_code'] => $item) );
$_SESSION['cart'] = $cart;
$sum_price = $item['item_price']*$item['item_num'];
// カートに商品情報を格納したら、セッションを削除
unset($_SESSION['hits_datail']);

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
        <!-- 商品詳細を表示する処理 -->
            <h1><?php echo $item['item_num']; ?>点がカートに追加されました。</h1>
            <form action="<?php echo CART; ?>" method="GET">
                <input type="submit" value="カートに進む">
            </form>
            <img src="<?php echo $item['item_image_s']; ?>">
            <h2><?php echo $item['item_name']; ?></h2>
            <h3><?php echo $item['item_price']; ?>円</h3>
            <h3>個数:<?php echo $item['item_num']; ?> </h3>
            <?php 
            echo '商品小計:¥'.$sum_price;
                ?>
                <form action="<?php echo BUY_CONFIRM; ?>" method='POST'>
                    <input type="submit" value="購入手続きへ進む">
                    <input type="hidden" name="mode" value="BUY_CONFIRM">
                </form>
        <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
        <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn2_105_17.gif" width="105" height="17" title="Webサービス by Yahoo! JAPAN" alt="Webサービス by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px"></a>
        <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    </body>
</html>