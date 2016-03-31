<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
//Yahpp API
require_once '../common/common.php';//共通ファイル読み込み(使用する前に、appidを指定してください。)
// log.txtへ書き込み
log_write();

// 商品検索を実行したタイミングで商品詳細を格納したセッションと商品の個数を格納したセッションを破棄
if( !empty($_SESSION['hits_datail']) ){
    unset($_SESSION['hits_datail']);
    unset($_SESSION['item_num']);
}
//商品検索API
$search_result = item_search_YAHOOAPI($sortOrder, $appid, $categories);
if( empty($_SESSION['hits_detail']) ){
    $_SESSION['hits_detail'] = null;
}
$hits_search = !empty($search_result[0]) ? $search_result[0] : $_SESSION['hits_search'];
$_SESSION['hits_search'] = $hits_search;
$query = $search_result[1];
$sort = $search_result[2];
$category_id = $search_result[3];
// var_dump($_SESSION['hits_search']);
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
        <!-- 検索結果を表示する処理 -->
        <?php 
        if( !empty($hits_search) ){
            foreach ($hits_search as $hit_search) { ?>
                <div class="Item">
                    <h2><a href="<?php echo ITEM; ?>?item_code=<?php echo h($hit_search->Code)?>"><?php echo h($hit_search->Name); ?></a></h2>
                    <p><a href="<?php echo ITME; ?>"><img src="<?php echo h($hit_search->Image->Medium); ?>" /></a><?php echo h($hit_search->Description); ?></p>
                </div>
        <?php }
        } 
        else{
            echo 'キーワードを入力してください。';
        }?>
        <!-- Begin Yahoo! JAPAN Web Services Attribution Snippet -->
        <a href="http://developer.yahoo.co.jp/about">
        <img src="http://i.yimg.jp/images/yjdn/yjdn_attbtn2_105_17.gif" width="105" height="17" title="Webサービス by Yahoo! JAPAN" alt="Webサービス by Yahoo! JAPAN" border="0" style="margin:15px 15px 15px 15px"></a>
        <!-- End Yahoo! JAPAN Web Services Attribution Snippet -->
    </body>
</html>