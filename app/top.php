<?php 
session_start();
require_once '../util/defineUtil.php';
require_once '../util/dbaccessUtil.php';
require_once '../util/scriptUtil.php';
//Yahpp API
require_once '../common/common.php';//共通ファイル読み込み(使用する前に、appidを指定してください。)
// log.txtへ書き込み
log_write();

// ログアウトした際にログインしているユーザーの情報を格納しているセッションを破棄
$mode = !empty($_POST['mode']) ? $_POST['mode'] : null;
if($mode == 'LOGOUT' && !empty($_SESSION['user']) ){
    unset($_SESSION['user']);
}
// トップページに遷移した際に、商品詳細を格納したセッションと商品の個数を格納したセッションを破棄
if( !empty($_SESSION['hits_datail']) ){
    unset($_SESSION['hits_datail']);
    unset($_SESSION['item_num']);
}
//商品検索API
$search_result = item_search_YAHOOAPI($sortOrder, $appid, $categories);
$hits_search = $search_result[0];
$query = $search_result[1];
$sort =  $search_result[2];
$category_id = $search_result[3];

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
    </body>
</html>