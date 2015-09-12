<?
if(is_file($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php')){
    require_once($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php');
} // 注意文件路径
error_reporting(null);
$ly=$_SERVER['HTTP_REFERER'];
$ym=$_SERVER['SERVER_NAME'];
date_default_timezone_set("PRC");
$date = date("Y-m-j H:i:s ");
include('config.php');
define('DB_TYPE','sqlite');
define('DB_PATH',$sqlitedb);
define('DB_A','');
$DB=new db;
?>