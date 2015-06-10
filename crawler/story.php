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
    $page=$p+1;
    while($page--){
        $page_index =$cid;

        if($page>1){
            $page_index.='_'.$page;
        }
        $url="http://www.gtb8.com/html/part/index$page_index.html";
        $content = curl_get($url);
        $content = iconv("GBK", "UTF-8//IGNORE", $content);
        $html->load($content);
        if (!is_object($html)) {
            continue;
        }
        $listt = $html->find("table.listt");
        foreach($listt as $item){
            $story = $item->find('a',0);
            //http://www.gtb8.$itemcom
            if (!is_object($story)) {
                GLogger::e("[小说]解析失败",$url);
                sleep(1);
                continue;
            }
            $url="http://www.gtb8.com".$story->href;
            $content = curl_get($url);
            $content = iconv("GBK", "UTF-8//IGNORE", $content);
            $html->load($content);
            if (!is_object($html)) {
                sleep(1);
                continue;
            }
            $content = $html->find("div#ks_xp .n_bd",0);
            if (!is_object($content)) {
                sleep(1);
                continue;
            }
            $rr = str_replace("/html/article/index",'',$story->href);
            $out_id=intval(str_replace('.html','',$rr));
            $row= $db->get_row("select * from story where out_id=".$out_id);
            if($row){
                sleep(1);
                continue;
            }
            $title=$story->title;
            if (preg_match('/【(.*?)】/', $title, $matches)) {
                $title=$matches[1];
            }
            $content=$content->innertext;
            $cat_id = $cid;
            $v_data['title'] = $title;
            $v_data['cat_id'] = $cat_id;
            $v_data['content']=$content;
            $v_data['guid'] = guid();
            $v_data['out_id'] = $out_id;
            $v_data['join_time'] = millisecond();
            $rlt = $db->query("INSERT INTO story SET " . $db->get_set($v_data));
            if (!$rlt) {
                GLogger::e('[小说]插入失败', $v_data);
            }
            //var_dump($v_data);
            sleep(1);
        }
        sleep(1);
    }
}