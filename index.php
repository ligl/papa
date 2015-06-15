<?php
/**
 * Created by PhpStorm.
 * User: amose
 * Date: 15/6/6
 * Time: 上午11:43
 */
require 'Slim/Slim.php';
require 'notorm/NotORM.php';
require 'config/config.php';
\Slim\Slim::registerAutoloader();
$db_user  = $_CONFIG['db']['db_user'];
$db_password=$_CONFIG['db']['db_password'];
$db_name=$_CONFIG['db']['db_name'];
$db_host=$_CONFIG['db']['db_host'];
$db_encoding = $_CONFIG['db']['encoding'];

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name",$db_user,$db_password);
$db = new NotORM($pdo);
$db->exec("SET NAMES 'utf8'");
$app = new \Slim\Slim(array(
    'templates.path' => './app/tpl'
));
//page & view route
$app->get('/', function () use ($app){
    $app->render('index.htm',array('name'=>'amose'));
});

//api route
$app->group('/api',function() use($app){

});

$app->run();