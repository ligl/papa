<?php
/**
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
set_time_limit(0);//永不过期

include_once('../libs/simple_html_dom.php');
include_once('../libs/ez_sql_core.php');
include_once('../libs/ez_sql_mysql.php');
include_once('../libs/utils.php');
include_once('../config.php');
include_once('../libs/GLogger.class.php');
header("Content-type: text/html; charset=utf-8");
//error_reporting(0);
$db = new ezSQL_mysql($_CONFIG['db']['db_user'], $_CONFIG['db']['db_password'], $_CONFIG['db']['db_name'], $_CONFIG['db']['db_host'],$_CONFIG['db']['encoding']);

$movie_id = $db->get_var('select max(out_id) as out_id from video');//影片编号
$movie_id = intval($movie_id) == 0 ? 1000 : $movie_id;
$fetch_rows = 1000;

$html = new simple_html_dom();
do {
    $url = "http://www.gtb8.com/view/index$movie_id.html";
    $content = curl_get($url);
    $content = iconv("GBK","UTF-8//IGNORE",$content);
    $html->load($content);
    if ($html) {
        // 存在
        $jq = $html->find("div#jq", 0);
        $info = $jq->find('div.infobox .info', 0);
        $title = trim($info->children(0)->plaintext);
        $category = trim($info->children(2)->find('a', 0)->plaintext);
        $url = "http://www.gtb8.com/video/?$movie_id-0-0.html";
        $content = curl_get($url);
        $content = iconv("GBK","UTF-8//IGNORE",$content);
        $html->load($content);
        $video_list = $html->find('div.playerbox>.play655', 0)->find('script', 0)->innertext;
        $regex = '/\[\[.*\]\]/';
        if (preg_match($regex, $video_list, $matches)) {
            $cat_id = get_cat_id($category);
            $video_list = $matches[0];
            $video_list = str_replace("'", '"', $video_list);
            $video_list = json_decode($video_list, true);
            foreach ($video_list as $video) {
                // 插入数据库
                //类型 qvod xfplay
                $type = strtolower($video[0]);
                $v_data['title'] = $title;
                $v_data['type'] = $type;
                $v_data['cat_id'] = $cat_id;
                $play_list = $video[1];
                foreach ($play_list as $u) {
                    if (preg_match('/\$(.*)\$/', $u, $matches)) {
                        $v_data['url'] = $matches[1];
                        $v_data['guid'] = guid();
                        $v_data['out_id'] = $movie_id;
                        $v_data['join_time'] = millisecond();
                        $rlt = $db->query("INSERT INTO video SET " . $db->get_set($v_data));
                        if (!$rlt) {
                            GLogger::e('插入视频失败', $v_data);
                        }
                    }
                }
            }
        } else {
            GLogger::e('没有视频源', $url);
        }
        // var VideoListJson=[['xfplay',['\u7B2C1\u96C6$xfplay://dna=meEbAGIfmdjbAGbgmxfcDZx5AdyeBefbAHD5mwa2meHWAwEeAxD0At|dx=107518546|mz=7405_onekeybatch.mp4|zx=nhE0pdOVl3P5AF5uLKP5rv5wo206BGa4mc94MzXPozS|zx=nhE0pdOVl3Ewpc5xqzD4AF5wo206BGa4mc94MzXPozS$xfplay']]],urlinfo='http://'+document.domain+'/video/?4959-<from>-<pos>.html';
    } else {
        // not found
        GLogger::e('视频地址不存在', $url);
    }
    $movie_id++;
} while (--$fetch_rows > 0);
//TODO 如果地址不能访问，发送邮件通过我
//foreach ($html->find('img') as $element)
//    echo $element->src . '<br>cd';
function get_cat_id($category)
{
    global $db;
    $cat = $db->get_row("select * from category where `name`='$category'");
    if (!$cat) {
        // 插入
        $cat_data['name'] = $category;
        $cat_data['type'] = 1;
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