<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(0);
include('head.php');

/* SQL */
$sql='
CREATE TABLE "bankuai"(
[id] integer PRIMARY KEY AUTOINCREMENT
,[bk] vargraphic(255)
,[banzhu] text
);
CREATE TABLE "lchf"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[user] text
,[nr] text
,[sj] vargraphic(255)
,[tiezihfid] vargraphic(255));
CREATE TABLE "liaot"(
[id] integer PRIMARY KEY AUTOINCREMENT
,[user] varchar(255)
,[sj] varchar(255)
,[nr] text
,[to] varchar(255));
CREATE TABLE "neixin"(
[id] integer PRIMARY KEY AUTOINCREMENT
,[kan] varchar(255)
,[user] varchar(255)
,[sj] varchar(255)
,[nr] text
,[to] varchar(255));
CREATE TABLE "qd"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[time] vargraphic(255)
,[user] text);
CREATE TABLE "saima"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[ma1] text
,[ma2] text
,[ma3] text
,[ma4] text
,[ma5] text
,[ma6] text
,[youma] text
,[user] text);
CREATE TABLE "sanjiedou"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[shenjie] text
,[fojie] text
,[mojie] text
,[youjie] text
,[user] text);
CREATE TABLE "tiezi"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[user] text
,[bt] text
,[nr] text
,[sj] vargraphic(255)
,[bk] vargraphic(255)
,[jin] vargraphic(255)
,[dian] text
,[hfsj] vargraphic(255)
,[ding] vargraphic(255));
CREATE TABLE "tiezihf"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[user] text
,[nr] text
,[sj] vargraphic(255)
,[tzid] vargraphic(255));
CREATE TABLE "time"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[saimatime] vargraphic(255)
,[touzitime] vargraphic(255)
,[sanjiedoutime] vargraphic(255));
CREATE TABLE "touxiang"(
[id] integer PRIMARY KEY AUTOINCREMENT
,[user] vargraphic(255)
,[txurl] text);
CREATE TABLE "touzi"(
[Id] integer PRIMARY KEY AUTOINCREMENT
,[damoney] text
,[xiaomoney] text
,[danmoney] text
,[shuangmoney] text
,[baozimoney] text
,[dxds] text
,[user] text);
CREATE TABLE "user"(
[id] integer PRIMARY KEY AUTOINCREMENT
,[user] vargraphic(255)
,[pass] vargraphic(255)
,[rmb] vargraphic(255)
,[vip] vargraphic(255)
,[qq] vargraphic(255)
,[tel] vargraphic(255)
,[sj] vargraphic(255)
,[qianming] vargraphic(255)
,[zhfwsj] vargraphic(255)
,[admin] vargraphic(255));
CREATE TABLE "shoucangtz"(
[id] integer PRIMARY KEY AUTOINCREMENT
,[user] vargraphic(255)
,[tzid] vargraphic(255)
,[bkid] vargraphic(255)
);
';

$act=$_GET['act'];

echo "<title>CHEN挂Q官方论坛安装！</title>";
$foot = '<hr>© Powered by <a href="http://hu60.cn/wap/0wap/?cid=msg&pid=send&touid=12882">QQ:1621158072!</a>';
if (file_exists("lock")) {
echo "你已经成功安装过！如果你要重新安装，请删除目录下的‘lock’文件再运行安装";
echo $foot;
exit();
}

if ($act == '') {
echo "<pre>";
include ("readme.txt");
echo "</pre>";
echo "<br><a href='install.php?act=2'>下一步</a>";
}

if ($act == '2') {
echo "数据库信息：<hr>
<form action='install.php?act=bc' method='post'>
论坛名称:<br><input type='text' name='bt' value=''/><br/>
Sqlite数据库名:<br><input type='text' name='km' value=''/>
<hr>
<input type='submit' value='保存'/>
";
}

if ($act == 'bc') {
$km = $_POST['km'];
$bt = $_POST['bt'];
$km=strtoupper(md5($km.'qq1621158072'));
$connfile = '<?php
$title="'.$bt.'";
$sqlitedb="'.$km.'";
$zckg="0";
$zcts="论坛关闭了注册，www.69mz.com";
$fwkg="0";
$fwts="系统维护中！请稍后访问！";
$ltggkg="0";
$ltgg="";
?>';
$fp = fopen("config.php", "w");
fwrite($fp, $connfile);
fclose($fp);
echo "文件保存成功！<a href='install.php?act=3'>点击继续</a>";
}

if($act == '3')
{
echo "设置管理员帐号：<hr>
<form action='install.php?act=bc2' method='post'>
管理员帐号:<br><input type='text' name='zh' value=''/><br/>
管理员密码:<br><input type='text' name='mm' value=''/><br/>
<input type='submit' value='保存'/>";
echo $foot;
}

if($act == 'bc2')
{
$zh=$_POST['zh'];
$mm=$_POST['mm'];
$DB->exec($sql);
$DB->insert('user', array('user'=>$zh,'pass'=>$mm,'rmb'=>'1000','vip'=>'no','qq'=>'该用户还没留下QQ','tel'=>'该用户还没设置电话号','sj'=>$date,'qianming'=>'该用户还没设置签名','zhfwsj'=>$date,'admin'=>'yes'));
$fp = fopen("lock", "w");
fwrite($fp, '安装锁');
fclose($fp);
file_get_contents('http://lt.jieshao.pw/tj.php?act=tj&ym='.$_SERVER['SERVER_NAME'].$_SERVER["PHP_SELF"]);//统计安装数量 请不要删除
echo '安装成功';
}



?>
