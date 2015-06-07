<?php
/**
 * User: amose
 * Date: 2015/6/7
 * Time: 15:04
 */
function millisecond()
{
    return ceil(microtime(true) * 1000);
}

function curl_get($pUrl, $pCookies = false, $pCookieSuffix = "")
{
    $ch = curl_init($pUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.");

    if ($pCookies) {
        $cookieFile = "cookie" . $pCookieSuffix . ".txt";
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }

    $html = curl_exec($ch);
    $html = trim($html);

    curl_close($ch);

    if ($html == "") {
        return false;
    }

    return $html;
}

function curl_post($pUrl, $data = '')
{
    if (is_array($data)) {
        $post_data = http_build_query($data);
    } else {
        $post_data = $data;
    }
    $ch = curl_init($pUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/A.B (KHTML, like Gecko) Chrome/X.Y.Z.W Safari/A.B.");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $html = curl_exec($ch);
    $html = trim($html);

    curl_close($ch);

    if ($html == "") {
        return false;
    }
    return $html;
}

function resp()
{
    $varNum = func_num_args();
    $varArray = func_get_args();
    if ($varNum == 0) {
        //成功
        $res['code'] = 0;
    } else if ($varNum == 1) {
        $res['code'] = 0;
        $res['data'] = $varArray[0];
    } else {
        $res['code'] = intval($varArray[0]);
        $res['msg'] = $varArray[1];
    }
    echo json_encode($res);
    exit;
}

function guid()
{
    mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $uuid = substr($charid, 0, 8)
        . substr($charid, 8, 4)
        . substr($charid, 12, 4)
        . substr($charid, 16, 4)
        . substr($charid, 20, 12);
    return $uuid;
}