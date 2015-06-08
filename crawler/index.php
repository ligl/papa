<?php
/**
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
//定义全局入口点
define('IN_PAPA',true);

include_once('../libs/simple_html_dom.php');
include_once('../libs/ez_sql_core.php');
include_once('../libs/ez_sql_mysql.php');
include_once('../libs/utils.php');
include_once('../config.php');
include_once('../libs/GLogger.class.php');
header("Content-type: text/html; charset=utf-8");

//TODO 爬视频
$m = trim($_GET['m']);
switch($m){
    case 'video':
        //
        require_once('video.php');
        break;
    case 'story':
        //
        require_once('story.php');
        break;
    case 'picture':
        //
        require_once('picture.php');
        break;
    default:
        die('access invalid');
        break;
}
//TODO 爬文章
//TODO 爬图片
