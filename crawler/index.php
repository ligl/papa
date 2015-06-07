<?php
/**
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
include_once("../libs/simple_html_dom.php");
include_once("../libs/ez_sql_core.php");
include_once("../libs/ez_sql_mysql.php");
include_once("../libs/utils.php");
include_once("../config.php");
$db = new ezSQL_mysql($_CONFIG['db']['db_user'], $_CONFIG['db']['db_password'], $_CONFIG['db']['db_name'], $_CONFIG['db']['db_host']);
//$db_data["title"]="esacpe test --;"";
//$db->query("INSERT INTO assets SET ".$db->get_set($db_data));
//$rlt = $db->get_results("select * from assets");
//$db->debug();

//$url = "http://www.gtb8.com/";
//$html = file_get_html($url);
//foreach($html->find("img") as $element)
//    echo $element->src . "<br>cd";
//TODO 爬视频

//TODO 爬文章
//TODO 爬图片
$db->flush();
$cat_data['name']='aa李广亮';
$cat_data['type']=1;
$cat_data['join_time']=millisecond();
$rlt = $db->query("INSERT INTO category SET ".$db->get_set($cat_data));
