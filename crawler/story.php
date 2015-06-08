<?php
/**
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
if (!defined('IN_PAPA'))
{
    die('Hacking attempt');
}

set_time_limit(0);//永不过期
//error_reporting(0);
$db = new ezSQL_mysql($_CONFIG['db']['db_user'], $_CONFIG['db']['db_password'], $_CONFIG['db']['db_name'], $_CONFIG['db']['db_host'], $_CONFIG['db']['encoding']);

$movie_id = $db->get_var('select max(out_id) as out_id from video');//影片编号
$movie_id = intval($movie_id) == 0 ? 1111 : $movie_id;
$fetch_rows = 2000;
$html = new simple_html_dom();
$cat_page=array('23'=>25,'24'=>17,'25'=>31,'26'=>19,'27'=>18,'28'=>17,'29'=>17,'30'=>17);
foreach($cat_page as $cid=>$p){
    $page=$p;
    while($page--){
        //TODO 获取行表
        $page_index =$p;
        if($page>1){
            $cid.='_'.$page;
        }
        $url="http://www.gtb8.com/html/part/index$page_index.html";
        $content = curl_get($url);
        $content = iconv("GBK", "UTF-8//IGNORE", $content);
        $html->load($content);
        if (!is_object($html)) {
            continue;
        }

    }

}
while ($movie_id++ > 0 && --$fetch_rows > 0) {

    $url = "http://www.gtb8.com/html/part/index.html";
    $content = curl_get($url);
    $content = iconv("GBK", "UTF-8//IGNORE", $content);
    $html->load($content);
    if (is_object($html)) {
        // 存在
        $jq = $html->find("div#jq", 0);
        if (!$jq) {
            GLogger::e('【小说】网页解析失败', $url);
            continue;
        }
    } else {
        // not found
        GLogger::e('【小说】地址不存在', $url);
    }
    //隔一秒一循环
    sleep(1);
}
//TODO 如果地址不能访问，发送邮件通过我
function get_cat_id($category)
{
    global $db;
    $cat = $db->get_row("select * from category where `name`='$category'");
    if (!$cat) {
        // 插入
        $cat_data['name'] = $category;
        $cat_data['type'] = 2;
        $cat_data['join_time'] = millisecond();
        $rlt = $db->query("INSERT INTO category SET " . $db->get_set($cat_data));
        if ($rlt) {
            $cat_id = $db->insert_id;
        } else {
            $cat_id = 0;
        }
    } else {
        $cat_id = $cat->id;
    }
    return $cat_id;
}