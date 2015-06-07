<?php
/**
 * Created by PhpStorm.
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
include_once('libs/simple_html_dom.php');
include_once('libs/ez_sql_core.php');
include_once('libs/ez_sql_mysql.php');
include_once('libs/utils.php');
include_once('config.php');
//$db = new ezSQL_mysql($_CONFIG['db_user'],$_CONFIG['db_password'],$_CONFIG['db_name'],$_CONFIG['db_host']);
//$db_data['title']='esacpe test --;"';
//$db->query("INSERT INTO assets SET ".$db->get_set($db_data));
//$rlt = $db->get_results("select * from assets");
//if($rlt){
//    $item   = $rlt[0];
//   echo $item->title;
//}
//
//$db->debug();

//$url = "http://www.gtb8.com/";
//$html = file_get_html($url);
//foreach($html->find('img') as $element)
//    echo $element->src . '<br>cd';
resp(1,strlen(md5('asdadfasf')));