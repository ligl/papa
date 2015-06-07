<?php
/**
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
include_once('../libs/simple_html_dom.php');
include_once('../libs/ez_sql_core.php');
include_once('../libs/ez_sql_mysql.php');
include_once('../libs/utils.php');
include_once('../config.php');
include_once('../libs/GLogger.class.php');
//error_reporting(0);
$db = new ezSQL_mysql($_CONFIG['db']['db_user'], $_CONFIG['db']['db_password'], $_CONFIG['db']['db_name'], $_CONFIG['db']['db_host']);
GLogger::d('amose test');
//$url = "http://www.gtb8.com/allmovie.html";
//$url='http://www.gtb8.com/view/index3436.html';
//$html = file_get_html($url);
//for($i=1111;$i<1100;$i++){
//    $url="http://www.gtb8.com/view/index$i.html";
//    $html = file_get_html($url);
//    if($html){
//        echo 'ok.............................'.$url.'<br/>';
//    }else{
//        echo 'no.......'.$url.'<br/>';
//    }
//}
//TODO 如果地址不能访问，发送邮件通过我
//foreach ($html->find('img') as $element)
//    echo $element->src . '<br>cd';