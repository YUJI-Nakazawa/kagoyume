<?php function return_top(){ ?>
    <a href="<?php echo ROOT_URL; ?>">TOP</a>
<?php } ?>

<?php 
function page_head(){?>
    <h1><a href="<?php echo ROOT_URL; ?>">かごゆめ</a></h1><?php
    if( !empty($_SESSION['user']) ){?>
        <h5>ようこそ!<a href="<?php echo MY_DATA; ?>">
            <?php echo $_SESSION['user'][0]['name']; ?>
        </a>さん</h5>
    <?php }?>
    <ul>
        <li><a href="<?php echo ROOT_URL; ?>">TOP</a></li>
    <?php 
    //ログインしていない場合、ログインのリンクを表示
    if( empty($_SESSION['user']) ){?>
        <li><a href="<?php echo LOGIN; ?>">ログイン</a></li>
    <?}
    //ログインしている場合、ユーザー情報ページヘとログアウトのリンクを表示
    else{?>
        <li><a href="<?php echo CART; ?>">カート</a></li>
        <li>
            <form action="<?php echo ROOT_URL; ?>" method="POST">
                <input type="submit" value="ログアウト">
                <input type="hidden" name="mode" value="LOGOUT">
            </form>
        </li>
    <?php } ?>
    </ul>
<?php } ?>

<?php 
function search_form($sortOrder, $sort, $categories, $category_id, $query){ ?>
    
    
    
    <form action="<?php echo SEARCH ?>" class="Search">
    表示順序:
    <select name="sort">
    <?php foreach ($sortOrder as $key => $value) { ?>
    <option value="<?php echo h($key); ?>" <?php if($sort == $key) echo "selected=\"selected\""; ?>><?php echo h($value);?></option>
    <?php } ?>
    </select>
    キーワード検索：
    <select name="category_id">
    <?php foreach ($categories as $id => $name) { ?>
    <option value="<?php echo h($id); ?>" <?php if($category_id == $id) echo "selected=\"selected\""; ?>><?php echo h($name);?></option>
    <?php } ?>
    </select>
    <input type="text" name="query" value="<?php echo h($query); ?>"/>
    <input type="submit" value="Yahooショッピングで検索"/>
    </form>
<?php } ?>

<?php 
//商品検索YAHOO API
function item_search_YAHOOAPI($sortOrder, $appid, $categories){
    $query = !empty($_GET['query']) ? $_GET['query'] : "";
    $sort =  !empty($_GET['sort']) && array_key_exists($_GET['sort'], $sortOrder) ? $_GET['sort'] : "-score";
    $category_id = !empty($_GET['category_id']) && ctype_digit($_GET['category_id']) && array_key_exists($_GET['category_id'], $categories) ? $_GET['category_id'] : 1;
    if ($query != "") {
        $query4url = rawurlencode($query);
        $sort4url = rawurlencode($sort);
        $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?appid=$appid&query=$query4url&category_id=$category_id&sort=$sort4url";
        $xml = simplexml_load_file($url);
        if ($xml["totalResultsReturned"] != 0) {//検索件数が0件でない場合,変数$hitsに検索結果を格納します。
            return array($xml->Result->Hit, $query, $sort, $category_id);
        }
    }
}
//商品コード検索YAHOO API
function item_detail_YAHOOAPI($appid, $item_code, $image_size){
        $responsegroup = 'medium'; //APIで取得するデータのサイズ:small / medium / large から選択
        $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemLookup?appid=$appid&itemcode=$item_code&image_size=$image_size&responsegroup=$responsegroup";
        $xml = simplexml_load_file($url);
        $item_name = h($xml->Result->Hit->Name);
        $item_price = h($xml->Result->Hit->Price);
        $item_image = h($xml->Result->Hit->ExImage->Url);//商品のメイン画像
        $item_image_s = h($xml->Result->Hit->Image->Small);//商品の小さい(76×76)画像
        return array('item_code' => $item_code, 'item_name' => $item_name, 'item_price' => $item_price, 'item_image' => $item_image, 'item_image_s' => $item_image_s);
}

/**
 * フォームの再入力時に、すでにセッションに対応した値があるときはその値を返却する
 * @param mixed $name formのname属性
 * @return mixed セッションに入力されていた値
 */
function form_value($name){
    if(isset($_POST['mode']) && $_POST['mode']=='REINPUT'){
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }
    }
    else{ //再入力時でない時、セッションにｎullを入れる。
        $_SESSION[$name] = null;
        return $_SESSION[$name];
    }
}

/**
 * ゲットから存在チェックしてからセッションに値を渡す。
 * 二回目以降のアクセス用に、ゲットから値の上書きがされない該当セッションは初期化する
 * @param mixed $name
 * @return mixed
 */
function bind_g2s($arg){
    if(!empty($_GET[$arg])){
        $_SESSION[$arg] = $_GET[$arg];
        return $_GET[$arg];
    }
    else{
        $_SESSION[$arg] = null;
        return null;
    }
}
/**
 * ポストから存在チェックしてからセッションに値を渡す。
 * 二回目以降のアクセス用に、ポストから値の上書きがされない該当セッションは初期化する
 * @param mixed $arg
 * @return mixed
 */
function bind_p2s($arg){
    if(!empty($_POST[$arg])){
        $_SESSION[$arg] = $_POST[$arg];
        return $_POST[$arg];
    }else{
        $_SESSION[$arg] = null;
        return null;
    }
}

function log_write(){
    // $filename = 'log.txt';
    $time = date('Y/m/d H:i:s');//アクセス日時の取得
    $request_url = $_SERVER['REQUEST_URI'];//URLを取得
    $http_referer = $_SERVER['HTTP_REFERER'];//遷移元のURLを取得
    $log = 'アクセス日時:'.$time.'/URL:'.$request_url.'/遷移元URL:'.$http_referer;
    
    $fp = fopen('../logs/log.txt','a');
    fwrite($fp,$log."\n");
    fclose($fp);
}
 ?>