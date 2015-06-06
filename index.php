<?php
/**
 * Created by PhpStorm.
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
include_once('libs/simple_html_dom.php');

$url = "http://www.gtb8.com/";
$html = file_get_html($url);
foreach($html->find('img') as $element)
    echo $element->src . '<br>cd';
