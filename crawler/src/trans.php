<?php
/**
 * Created by PhpStorm.
 * User: amose
 * Date: 15/10/22
 * Time: 下午10:33
 */
if (!defined('IN_PAPA')) {
    die('Hacking attempt');
}
set_time_limit(0);//永不过期
//error_reporting(0);

//https://developers.pinterest.com/tools/api-explorer/
$db = new ezSQL_mysql($_CONFIG['db']['db_user'], $_CONFIG['db']['db_password'], $_CONFIG['db']['db_name'], $_CONFIG['db']['db_host'], $_CONFIG['db']['encoding']);

$movie_id = $db->get_var("select id from `video` where poster='' and weight=-100 limit 1");//影片编号

echo '<h2>start trans [' . date('Y-m-d H:i:s') . ']</h2>';
while ($v_data = $db->get_results("select * from video where id>$movie_id and weight>0 limit 50")) {
    foreach ($v_data as $item) {
        try {
            $movie_id = $item->id;
            //poster
            $org_poster = $item->org_poster;
            if (empty($org_poster)) {
                continue;
            }
            $pic_url = post_pinterest($movie_id, $org_poster);
            if ($pic_url) {
                update_poster($movie_id, $pic_url);
            }
            //pics
            $org_pics = $item->org_pics;
            if (empty($org_pics)) {
                continue;
            }
            $org_pics = explode(',', $org_pics);
            $pics = array();
            foreach ($org_pics as $pic) {
                $pic_url = post_pinterest($movie_id, $pic);
                if ($pic_url) {
                    $pics[] = $pic_url;
                }
            }
            if ($pics) {
                update_pics($movie_id, $pics);
            }
            echo '<p>' . $movie_id . '-' . $item->title . '</p>';
        } catch (Exception $e) {
            GLogger::e('[gtb8]获取图片失败', $e);
        }
    }
}
echo '<h2>end trans [' . date('Y-m-d H:i:s') . ']</h2>';

function update_poster($id, $poster)
{
    global $db;
    $poster = $db->escape($poster);
    $rlt = $db->query("update video set poster='$poster' where id=$id");
    if ($rlt) {
        return true;
    } else {
        return false;
    }
}

function update_pics($id, $pics)
{
    global $db;
    $pics = $db->escape(join(',', $pics));
    $rlt = $db->query("update video set pics='$pics' where id=$id");
    if ($rlt) {
        return true;
    } else {
        return false;
    }
}

function post_pinterest($id, $pic)
{
    sleep(1);
    $token = 'AUqNziNVZOo2r01hkpg3pKshk2AQFA_P5ZWk7gpCk5BGFQAQBgAAAAA';
    $url = "https://api.pinterest.com/v1/pins/?access_token=$token";
    $params['board'] = 'lepapa3565/pics';

    $params['note'] = $id;

    $base64_img = base64_encode(file_get_contents($pic));
    $params['image_base64'] = $base64_img;

    //$params['image_url'] = 'http://img.sejiazu.com:3027/Uploads/vod/2013-08-08/bc478476-e072-45f4-b691-2a636a35060b.jpg';

    $rlt = curl_post($url, $params);

    $rlt = json_decode($rlt, true);
    if ($rlt['data']) {
        $pin_url = $rlt['data']['url'];
        $rlt = curl_get($pin_url);
        $html = new simple_html_dom();
        $html->load($rlt);
        if ($html) {
            // 存在
            $pinImage = $html->find("a.paddedPinLink", 0);
            if ($pinImage) {
                return $pinImage->href;
            } else {
                GLogger::e('[pinterest]网页解析图片失败', $pin_url);
                return false;
            }
        } else {
            //加载失败
            GLogger::e('[pinterest]网页加载失败', $pin_url);
            return false;
        }
    } else {
        GLogger::e('[pinterest]上传失败', $rlt);
        return false;
    }
}