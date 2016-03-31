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
$search_result = item_search_YAHOOAPI($sortOrder, $appid, $categories);
$hits_search = !empty($search_result[0]) ? $search_result[0] : $_SESSION['hits_search'];
$query = $search_result[1];
$sort = $search_result[2];
$category_id = $search_result[3];
//商品コード検索API
$item_code = $_GET['item_code'];
$image_size = 300;
$item_num = !empty($_SESSION['item_num']) ? $_SESSION['item_num'] : 1;
if( !empty($_SESSION['hits_datail']) ){
    $hits_detail = $_SESSION['hits_detail'];
}
else{
    $hits_detail = item_detail_YAHOOAPI($appid, $item_code, $image_size);
}
$_SESSION['hits_detail'] = $hits_detail;
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
        <a href="<?php echo SEARCH; ?>">検索結果に戻る</a><br><br>
            <img src="<?php echo $hits_detail['item_image']; ?>">
            <h2><?php echo $hits_detail['item_name']; ?></h2>
            <h3><?php echo $hits_detail['item_price']; ?>円</h3>
            <form action="<?php echo ADD; ?>" method="POST">
                個数:<input type="text" name="item_num" value="<?php echo $item_num ?>">
                <input type="submit" value="カートに追加する">
            </form>
        <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
        <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn2_105_17.gif" width="105" height="17" title="Webサービス by Yahoo! JAPAN" alt="Webサービス by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px"></a>
        <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    </body>
</html>