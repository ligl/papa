<?php
/**
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
$db = new ezSQL_mysql($_CONFIG['db']['db_user'], $_CONFIG['db']['db_password'], $_CONFIG['db']['db_name'], $_CONFIG['db']['db_host'], $_CONFIG['db']['encoding']);

$db->query("select * from category");
$db->debug();