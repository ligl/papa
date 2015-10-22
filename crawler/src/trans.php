<?php
/**
 * Created by PhpStorm.
 * User: amose
 * Date: 15/10/22
 * Time: 下午10:33
 */
include_once('../libs/simple_html_dom.php');
include_once('../libs/ez_sql_core.php');
include_once('../libs/ez_sql_mysql.php');
include_once('../libs/utils.php');
include_once('../config.php');
include_once('../libs/GLogger.class.php');
header("Content-type: text/html; charset=utf-8");

//https://developers.pinterest.com/tools/api-explorer/
$token = 'AeCbvD_Z3IaRU2kv_qFTgZU4ZrghFA-IBMaQtfBCk5BGFQAQBgAAAAA';
$url = "https://api.pinterest.com/v1/pins/?access_token=$token";
$params['board'] = 'lepapa3565/pics';
$params['note'] = 'note 2';
if (isset($_GET['base64'])) {
    $base64_img = base64_encode(file_get_contents('http://img.sejiazu.com:3027/Uploads/vod/2013-08-08/bc478476-e072-45f4-b691-2a636a35060b.jpg'));
    $params['image_base64'] = $base64_img;
} else {
    $params['image_url'] = 'http://img.sejiazu.com:3027/Uploads/vod/2013-08-08/bc478476-e072-45f4-b691-2a636a35060b.jpg';
}
$rlt = curl_post($url, $params);
echo $rlt;
//echo curl_get('');