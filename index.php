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
include_once('config.php');
global $_CONFIG;
$db_user='root';
$db = new ezSQL_mysql($_CONFIG['db_user'],$_CONFIG['db_password'],$_CONFIG['db_name'],$_CONFIG['db_host']);
$db->flush();