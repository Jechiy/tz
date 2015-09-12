<?
include_once('db.php');
include_once ('conn.php');
include_once ('system.php');
$act=$_GET['act'];
$my=$_GET['my'];
$user1=$_POST['user1'];
$pass1=$_POST['pass1'];
$pass2=$_POST['pass2'];
$user = $_COOKIE["user"];
$pass = $_COOKIE["pass"];
$xx=$_GET['xx'];
$url=$_GET['url'];
$ltnr=$_POST['ltnr'];
$id=$_GET['id'];
$hid=$_POST['hid'];
$co=$_POST['co'];
$to=$_GET['to'];
$to1=$_POST['to1'];
$to2=$_POST['to2'];
$sql=$_POST['sql'];
$yajin=$_POST['yajin'];
$caiquan=$_POST['caiquan'];
$saima=$_POST['saima'];
$dxds=$_POST['dxds'];
$jiner=$_POST['jiner'];
$yhm=$_POST['yhm'];
$junzi=$_POST['junzi'];
$shili=$_POST['shili'];
$gid = $_REQUEST['gid'];
$tid = $_REQUEST['tid'];
$gao = $_REQUEST['gao'];
$sl = $_REQUEST['sl'];
$dan = $_REQUEST['dan'];
$bt = $_REQUEST['bt'];
$nr = $_REQUEST['nr'];

$bkid=$_GET['bkid'];
$tzid=$_GET['tzid'];
$htid=$_GET['htid'];
$search = $_REQUEST['search'];
$bkname = $_GET['bkname'];


$ha='<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel= "stylesheet" type= "text/css" href="theme/css/css.css">
<script src="theme/js/jquery-1.6.2.min.js"></script>
</head>
<body>
<div class="bj">
<!--
<div class="logo"><img src="theme/img/logo.gif"></div>
-->
';

if ($user != '' and $pass !='') {
$ha .= '<div class="n"><a href="/"><div class="n1">首页</div></a><a href="?act=jin"><div class="n1">精品</div></a><a href="?act=youxi"><div class="n1">游戏</div></a>
<a href="?act=exit"><div class="n2">退出</div></a> <a href="?act=user&id='.$user.'"><div class="n2">'.$user.'</div></a> <a href="?act=xin"><div class="n2">信箱</div></a>
</div>';
$liaot='
<div class="d h"><form action="?act=liaoadd" method="post">
<input type="text" name="ltnr" maxlength="140" class="q" />
<br/>
<input type="submit" name="sub1" value="聊天"/></div>
';
$userset='
<a href="?act=userset&id='.$user.'"><div class="boxxs1">设置</div></a> <a href="?act=ghtx&id='.$user.'"><div class="boxxs1">更换头像</div></a> <a href="?act=xgmm"><div class="boxxs1">修改密码</div></a>
';
$faneixin= '<div class="d h">
<form action="?act=xin&my=fa&id='.$user.'" method="post">
<input type="hidden" name="hid" value="'.$id.'">
<input type="text" name="co" maxlength="140" class="q" value=""/><br/>
<input type="submit" name="sub1" value="发内信"/></div>
';

$fatie='
<a name="post"></a><div class="d h">
<form action="?act=fatie&bkid='.$bkid.'" method="post" enctype="multipart/form-data">
标题:<br/><input type="text" name="bt" maxlength="500" class="q" />
<br/>内容:<span class="g">[必填]</span><br/>
<input type="text" name="nr" maxlength="500" class="q" /><br/>
<input type="submit" name="sub1" value="发贴"/></form>
</div>
';
$huitie='
<a name="post"></a><div class="d h">
<form action="?act=huitie&tzid='.$tzid.'&bkid='.$bkid.'" method="post" enctype="multipart/form-data">
内容:<br/><input type="text" name="nr" maxlength="500" class="q" />
<input type="submit" name="sub1" value="回帖"/></form>
</div>
';
$lchuitie='
<div class="bc m">
<form action="?act=lcht&tzid='.$tzid.'&htid='.$htid.'" method="post">
<input type="text" name="nr" maxlength="140" class="q" /><br/>
<input type="submit" name="sub1" value="回贴"/>
</div>
';

}else{
$ha .= '<div class="n"><a href="/"><div class="n1">首页</div></a><a href="?act=jin"><div class="n1">精品</div></a><a href="?act=youxi"><div class="n1">游戏</div></a>
<a href="?act=reg"><div class="n2">注册</div></a> <a href="?act=login"><div class="n2">登录</div></a>
</div>';
$liaot='';
$userset='';
$faneixin='';
$fatie='';
$huitie='';
}




?>