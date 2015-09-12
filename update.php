<?
header("Content-type: text/html; charset=utf-8");
error_reporting(0);
ignore_user_abort(true);

/* 修改以下内容即可 */
$v='1.1';//当前程序版本
$url='http://lt.jieshao.pw/v.php';//检查更新网址


if(!class_exists('ZipArchive')) {
die("调用ZipArchive类失败！");
exit();
}
//函数
 function explodes($a,$b,$c)
{
$aa=explode($a,$c);
$aa=explode($b,$aa[1]);
return $aa[0];
}

function zipExtract ($src, $dest)
{
$zip = new ZipArchive();
if ($zip->open($src)===true)
{
$zip->extractTo($dest);
$zip->close();
return true;
}
return false;
}

//获取最新版本
function newversion($url)
{
$ym=file_get_contents($url);
$ym=explodes('<version>','</version>',$ym);
return $ym;
}

//获取更新包下载地址
function newlink($url)
{
$ym=file_get_contents($url);
$ym=explodes('<link>','</link>',$ym);
return $ym;
}

//获取更新内容
function newcontent($url)
{
$ym=file_get_contents($url);
$ym=explodes('<content>','</content>',$ym);
return $ym;
}

$xxdz=$_SERVER['PHP_SELF'];
$act = isset($_GET['act']) ? htmlspecialchars($_GET['act']) : '';
switch ($act) {
default:
$newv=newversion($url);
$newlink=newlink($url);
$newcontent=newcontent($url);
if($v === $newv)
{
echo '程序是最新版本！';
}else{
echo '发现新版本！<br>版本号：'.$newv.'<br>';
echo '更新内容:<br>'.$newcontent.'<br>';
echo '<a href="?act=update&url='.$newlink.'">点击更新</a>';
}
break;

case 'update':
$RemoteFile = rawurldecode($_GET["url"]);
$ZipFile = "Archive.zip";
$Dir = "./";
copy($RemoteFile,$ZipFile) or die("无法下载更新包文件！".'<a href="'.$xxdz.'">返回上级</a>');
if (zipExtract($ZipFile,$Dir)) {
echo "程序更新成功！<br>";
echo '<a href="'.$xxdz.'">返回上级</a>';
unlink($ZipFile);
}
else {
echo "无法解压文件！<br>";
echo '<a href="'.$xxdz.'">返回上级</a>';
if (file_exists($ZipFile)) {
unlink($ZipFile);
}
}
break;
}
?>