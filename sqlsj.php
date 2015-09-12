<?
include('head.php');
$sql='
CREATE TABLE "shoucangtz"(
[id] integer PRIMARY KEY AUTOINCREMENT
,[user] vargraphic(255)
,[tzid] vargraphic(255)
,[bkid] vargraphic(255)
);
';
$DB->exec($sql);
echo 'sql升级成功,sql文件删除成功！';
unlink('sqlsj.php');
exit;
?>