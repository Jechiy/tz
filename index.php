<?
header("Content-type: text/html; charset=utf-8");
include('head.php');

if (!file_exists("config.php"))
{
echo '<a href="install.php">点击这里安装</a>';
exit();
}

if($fwkg == "1")
{
echo '<title>系统维护</title>';
echo $fwts;
exit;
}

/* 首页 */
if($act == "")
{


if($ltggkg == "1")
{
$gg='<div class="d h"><font color="red">论坛公告：'.$ltgg.'</font></div>';
}


echo $ha;
echo <<<HTML
<a name="top"></a><title>{$title}</title>
<div class='fens'><a href='?act=tzlist&bkid=1'><div class='fen'><img src='theme/img/fen1.png'></div></a><a href='?act=tzlist&bkid=2'><div class='fen'><img src='theme/img/fen2.png'></div></a><a href='?act=tzlist&bkid=3'><div class='fen'><img src='theme/img/fen3.png'></div></a><a href='?act=tzlist&bkid=4'><div class='fen'><img src='theme/img/fen4.png'></div></a><a href='?act=tzlist&bkid=5'><div class='fen'><img src='theme/img/fen5.png'></div></a><a href='?act=tzlist&bkid=6'><div class='fen'><img src='theme/img/fen6.png'></div></a></div>
{$gg}
<div class="boxs">
HTML;

$a=$DB->select('*','tiezi','order by id desc limit 0,8')->fetchALL();
if($a){
foreach($a as $row){

/* 统计帖子回复数（不统计楼层回复） */
$huinum=$DB->select('count(*)','tiezihf',"WHERE tzid='".$row['Id']."'")->fetchALL(PDO::FETCH_COLUMN);
$huinum=$huinum['0'];

echo '
<a href="?act=cktz&tzid='.$row['Id'].'&bkid='.$row['bk'].'">
<div class="box">
<div class="text">
'.$row['bt'].'
</div>
<p>点'.$row['dian'].' '.$row['user'].' '.$row['sj'].'</p>
<div class="hui"></div>
<div class="huia">'.$huinum.'</div>
</div></a>
';
}
}
echo '<a href="?act=lt"><div class="move">...</div></a></div>';
include('foot.php');
}

/* 网站后台管理 */
if($act == "admin")
{
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
if($row['admin'] == "yes")
{
echo $ha;
echo '<a name="top"></a><title>后台管理 - '.$title.'</title>';

echo '<div class="d h">您好！管理员！</div>';
echo '<div class="d h"><a href="?act=admin&my=bk">板块管理</a> <a href="?act=admin&my=yh">用户管理</a> <a href="?act=admin&my=set">论坛设置</a>
</div>';

/* 板块管理 */
if($my == "bk")
{
$accc=$DB->select('*','bankuai',"WHERE 1")->fetchALL();
$count=count($accc);
if($count <= 0)
{
$DB->query('delete from bankuai');
}

echo '<div class="d h">板块列表:<br><br>';
$c=$DB->select('*','bankuai',"WHERE 1")->fetchALL();
if($c){
foreach($c as $row){
echo '板块ID：'.$row['id'].'<br>板块名称：<a href="?act=tzlist&bkid='.$row['id'].'">'.$row['bk'].'</a> <a href="?act=admin&my=xg&bkid='.$row['id'].'&bkname='.$row['bk'].'">修改</a> <a href="?act=admin&my=sc&bkid='.$row['id'].'">删除</a><br><br>';
}
}
echo '</div>';
echo '
<div class="d h">
<form>
添加板块: 输入板块名称<br/>
<input type="hidden" name="act" value="admin"/>
<input type="hidden" name="my" value="bk"/>
<input type="hidden" name="my" value="tj"/>
<input type="text" name="bkname" maxlength="10" class="q" />
<input type="submit" name="sub1" value="添加"/></form>
</div>
';
}

/* 添加板块 */
if($my == 'tj')
{
if($bkname == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
$DB->insert('bankuai',array('bk'=>$bkname,'banzhu'=>''));
echo '<script language=\'javascript\'>alert(\'板块添加成功！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}
}

/* 修改板块 */
if($my == 'xg')
{
if($bkid == "" or $bkname == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
echo '
<div class="d h">
<form>
修改板块: 板块名称:<br/>
<input type="hidden" name="act" value="admin"/>
<input type="hidden" name="my" value="bk"/>
<input type="hidden" name="my" value="xg1"/>
<input type="hidden" name="bkid" maxlength="10" class="q" value="'.$bkid.'"/>
<input type="text" name="bkname" maxlength="10" class="q" value="'.$bkname.'"/>
<input type="submit" name="sub1" value="修改"/></form>
</div>
';
}
}
if($my == "xg1")
{
if($bkid == "" or $bkname == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
if(!preg_match("/^[0-9]\d{0,99}$/",$bkid)) {
echo '<script language=\'javascript\'>alert(\'请输入正确的板块ID！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
$ac=$DB->select('*','bankuai',"WHERE id='$bkid'")->fetchALL();
$ac=$ac['0'];
if($ac['bk'] == "")
{
echo '<script language=\'javascript\'>alert(\'修改板块错误，该板块未创建！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
$DB->update('bankuai',array('bk'=>$bkname),"WHERE id = '$bkid'");
echo '<script language=\'javascript\'>alert(\'修改成功！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}
}
}
}

/* 删除板块 */
if($my == 'sc')
{
if($bkid == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
echo '
<div class="d h">
是否删除板块? <font color="red">该操作会删除板块下的所有帖子!</font>
<form>
<input type="hidden" name="act" value="admin"/>
<input type="hidden" name="my" value="scbk"/>
<input type="hidden" name="bkid" value="'.$bkid.'"/>
<input type="submit" name="sub1" value="是"/>
</form>

<form>
<input type="hidden" name="act" value="admin"/>
<input type="hidden" name="my" value="bk"/>
<input type="submit" name="sub1" value="否"/>
</form>
</div>
';
}
}

if($my == "scbk")
{
if($bkid == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
if(!preg_match("/^[0-9]\d{0,99}$/",$bkid)) {
echo '<script language=\'javascript\'>alert(\'请输入正确的板块ID！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
$ac=$DB->select('*','bankuai',"WHERE id='$bkid'")->fetchALL();
$ac=$ac['0'];
if($ac['bk'] == "")
{
echo '<script language=\'javascript\'>alert(\'板块删除失败！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}else{
$DB->delete('bankuai',"WHERE id='$bkid'");//删除板块
$DB->delete('tiezi',"WHERE bk='$bkid'");//删除板块下的帖子
echo '<script language=\'javascript\'>alert(\'板块删除成功！\'); window.location.href="http://'.$ym.'/?act=admin&my=bk";</script>';
}
}
}
}

/*论坛设置*/
if($my == "set")
{
echo '
<div class="d h">
<form>
<input type="hidden" name="act" value="admin"/>
<input type="hidden" name="my" value="setxg"/>
<input type="hidden" name="setsqldb" value="'.$sqlitedb.'"/>
论坛名称：<br>
<input type="text" name="lttitle"  class="q" value="'.$title.'"/>
论坛注册开关：1 开 0 关<br/>
<input type="text" name="zcsz"  class="q" value="'.$zckg.'"/>
论坛关闭注册后的提示语：<br/>
<input type="text" name="zctssz"  class="q" value="'.$zcts.'"/>
论坛访问开关：1 开 0 关<br/>
<input type="text" name="fwsz" class="q" value="'.$fwkg.'"/>
论坛关闭访问后的提示语：<br/>
<input type="text" name="fwtssz" class="q" value="'.$fwts.'"/>
论坛公告 开关：1 开 0 关<br/>
<input type="text" name="ltggkg" class="q" value="'.$ltggkg.'"/>
论坛公告设置：<br/>
<input type="text" name="ltggsz" class="q" value="'.$ltgg.'"/>
<input type="submit" name="sub1" value="修改"/></form>
</div>
';
}
if($my == "setxg")
{
$setsqldb=$_GET['setsqldb'];
$lttitle=$_GET['lttitle'];
$zcsz=$_GET['zcsz'];
$zctssz=$_GET['zctssz'];
$fwsz=$_GET['fwsz'];
$fwtssz=$_GET['fwtssz'];
$ltggkg1=$_GET['ltggkg'];
$ltggsz=$_GET['ltggsz'];
$connfile = '<?php
$title="'.$lttitle.'";
$sqlitedb="'.$setsqldb.'";
$zckg="'.$zcsz.'";
$zcts="'.$zctssz.'";
$fwkg="'.$fwsz.'";
$fwts="'.$fwtssz.'";
$ltggkg="'.$ltggkg1.'";
$ltgg="'.$ltggsz.'";
?>';
$fp = fopen("config.php", "w");
fwrite($fp, $connfile);
fclose($fp);
echo '<script language=\'javascript\'>alert(\'设置保存成功！\'); window.location.href="http://'.$ym.'/?act=admin&my=set";</script>';
}

include('foot.php');
}elseif($row['admin'] == "" or $row['admin'] == "no")
{
echo '<script language=\'javascript\'>alert(\'施工重地，闲人免进！\'); window.location.href="http://'.$ym.'/";</script>';
}
}


/* 退出 */
if($act == 'exit')
{
setcookie("user", "", time() - 2592000);
setcookie("pass", "", time() - 2592000);
echo '<script language=\'javascript\'>alert(\'退出成功!\'); window.location.href="http://'.$ym.'/";</script>';
}

/* 登录 */
if($act == 'login')
{
$num1=$DB->select('count(*)','user')->fetchALL(PDO::FETCH_COLUMN);
$num1=$num1[0];
echo $ha;
echo  '
<a name="top"></a><title>登录 - '.$title.'</title>
<div class="e">
<form action="?act=login&my=login" method="POST">
用户名:<br>
<input type="text" name="user1" value=""><br>
密码:<br>
<input type="password" name="pass1" value=""><br>
<input type="submit"value="马上登录"> </form>
<a href="?act=reg">免费注册</a><br><br>
本站共有 <font color=red>'.$num1.'</font> 位注册会员
</div>
';
include('foot.php');

if($my == "login")
{
if($user1 == "" or $pass1 == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}else{
$row=$DB->select('*','user',"WHERE user='$user1'")->fetchALL();
$row=$row['0'];
if($user1<>$row{'user'})
{
echo '<script language=\'javascript\'>alert(\'该账号不存在，请先注册！\'); window.location.href="http://'.$ym.'/?act=reg";</script>';
}else{
if($pass1<>$row{'pass'})
{
echo '<script language=\'javascript\'>alert(\'密码错误！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}else{
$cok=3600*24*30;
setcookie("user", $user1, time() + $cok);
setcookie("pass", $pass1, time() + $cok);
echo '<script language=\'javascript\'>alert(\'登录成功！\'); window.location.href="http://'.$ym.'/";</script>';
}
}
}
}
}

/* 注册 */
if($act == 'reg')
{
$num=$DB->select('count(*)','user')->fetchALL(PDO::FETCH_COLUMN);
$num=$num[0];
echo $ha;
if($zckg == "1")
{
echo '<div class="d h">'.$zcts.'</div>';
}else{
echo  '
<a name="top"></a><title>免费注册 - '.$title.'</title>
<div class="e">
<form action="?act=reg&my=reg" method="POST">用户名:<br>
<input type="text" name="user1" value=""><br>
密码:<br><input type="password" name="pass1" value=""><br>
重复密码:<br><input type="password" name="pass2" value=""><br>
<input type="submit"value="马上注册"></form><br>
本站共有 <font color=red>'.$num.'</font> 位注册会员
</div>
';
}
include('foot.php');

if($my == 'reg')
{
if($user1 == "" or $pass1 == "" or $pass2 == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=reg";</script>';
}else{
if($pass1 != $pass2)
{
echo '<script language=\'javascript\'>alert(\'您的两次输密码不相同哦！\'); window.location.href="http://'.$ym.'/?act=reg";</script>';
}else{
$pdcd=strlen($user1);
if($pdcd < "4")
{
echo '<script language=\'javascript\'>alert(\'用户名长度必须大于4！\'); window.location.href="http://'.$ym.'/?act=reg";</script>';
}else{
$row=$DB->select('*','user',"WHERE user='$user1'")->fetchALL();
$row=$row['0'];
if(is_array($row))
{
echo '<script language=\'javascript\'>alert(\'该用户名已注册，请重新注册！\'); window.location.href="http://'.$ym.'/?act=reg";</script>';
}else{
$user1=htmlspecialchars($user1);
$pass1=htmlspecialchars($pass1);
$DB->insert('user', array('user'=>$user1,'pass'=>$pass1,'rmb'=>'1000','vip'=>'no','qq'=>'该用户还没留下QQ','tel'=>'该用户还没设置电话号','sj'=>$date,'qianming'=>'该用户还没设置签名','zhfwsj'=>$date,'admin'=>'no'));
$cok=3600*24*30;
setcookie("user", $user1, time() + $cok);
setcookie("pass", $pass1, time() + $cok);
echo '<script language=\'javascript\'>alert(\'注册成功！\'); window.location.href="http://'.$ym.'/";</script>';
}
}
}
}
}
}

/* 聊天 */
if($act == "liao")
{
$ly=$_SERVER['HTTP_REFERER'];
echo $ha;
echo '
<a name="top"></a><title>聊天 - '.$title.'</title>
<div class="tops mar">
<a href="/"><div class="tops1">返回首页</div></a>
<a href="?act=liao"><div class="tops1">刷新</div></a>
</div>
';

$pagesize = 10;
$cou=$DB->select('count(*)','liaot')->fetch(PDO::FETCH_COLUMN);
$numrows = $cou;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize) {
$pages++;
}
if (isset($_GET['page'])) {
$page = intval($_GET['page']);
} else {
$page = 1;
}
$offset = $pagesize * ($page - 1);
$result=$DB->select('*','liaot','order by `id` desc limit ?,?', $offset,$pagesize)->fetchAll();
if($result){
foreach($result as $row){
$row11111=$DB->select('*','touxiang','WHERE user=?',$row['user'])->fetchALL();
$row11111=$row11111[0];
if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}
echo '<a href="?act=user&id='.$row['user'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div></a>
';
echo '<div class="boxx">'.bbs_ubb($row['nr']).'</div><div class="boxxs"><div class="boxxs1">'.$row['user'].'</div><div class="boxxs1">'.$row['sj'].'</div></div>';
}
echo $liaot;
echo '<div class="page">';
$prev = $page - 1;//上一页
if($prev <= "0")
{
$prev='1';
}
$next = $page + 1;//下一页
echo '<a href="?act=liao&page='.$prev.'"><div class="page1">上一页</div></a>';
for ($i = 1; $i < $page; $i++)
echo "<a href='?act=liao&page=".$i."'><div class='page3'>".$i."</div></a> ";
echo "<a href='?act=liao&page=".$page."'><div class='page3' style='background: #D5D5D5;'>" . $page . "</div></a> ";
if($next > $pages)
{
$next=$i;
}
for ($i = $page + 1; $i <= $pages; $i++)
echo "<a href='?act=liao&page=".$i."'><div class='page3'>" . $i . "</div></a> ";
echo '<a href="?act=liao&page='.$next.'"><div class="page1">下一页</div></a>';
echo '</div>';
}else
{
echo '<div class="d h">聊天室目前还没有人聊天哦！</div>';
echo $liaot;
}
include('foot.php');
}

if($act == 'liaoadd')
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请登录后在操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes")
{
if($ltnr != '')
{
$sj=$date;
$ltnr=htmlspecialchars($ltnr);
$DB->insert('liaot',array('user'=>$user,'sj'=>$date,'nr'=>$ltnr,'to'=>''));
echo '<script language=\'javascript\'>alert(\'发送成功！\'); window.location.href="http://'.$ym.'/?act=liao";</script>';
}else{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=liao";</script>';
}
}
}

/* 用户中心 */
if($act == "user")
{
$row=$DB->select('*','user',"WHERE user='$id'")->fetchALL();
$row=$row['0'];

if($row['user'] == "")
{
echo '<script language=\'javascript\'>alert(\'该账号不存在！\'); window.location.href="http://'.$ym.'/";</script>';
}else{
$row1=$DB->select('*','touxiang','WHERE user=?',$id)->fetchALL();
$row1=$row1[0];
if($row1['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row1['txurl'];
}

if($row['admin'] == "yes")
{
$shenfen='<font color="red">(管理员)</font>';
}else{
$shenfen1=$DB->select('*','bankuai',"where banzhu like '%".$row['user']."%'")->fetchALL();
if($shenfen1['0'] == "")
{
$shenfen='';
}else{
$shenfen='<font color="red">(版主)</font>';
}
}

echo $ha;
echo '<a name="top"></a><title>用户 - '.$title.'</title>
<a name="top"></a>';
echo '
<div class="boxxer boxx"><img src="'.$uurl.'" alt="头像"/></div>
<div class="boxxs"><div class="boxxs1">'.$id.$shenfen.'</div>
<a href="?act=user&id='.$id.'"><div class="boxxs1">刷新</div></a>
';
if($id == $user)
{
echo $userset;
$yhshoucang='<a href="?act=lookshoucang">查看我收藏的帖子</a>';
}
echo '</div>';
echo '<div class="d h">
个人资料<br>
用户ID：'.$row{'id'}.'<br>
注册时间：'.$row{'sj'}.'<br>
QQ号码：'.$row{'qq'}.'<br>
电话号码：'.$row{'tel'}.'<br>
个性签名：'.$row{'qianming'}.'<br>
最后访问时间：'.$row{'zhfwsj'}.'<br>
</div>';
echo '<div class="d h"><a href="?act=usertz&id='.$row{'id'}.'">查看Ta的贴子</a> '.$yhshoucang.' </div>';
echo $faneixin;
echo '<div class="bc">最近动态：</div>';
$a=$DB->select('*','tiezi',' where user = ? order by id desc limit 0,6',$id)->fetchALL();
if($a){
foreach($a as $row){
echo '<a href="?act=cktz&tzid='.$row['Id'].'&bkid='.$row['bk'].'"><div class="i">'.$row['bt'].'</div></a>';
}
}else{
echo '<div class="bc">该用户没有最进动态！</div>';
}

include('foot.php');
}
}

/* 查看用户的所有帖子 */
if($act == "usertz")
{
$row=$DB->select('*','user',"WHERE id='$id'")->fetchALL();
$row=$row['0'];
echo $ha;
echo '<a name="top"></a><title>'.$row['user'].'的帖子 - '.$title.'</title>
<a name="top"></a>
<div class="tops mar">
<a href="?act=user&id='.$row['user'].'"><div class="tops1">返回</div></a>
<a href="?act=usertz&id='.$row['id'].'"><div class="tops1">刷新</div></a>
</div>
';
echo '<div class="bc">这里的帖子都是'.$row['user'].'的，而且是按最后回复的时间排序的</div>';
$cou=$DB->select('count(*)','tiezi')->fetch(PDO::FETCH_COLUMN);
$size=10;
$nv=ceil($cou/$size);
$page=(int)$_GET['page'];
$p=empty($page)?'1':$page;
$p=$p>$nv?'1':$p;
$start=$size*($p-1);
$result=$DB->select('*','tiezi','where user = ? order by `hfsj` desc limit ?,?',$row['user'],$start,$size)->fetchAll();
if($result){
foreach($result as $row1){
echo '<a href="?act=cktz&tzid='.$row1['Id'].'&bkid='.$row1['bk'].'"><div class="i">'.$row1['bt'].'</div></a>';
}
echo '<div class="page">';
if($p>1){
echo'<a href="?act=usertz&id='.$row['id'].'&page='.($p-1).'"><div class="page1">上一页</a></div>';
}
if($p<$nv&&$nv>1){
echo'<a href="?act=usertz&id='.$row['id'].'&page='.($p+1).'"><div class="page1">下一页</a></div>';
}
echo '</div>';
}else
{
echo '<div class="d h">该用户目前还没有发过帖子哦！</div>';
}
include('foot.php');
}

/* 查看我收藏的帖子 */
if($act == "lookshoucang")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
echo $ha;
echo '<a name="top"></a><title>'.$row['user'].'的帖子收藏 - '.$title.'</title>
<a name="top"></a>
<div class="tops mar">
<a href="?act=user&id='.$row['user'].'"><div class="tops1">返回</div></a>
<a href="?act=lookshoucang"><div class="tops1">刷新</div></a>
</div>
';
echo '<div class="bc">这里的帖子都是你收藏的</div>';
$cou=$DB->select('count(*)','shoucangtz')->fetch(PDO::FETCH_COLUMN);
$size=10;
$nv=ceil($cou/$size);
$page=(int)$_GET['page'];
$p=empty($page)?'1':$page;
$p=$p>$nv?'1':$p;
$start=$size*($p-1);
$result=$DB->select('*','shoucangtz','where user = ? order by `id` desc limit ?,?',$row['user'],$start,$size)->fetchAll();
if($result){
echo '<div class="d h">';
foreach($result as $row1){
$tzbt=$DB->select('*','tiezi','where Id = ?',$row1['tzid'])->fetchALL();
$tzbt=$tzbt['0'];
$tzbt=$tzbt['bt'];
echo '<a href="?act=cktz&tzid='.$row1['tzid'].'&bkid='.$row1['bkid'].'">'.$tzbt.'</a> <a href="?act=scshoucang&id='.$row1['id'].'">删除收藏</a><br><br>';
}
echo '</div>';
echo '<div class="page">';
if($p>1){
echo'<a href="?act=lookshoucang'.'&page='.($p-1).'"><div class="page1">上一页</a></div>';
}
if($p<$nv&&$nv>1){
echo'<a href="?act=lookshoucang'.'&page='.($p+1).'"><div class="page1">下一页</a></div>';
}
echo '</div>';
}else
{
echo '<div class="d h">您目前还没有收藏过帖子哦！</div>';
}
include('foot.php');
}
}

/* 删除帖子收藏 */
if($act == "scshoucang")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($id ==  "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=lookshoucang";</script>';
}else{
$a=$DB->select('*','shoucangtz','where id = ?',$id)->fetchALL();
$a=$a['0'];
if($a['user'] == $user)
{
$DB->delete('shoucangtz','where id = ?',$id);
echo '<script language=\'javascript\'>alert(\'删除成功！\'); window.location.href="http://'.$ym.'/?act=lookshoucang";</script>';
}else{
echo '<script language=\'javascript\'>alert(\'删除失败，这个收藏记录不是你的！\'); window.location.href="http://'.$ym.'/?act=lookshoucang";</script>';
}
}
}
}

/* 用户设置 */
if($act == "userset")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];

$qqhm=$_POST['qqhm'];
$dhhm=$_POST['dhhm'];
$gxqm=$_POST['gxqm'];
echo $ha;
echo '<a name="top"></a><title>用户设置 - '.$title.'</title>
<a name="top"></a>';
echo '<div class="d h">
个人资料设置<br>
<form action="?act=userset&my=bc" method="post">
QQ号码：<br><input type="text" name="qqhm" maxlength="10" class="q" value="'.$row{'qq'}.'"/><br/>
电话号码：<br><input type="text" name="dhhm" maxlength="11" class="q" value="'.$row{'tel'}.'"/><br/>
个性签名：<br><input type="text" name="gxqm" maxlength="140" class="q" value="'.$row{'qianming'}.'"/><br/>
<input type="submit" name="sub1" value="保存"/></form>
<form>
<input type="hidden" name="act" value="user"/>
<input type="hidden" name="id" value="'.$user.'"/>
<input type="submit"  value="返回"/>
</form>
</div>';

include('foot.php');

/*修改资料 */
if($my == "bc")
{
if($qqhm == "" or $dhhm == "" or $gxqm == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=userset&id='.$id.'";</script>';
}else{
$qqhm=htmlspecialchars($qqhm);
$dhhm=htmlspecialchars($dhhm);
$gxqm=htmlspecialchars($gxqm);
$DB->update('user',array('qq'=>$qqhm,'tel'=>$dhhm,'qianming'=>$gxqm),"WHERE user = '$user'");
echo '<script language=\'javascript\'>alert(\'修改成功！\'); window.location.href="http://'.$ym.'/?act=userset";</script>';
}
}
}
}

/* 设置头像 */
if($act == "ghtx")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
echo $ha;
echo '<a name="top"></a><title>头像设置 - '.$title.'</title>
<a name="top"></a>';
echo '
<div class="e">
<form action="?act=ghtx&my=tx&id='.$user.'" method="post" enctype="multipart/form-data">
更换头像:<br>
<input type="hidden" name="num" value="1"/>
<label for="file"></label>
<input type="file" name="file" id="file" /> 
<br/>
<input type="submit" name="sub" value="设置"/>
</form>
</div>
';
include('foot.php');


if($my == 'tx')
{
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/pjpeg"))
&& ($_FILES["file"]["size"] < 2000000))
{
$filetmpname=$_FILES["file"]["tmp_name"];
$filename=$_FILES["file"]["name"];
$filename=str_replace('_','',$filename);
$filename=explode('.',$filename);
$filename=$filename[1];
$id1=md5($id);
$filename=$id1.'.'.$filename;
if ($_FILES["file"]["error"] > 0)
{
echo '<script language=\'javascript\'>alert(\'未知错误！\'); window.location.href="'.$ly.'";</script>';
}else
{
move_uploaded_file($filetmpname,"upload/touxiang/".$filename);
$txurl="upload/touxiang/".$filename;

$img=new image;//实例化类文件，
$simg=$img->resize('./'.$txurl,'upload','200','200');
copy($simg,$txurl);
unlink($simg); 
$row=$DB->select('*','touxiang',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
if($row['user'] == "")
{
$DB->insert('touxiang',array('user'=>$user,'txurl'=>$txurl));
echo '<script language=\'javascript\'>alert(\'头像修改成功！\'); window.location.href="http://'.$ym.'/?act=user&id='.$id.'";</script>';
}

if($row['user'] == $id)
{
$DB->update('touxiang',array('txurl'=>$txurl),"where user='$user'");
echo '<script language=\'javascript\'>alert(\'头像修改成功！\'); window.location.href="http://'.$ym.'/?act=user&id='.$id.'";</script>';
}
}
}else{
echo '<script language=\'javascript\'>alert(\'不是图片文件,或者文件太大,请重新上传！\'); window.location.href="'.$ly.'";</script>';
}
}
}
}

/* 修改密码*/
if($act == "xgmm")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
echo $ha;
echo '<a name="top"></a><title>修改密码 - '.$title.'</title>
<a name="top"></a>';
echo '<div class="d h">
修改密码<br><br>
<form action="?act=xgmm&my=xg" method="post">
原密码：<br><input type="text" name="ymm" maxlength="32" class="q" value=""/><br/>
新密码：<br><input type="text" name="xmm" maxlength="32" class="q" value=""/><br/>
再次输入新密码：<br><input type="text" name="zcsrxmm" maxlength="32" class="q" value=""/><br/>
<input type="submit" name="sub1" value="保存"/><br>
</div>';

include('foot.php');

if($my == "xg")
{
if($ymm == "" or $xmm == "" or $zcsrxmm == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=xgmm";</script>';
}else{
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
if($ymm != $row{'pass'})
{
echo '<script language=\'javascript\'>alert(\'原密码不正确！\'); window.location.href="http://'.$ym.'/?act=xgmm";</script>';
}else{
if($xmm != $zcsrxmm)
{
echo '<script language=\'javascript\'>alert(\'密码输入不相同，请重新输入！\'); window.location.href="http://'.$ym.'/?act=xgmm";</script>';
}else{
$DB->update('user',array('pass'=>$xmm),"where user='$user'");
echo '<script language=\'javascript\'>alert(\'密码修改成功！请重新登录！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
setcookie("user", "", time() - 2592000);
setcookie("pass", "", time() - 2592000);
}
}
}
}
}
}

/* 信箱 */
if($act == "xin")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
echo $ha;
echo '
<a name="top"></a><title>内信 - '.$title.'</title>
<div class="tops mar">
<a href="?act=xin"><div class="tops1">返回</div></a>
<a href="?act=xin"><div class="tops1">刷新</div></a>
</div>
';
echo '<div class= "foot" > <!-- <a href="?act=xin&my=qcxin&id='.$user.'"><div class="foot1">清空发信箱记录</div></a> --!> <a href="?act=fsxin"><div class="foot1">发内信</div></a> <a href="?act=xin"><div class="foot1">刷新</div></a> </div>';

$pagesize = 10;
$cou=$DB->select('count(*)','neixin')->fetch(PDO::FETCH_COLUMN);
$numrows = $cou;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize) {
$pages++;
}
if (isset($_GET['page'])) {
$page = intval($_GET['page']);
} else {
$page = 1;
}
$offset = $pagesize * ($page - 1);
$result=$DB->select('*','neixin','WHERE `to` = ? order by `id` desc limit ?,?',$user,$offset,$pagesize)->fetchAll();
if($result){
foreach($result as $row){
echo "<div class='d h'>";
if ($row['kan'] == 0) {
echo '[新]';
}
$DB->update('neixin',array('kan'=>'1'),"where id='$row[id]'");
if ($row['user'] == $user) {
echo '<a href="?act=hfxin&to=' . $row['user'] . '">我</a>';
} else {
echo '<a href="?act=hfxin&to=' . $row['user'] . '">' . $row['user'] . '</a>';
}
$n = bbs_ubb($row['nr']);
echo '说:' . $n . '(' . $row['sj'] . ') ';
echo '<a href="?act=hfxin&to=' . $row['user'] . '">回复</a>|<a href="?act=xin&my=scxin&id='.$row['id'].'">删除</a>';
echo '</div>';
}
echo '<div class="page">';
$prev = $page - 1;//上一页
if($prev <= "0")
{
$prev='1';
}
$next = $page + 1;//下一页
echo '<a href="?act=liao&page='.$prev.'"><div class="page1">上一页</div></a>';
for ($i = 1; $i < $page; $i++)
echo "<a href='?act=liao&page=".$i."'><div class='page3'>".$i."</div></a> ";
echo "<a href='?act=liao&page=".$page."'><div class='page3' style='background: #D5D5D5;'>" . $page . "</div></a> ";
if($next > $pages)
{
$next=$i;
}
for ($i = $page + 1; $i <= $pages; $i++)
echo "<a href='?act=liao&page=".$i."'><div class='page3'>" . $i . "</div></a> ";
echo '<a href="?act=liao&page='.$next.'"><div class="page1">下一页</div></a>';
echo '</div>';
}else
{
echo '<div class="d h">您的信箱目前没有邮件哦！</div>';
}

include('foot.php');

/* 清空发信箱记录 */
/*
if($my == "qcxin")
{
$DB->delete('neixin',"WHERE user='$id'");
echo '<script language=\'javascript\'>alert(\'清空发信箱记录成功！\'); window.location.href="http://'.$ym.'/?act=xin";</script>';
}
*/


/* 删除内信记录 */
if($my == "scxin")
{
$DB->delete('neixin',"WHERE id='$id'");
echo '<script language=\'javascript\'>alert(\'删除成功！\'); window.location.href="http://'.$ym.'/?act=xin";</script>';
}

/* 发送内信 */
if($my == "fa")
{
if($co == "" or $hid == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="'.$ly.'";</script>';
}else{
$aa=$DB->select('*','user',"WHERE user='$hid'")->fetchALL();
$aa=$aa['0'];
if($aa[user] == '')
{
echo '<script language=\'javascript\'>alert(\'目前还没找到该用户！\'); window.location.href="'.$ly.'";</script>';
}else{
if($user == $hid)
{
echo '<script language=\'javascript\'>alert(\'你不可以自言自语哦！\'); window.location.href="'.$ly.'";</script>';
}else{
$sj=$date;
$co=htmlspecialchars($co);
$DB->insert('neixin',array('kan'=>'0','user'=>$user,'sj'=>$date,'nr'=>$co,'to'=>$hid));
echo '<script language=\'javascript\'>alert(\'内信发送成功！\'); window.location.href="http://'.$ym.'/?act=xin";</script>';
}
}
}
}
}
}

/* 发送内信页面 */
if($act == "fsxin")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$to'")->fetchALL();
$row=$row['0'];
if($to<>$row[user])
{
echo '<script language=\'javascript\'>alert(\'用户不存在！\'); window.location.href="'.$ly.'";</script>';
}else{
echo $ha;
echo '
<a name="top"></a><title>内信 - '.$title.'</title>
<div class="tops mar">
<a href="?act=xin"><div class="tops1">返回</div></a>
<a href="?act=fsxin"><div class="tops1">刷新</div></a>
</div>
';
echo "<div class='d h'>";
echo '<form action="?act=fsxin&my=fs" method="post">收件人：<br>
<input type="text" name="to1" value=""><br>内容：<br>';
echo '<textarea name="sql" rows="2" cols="35">
</textarea><br>
<input type="submit" value="确认发送"></form>
</div>';
include('foot.php');
}

if($my == "fs")
{
if($sql == "" or $to1 == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="'.$ly.'";</script>';
}else{
$aa=$DB->select('*','user',"WHERE user='$to1'")->fetchALL();
$aa=$aa['0'];
if($aa[user] == '')
{
echo '<script language=\'javascript\'>alert(\'目前还没找到该用户！\'); window.location.href="'.$ly.'";</script>';
}else{
if($user == $to1)
{
echo '<script language=\'javascript\'>alert(\'你不可以自言自语哦！\'); window.location.href="'.$ly.'";</script>';
}else{
$sj=$date;
$sql=htmlspecialchars($sql);
$DB->insert('neixin',array('kan'=>'0','user'=>$user,'sj'=>$date,'nr'=>$sql,'to'=>$to1));
echo '<script language=\'javascript\'>alert(\'内信发送成功！\'); window.location.href="http://'.$ym.'/?act=xin";</script>';
}
}
}
}
}
}

/* 回复内信页面 */
if($act == "hfxin")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$to'")->fetchALL();
$row=$row['0'];
if($to<>$row[user])
{
echo '<script language=\'javascript\'>alert(\'用户不存在！\'); window.location.href="'.$ly.'";</script>';
}else{
echo $ha;
echo '
<a name="top"></a><title>内信 - '.$title.'</title>
<div class="tops mar">
<a href="?act=xin"><div class="tops1">返回</div></a>
<a href="?act=hfxin&to='.$to.'"><div class="tops1">刷新</div></a>
</div>
';
echo "<div class='d h'>";
echo ' <form action="?act=hfxin&my=fs" method="post">
<input type="hidden" name="to2" value="'.$to.'"> 与('.$to.')对话： <a href="?act=hfxin&to='.$to.'">刷新</a> <br>';
echo '<textarea name="sql" rows="2" cols="35">
</textarea><br>
<input type="submit" value="确认发送"></form>
</div>';
include('foot.php');
}

if($my == "fs")
{
if($sql == "" or $to2 == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="'.$ly.'";</script>';
}else{
$row=$DB->select('*','user',"WHERE user='$to2'")->fetchALL();
$row=$row['0'];
if($aa[user] == '')
{
echo '<script language=\'javascript\'>alert(\'目前还没找到该用户！\'); window.location.href="'.$ly.'";</script>';
}else{
if($user == $to2)
{
echo '<script language=\'javascript\'>alert(\'你不可以自言自语哦！\'); window.location.href="'.$ly.'";</script>';
}else{
$sj=$date;
$sql=htmlspecialchars($sql);
$DB->insert('neixin',array('kan'=>'0','user'=>$user,'sj'=>$date,'nr'=>$sql,'to'=>$to2));
echo '<script language=\'javascript\'>alert(\'内信发送成功！\'); window.location.href="http://'.$ym.'/?act=xin";</script>';
}
}
}
}
}
}

/* 游戏大厅 */
if($act == "youxi")
{
echo $ha;
echo '
<a name="top"></a><title>游戏大厅 - '.$title.'</title>
';
if(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
echo '<div class="d h">用户信息 <a href="?act=youxiqd">签到</a>  <a href="?act=youxizz">转账</a> </div><div class="d h">';
echo '用户名：'.$row['user'];
echo '<br>你的ID：'.$row{'id'};
echo '<br>你的金币：'.$row{'rmb'}.'</div>';
}
echo '<div class="d h">游戏列表</div><div class="d h">';
echo '<img src="/theme/img/st.gif"><a href="?act=caiquan">疯狂石头</a><br>';
echo '游戏规则：<br>1.金币必须大于0才可以玩<br>2.如果您赢了，那么系统将还您2倍赌注</div>';
echo '<div class="d h"><img src="/theme/img/ma2.gif"><a href="?act=saima">赛马场</a><br>1.每隔5分钟开赛<br>
2.每期不限次数<br>
3.每期不限投金币数量</div>';
echo '<div class="d h"><img src="/theme/img/sz.gif"><a href="?act=shaizi">激情骰子</a><br>1.每隔5分钟开赛<br>
2.每期不限次数<br>
3.每期不限投金币数量<br>
小:4,5,6,7,8,9,10<br>
大:11,12,13,14,15,16,17<br>
单:5,7,9,11,13,15,17<br>
双:4,6,8,10,12,14,16<br>
豹子:三个骰子点数相同</div>';
/*
echo '
<div class="d h"><img src="/theme/img/qihuo.png"><a href="?act=qihuo">超级期货</a>
<br>1.每1小时股市就会进行调价<br>2.投资有风险,入市请谨慎</div>
';*/
echo '
<div class="d h"><img src="/theme/img/dou.jpg"><a href="?act=sanjiedou">三界斗</a><br>
游戏说明：<br>
1.每次战事为10分钟。<br>
2.您可以选择向自己喜欢的势力支援军资进行征兵，以助其获取最终战争胜利。<br>
3.您所资源的势力如果获取了战争胜利，作为战胜方，您可以获得支援军资的2.9倍战利品。<br>
4.申明：比赛结果会受天时，地利，人和影响，不保证任何势力肯定获胜或者失败。</div>
';
include('foot.php');
}

/* 游戏大厅签到 */
if($act == "youxiqd")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
date_default_timezone_set("PRC");
$time = date("Y-m-j");
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
$money=$row['rmb'] + 100;
$a=$DB->select('*','qd',"WHERE user='$user'")->fetchALL();
$a=$a['0'];
$qdtime=$a['time'];//取签到时间
$qdtime1=strtotime($qdtime);//取时间戳
$time1=strtotime($time);//取时间戳
$atime=$qdtime1-$time1;//用签到的时间减去现行时间
if($qdtime == "")
{
$DB->insert('qd',array('time'=>$time,'user'=>$user));//添加签到
$DB->update('user',array('rmb'=>$money),"WHERE user = '$user'");//添加金币
echo '<script language=\'javascript\'>alert(\'签到成功！金币增加100！\'); window.location.href="http://'.$ym.'/?act=youxi";</script>';
}elseif($atime < 0)
{
$DB->update('qd',array('time'=>$time),"WHERE user = '$user'");//添加签到
$DB->update('user',array('rmb'=>$money),"WHERE user = '$user'");//添加金币
echo '<script language=\'javascript\'>alert(\'签到成功！金币增加100！\'); window.location.href="http://'.$ym.'/?act=youxi";</script>';
}else{
echo '<script language=\'javascript\'>alert(\'您今天已经签到！\'); window.location.href="http://'.$ym.'/?act=youxi";</script>';
}
}
}

/* 游戏金币转账 */
if($act == "youxizz")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
$money=$row['rmb'];
echo $ha;
echo '
<a name="top"></a><title>转账 - '.$title.'</title>
';
echo '<div class="d h"><a href="?act=youxizz">转账</a> <a href="?act=youxi">游戏大厅</a></div><div class="d h">';
echo '用户名：'.$user;
echo '<br>你的ID：'.$row{'id'};
echo '<br>你的金币：'.$money.'</div>';
echo '<div class="d h">
<form name="form" action="?act=youxizz&my=c" method="post">
转账：<br />
用户名:(这里填写对方的用户名)<br /><input name="yhm" value="" /><br />
金额:<br /><input name="jiner" value="" /><br />
<input type="submit" value="确认"/>
</form></div>';

if($my == "c")
{
if($yhm == "" or $jiner == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="http://'.$ym.'/?act=youxizz";</script>';
}else{
if(!preg_match("/^[0-9]\d{0,99}$/",$jiner)) {
echo '<script language=\'javascript\'>alert(\'请输入正确的金额！\'); window.location.href="http://'.$ym.'/?act=youxizz";</script>';
}else{
$row000=$DB->select('*','user',"WHERE user='$yhm'")->fetchALL();
$row000=$row000['0'];
if($yhm<>$row000{'user'})
{
echo '<script language=\'javascript\'>alert(\'转账的用户名不存在！\'); window.location.href="http://'.$ym.'/?act=youxizz";</script>';
}elseif($yhm == $user)
{
echo '<script language=\'javascript\'>alert(\'不能转账给自己！\'); window.location.href="http://'.$ym.'/?act=youxizz";</script>';
}elseif($jiner > $money)
{
echo '<script language=\'javascript\'>alert(\'您没有这么多金币,无法转账！\'); window.location.href="http://'.$ym.'/?act=youxizz";</script>';
}else{
/* 扣除用户帐号金币 */
$money=$money-$jiner;
$DB->update('user',array('rmb'=>$money),"WHERE user = '$user'");

/* 给用户添加金币 */
$jinbi=$row000{'rmb'} + $jiner;
$DB->update('user',array('rmb'=>$jinbi),"WHERE user = '$yhm'");//添加金币
echo '<script language=\'javascript\'>alert(\'转账成功！\'); window.location.href="http://'.$ym.'/?act=youxizz";</script>';
}
}
}
}
include('foot.php');
}
}

/* 猜拳游戏 */
if($act == "caiquan")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
$money=$row['rmb'];
echo $ha;
echo '
<a name="top"></a><title>疯狂石头 - '.$title.'</title>
';
echo '<div class="tops mar">
<a href="?act=youxi"><div class="tops1">返回</div></a>
<a href="?act=caiquan"><div class="tops1">刷新</div></a>
</div>';
echo '
<div class="d h">您当前的金币：'.$money.'</div>
<div class="d h">
<form name="form" action="?act=caiquan&my=c" method="post">
您出:<br />
<select name="caiquan">
<option value="石头">石头</option>
<option value="剪刀">剪刀</option>
<option value="布">布</option>
</select><br />
赌注:<br /><input name="yajin" value="10" /><br />
<input type="submit" value="开始猜拳"/>
</form></div>
';


if($my == "c")
{
if(!preg_match("/^[0-9]\d{0,99}$/",$yajin)) {

}else{
if($yajin > $money)
{
echo '<script language=\'javascript\'>alert(\'您输入的赌注大于您的金币，请重新输入赌注！\'); window.location.href="http://'.$ym.'/?act=caiquan";</script>';
}else{
if($money <= 0)
{
echo '<script language=\'javascript\'>alert(\'金币必须大于0才可以玩！\'); window.location.href="http://'.$ym.'/?act=caiquan";</script>';
}else{

$result=caiquan($caiquan);
echo<<<HTML
<div class="d h">
你出拳: {$result['你']}<br/>
系统出拳: {$result['系统']}<br/>
胜负: {$result['结果']}<br/>
<a href="?act=caiquan">点击这里重新开始</a>
</div>
HTML;
echo '<br>';
if($result['结果'] == "赢")
{
$jinbi=$money + $yajin * 2;
}else{
if($result['结果'] == "输")
{
$jinbi=$money - $yajin;
}else{
$jinbi=$money;
}
}
$DB->update('user',array('rmb'=>$jinbi),"WHERE user = '$user'");
}
}
}
}
include('foot.php');
}
}

/* 赛马游戏 */
if($act == "saima")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
$money=$row['rmb'];

$roww=$DB->select('*','time',"WHERE saimatime")->fetchALL();
$roww=$roww['0'];


$c=$roww['saimatime'];
if($c == "")
{
$r=timejian5();
$DB->insert('time',array('saimatime'=>$r,'touzitime'=>'','sanjiedoutime'=>''));
}
$r=$roww['saimatime'];//5分钟后时间
$t = date("Y-m-j H:i");//现在时间
if($t < $r)
{
$sj=strtotime($r)-strtotime($t);
$sj=$sj/60;//剩余开奖时间
}else
{
$r1=timejian5();
$DB->update('time',array('saimatime'=>$r1));
$smurl='http://'.$_SERVER['SERVER_NAME'].'/job.php?kj=saima';
openu($smurl);
}

if($sj < 0)
{
$DB->query('delete from time');
}

echo $ha;
echo '
<a name="top"></a><title>赛马场游戏 - '.$title.'</title>
';
echo '<div class="tops mar">
<a href="?act=youxi"><div class="tops1">返回</div></a>
<a href="?act=saima"><div class="tops1">刷新</div></a>
</div>';
echo '<div class="d h">您当前的金币：<font color="red">'.$money.'</font></div>';
echo '<div class="d h">'.'还有：'.$sj.' 分钟开赛！<br>'.'<img src="./theme/img/dm.gif"><br>';

$file=fopen("saimadb.txt","r");
$file=fgets($file);
$kjjg=explode('|',$file);
echo '上期开奖结果：'.$kjjg['0'].' <img src="./theme/img/ma'.$kjjg['0'].'.gif"><br>上期开奖时间：'.$kjjg['1'].'<br>';


/* 取用户马1到马6的金币 */
$row1=$DB->select('*','saima',"WHERE user='$user'")->fetchALL();
$row1=$row1['0'];


if($row1 == "")
{
$DB->insert('saima',array('ma1'=>'0','ma2'=>'0','ma3'=>'0','ma4'=>'0','ma5'=>'0','ma6'=>'0','youma'=>'0','user'=>$user));
}

$row1=$DB->select('ma1','saima',"WHERE user='$user'")->fetchALL();
$row1=$row1['0'];

$row2=$DB->select('ma2','saima',"WHERE user='$user'")->fetchALL();
$row2=$row2['0'];

$row3=$DB->select('ma3','saima',"WHERE user='$user'")->fetchALL();
$row3=$row3['0'];

$row4=$DB->select('ma4','saima',"WHERE user='$user'")->fetchALL();
$row4=$row4['0'];

$row5=$DB->select('ma5','saima',"WHERE user='$user'")->fetchALL();
$row5=$row5['0'];

$row6=$DB->select('ma6','saima',"WHERE user='$user'")->fetchALL();
$row6=$row6['0'];
$ma[1]=$row1['ma1'];
$ma[2]=$row2['ma2'];
$ma[3]=$row3['ma3'];
$ma[4]=$row4['ma4'];
$ma[5]=$row5['ma5'];
$ma[6]=$row6['ma6'];

echo '您压1号马共有:'.$ma[1].'金币<br>';
echo '您压2号马共有:'.$ma[2].'金币<br>';
echo '您压3号马共有:'.$ma[3].'金币<br>';
echo '您压4号马共有:'.$ma[4].'金币<br>';
echo '您压5号马共有:'.$ma[5].'金币<br>';
echo '您压6号马共有:'.$ma[6].'金币<br>';

echo '</div><div class="d h">
<form name="form" action="?act=saima&my=c" method="post">
您压:
<select name="saima">
<option value="1">①号马(1赔2)</option>
<option value="2">②号马(1赔2)</option>
<option value="3">③号马(1赔2)</option>
<option value="4">④号马(1赔2)</option>
<option value="5">⑤号马(1赔2)</option>
<option value="6">⑥号马(1赔2)</option>
</select>
<br />
赌注:<br /><input name="yajin" value="10" /><br />
<input type="submit" value="投注"/>
</form></div>';
if($my == "c")
{
if(!preg_match("/^[0-9]\d{0,99}$/",$yajin)) {

}else{
if($yajin > $money)
{
echo '<script language=\'javascript\'>alert(\'您输入的赌注大于您的金币，请重新输入赌注！\'); window.location.href="http://'.$ym.'/?act=saima";</script>';
}else{
if($money <= 0)
{
echo '<script language=\'javascript\'>alert(\'金币必须大于0才可以玩！\'); window.location.href="http://'.$ym.'/?act=saima";</script>';
}else{

/* 扣除金币 */
$money=$money-$yajin;
$DB->update('user',array('rmb'=>$money),"WHERE user='$user'");


/* 添加赛马押金 */
$yajin=$ma[$saima]+$yajin;
$DB->update('saima',array('ma'.$saima=>$yajin),"WHERE user = '$user'");

/* 更新押的马 */
$row111=$DB->select('*','saima',"WHERE user='$user'")->fetchALL();
$row111=$row111['0'];

$saima1=$row111['youma'].','.$saima;
$DB->update('saima',array('youma'=>$saima1),"WHERE user='$user'");
echo '<script language=\'javascript\'>alert(\'押注成功，请等待开奖！\'); window.location.href="http://'.$ym.'/?act=saima";</script>';
}
}
}
}
include('foot.php');
}
}

/* 赌骰子游戏 */
if($act == "shaizi")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
$money=$row['rmb'];
$roww=$DB->select('*','time',"WHERE touzitime")->fetchALL();
$roww=$roww['0'];
$c=$roww['touzitime'];
if($c == "")
{
$r=timejian5();
$DB->insert('time',array('saimatime'=>'','touzitime'=>$r,'sanjiedoutime'=>''));
}
$r=$roww['touzitime'];//5分钟后时间
$t = date("Y-m-j H:i");//现在时间
if($t < $r)
{
$sj=strtotime($r)-strtotime($t);
$sj=$sj/60;//剩余开奖时间
}else{
$r1=timejian5();
$DB->update('time',array('touzitime'=>$r1));
$smurl='http://'.$_SERVER['SERVER_NAME'].'/job.php?kj=touzi';
openu($smurl);
}
if($sj < 0)
{
$DB->query('delete from time');
}

echo $ha;
echo '
<a name="top"></a><title>赌骰子游戏 - '.$title.'</title>
';
echo '<div class="tops mar">
<a href="?act=youxi"><div class="tops1">返回</div></a>
<a href="?act=shaizi"><div class="tops1">刷新</div></a>
</div>';
echo '<div class="d h">您当前的金币：<font color="red">'.$money.'</font></div>';
echo '<div class="d h">'.'还有：'.$sj.' 分钟开盅！<br>各位客官赶快下啦买定离手咯！<br>';

$file=fopen("touzidb.txt","r");
$file=fgets($file);
$kjjg=explode('|',$file);

$js=explode('/',$kjjg[0]);
$js=explode(',',$js[0]);
$a=$js['0'];
$b=$js['1'];
$c=$js['2'];
echo '上期开奖结果：'.$kjjg['0'].'<br><img src="/theme/img/'.$a.'.jpg"> <img src="./theme/img/'.$b.'.jpg"> <img src="./theme/img/'.$c.'.jpg">'.'<br>上期开奖时间：'.$kjjg['1'].'<br>';

/* 取用户投注的金币 */

$row11=$DB->select('*','touzi',"WHERE user='$user'")->fetchALL();
$row11=$row11['0'];

if($row11 == "")
{
$DB->insert('touzi',array('damoney'=>'0','xiaomoney'=>'0','danmoney'=>'0','shuangmoney'=>'0','baozimoney'=>'0','dxds'=>'','user'=>$user));
}



$row1=$DB->select('*','touzi',"WHERE user='$user'")->fetchALL();
$row1=$row1['0'];
$ma['damoney']=$row1['damoney'];
$ma['xiaomoney']=$row1['xiaomoney'];
$ma['danmoney']=$row1['danmoney'];
$ma['shuangmoney']=$row1['shuangmoney'];
$ma['baozimoney']=$row1['baozimoney'];
$madxds=$row1['dxds'];

echo '您目前买大:'.$ma['damoney'].'金币<br>';
echo '您目前买小:'.$ma['xiaomoney'].'金币<br>';
echo '您目前买单:'.$ma['danmoney'].'金币<br>';
echo '您目前买双:'.$ma['shuangmoney'].'金币<br>';
echo '您目前买豹子:'.$ma['baozimoney'].'金币</div>';

echo '<div class="d h">
游戏规则：<br>
小:4,5,6,7,8,9,10<br>
大:11,12,13,14,15,16,17<br>
单:5,7,9,11,13,15,17<br>
双:4,6,8,10,12,14,16<br>
豹子:三个骰子点数相同</div>
';

echo '<div class="d h">
<form name="form" action="?act=shaizi&my=c" method="post">
您压:<br />
<select name="dxds">
<option value="damoney">大(1赔2)</option>
<option value="xiaomoney">小(1赔2)</option>
<option value="danmoney">单(1赔2)</option>
<option value="shuangmoney">双(1赔2)</option>
<option value="baozimoney">豹子(1赔10)</option>
</select>
<br />
赌注:<br /><input name="yajin" value="10" /><br />
<input type="submit" value="投注"/>
</form></div>';
if($my == "c")
{
if(!preg_match("/^[0-9]\d{0,99}$/",$yajin)) {
}else{
if($yajin > $money)
{
echo '<script language=\'javascript\'>alert(\'您输入的赌注大于您的金币，请重新输入赌注！\'); window.location.href="http://'.$ym.'/?act=shaizi";</script>';
}else{
if($money <= 0)
{
echo '<script language=\'javascript\'>alert(\'金币必须大于0才可以玩！\'); window.location.href="http://'.$ym.'/?act=shaizi";</script>';
}else{

/* 扣除金币 */
$money=$money-$yajin;
$DB->update('user',array('rmb'=>$money),"WHERE user='$user'");


/* 添加赌骰子押金 */
$yajin=$ma[$dxds]+$yajin;
$DB->update('touzi',array($dxds=>$yajin),"WHERE user = '$user'");

/* 更新押的大小单双 */
if($dxds == "damoney")
{
$dxds1='da';
}elseif($dxds == "xiaomoney")
{
$dxds1='xiao';
}elseif($dxds == "danmoney")
{
$dxds1='dan';
}elseif($dxds == "shuangmoney")
{
$dxds1='shuang';
}elseif($dxds == "baozimoney")
{
$dxds1='baozi';
}
$dxds1=$madxds.','.$dxds1;
$DB->update('touzi',array('dxds'=>$dxds1),"WHERE user = '$user'");
echo '<script language=\'javascript\'>alert(\'押注成功，请等待开奖！\'); window.location.href="http://'.$ym.'/?act=shaizi";</script>';
}
}
}
}
include('foot.php');
}
}

/* 三界斗游戏 */
if($act == "sanjiedou")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$row=$DB->select('*','user',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
$money=$row['rmb'];
$roww=$DB->select('*','time',"WHERE sanjiedoutime")->fetchALL();
$roww=$roww['0'];
$c=$roww['sanjiedoutime'];
if($c == "")
{
$r=timejian5();
$DB->insert('time',array('saimatime'=>'','touzitime'=>'','sanjiedoutime'=>$r));
}
$r=$roww['sanjiedoutime'];//10分钟后时间
$t = date("Y-m-j H:i");//现在时间
if($t < $r)
{
$sj=strtotime($r)-strtotime($t);
$sj=$sj/60;//剩余开奖时间
}else{
$r1=timejian5();
$DB->update('time',array('sanjiedoutime'=>$r1));
$sjdurl='http://'.$_SERVER['SERVER_NAME'].'/job.php?kj=sanjiedou';
openu($sjdurl);
}

if($sj < 0)
{
$DB->query('delete from time');
}

echo $ha;
echo '
<a name="top"></a><title>三界斗 - '.$title.'</title>
';
echo '<div class="tops mar">
<a href="?act=youxi"><div class="tops1">返回</div></a>
<a href="?act=sanjiedou"><div class="tops1">刷新</div></a>
</div>';
echo '<div class="d h">您当前的金币：<font color="red">'.$money.'</font></div>';
echo '<div class="d h">'.'还有：'.$sj.' 分钟决出胜负，请求增援！<br>';
echo sjjg().' <a href="?act=sanjiedou">点击刷新</a></div>';
$file=fopen("sanjiedoudb.txt","r");
$file=fgets($file);
$zsjg=explode('|',$file);
echo '<div class="d h">上次战事 <font color="red">'.$zsjg[0].'</font> 胜<br>时间：'.$zsjg[1].'</div>';

/* 取用户三界斗金币 */
$row1=$DB->select('*','sanjiedou',"WHERE user='$user'")->fetchALL();
$row1=$row1['0'];
if($row1 == "")
{
$DB->insert('sanjiedou',array('shenjie'=>'0','fojie'=>'0','mojie'=>'0','youjie'=>'','user'=>$user));
}

$row=$DB->select('*','sanjiedou',"WHERE user='$user'")->fetchALL();
$row=$row['0'];
$sanjie['shenjie']=$row['shenjie'];
$sanjie['fojie']=$row['fojie'];
$sanjie['mojie']=$row['mojie'];
$youjie=$row['youjie'];

/* 统计三界斗金币 */
$shenjiemoney1=$DB->select('sum(shenjie)','sanjiedou')->fetchALL();
$shenjiemoney1=$shenjiemoney1['0']['sum(shenjie)'];

$fojiemoney1=$DB->select('sum(fojie)','sanjiedou')->fetchALL();
$fojiemoney1=$fojiemoney1['0']['sum(fojie)'];

$mojiemoney1=$DB->select('sum(mojie)','sanjiedou')->fetchALL();
$mojiemoney1=$mojiemoney1['0']['sum(mojie)'];

echo '<div class="d h">神界目前军资:'.$shenjiemoney1.'金币<br>';
echo '佛界目前军资:'.$fojiemoney1.'金币<br>';
echo '魔界目前军资:'.$mojiemoney1.'金币</div>';
echo '<div class="d h">您已增援神界:'.$sanjie['shenjie'].'金币<br>';
echo '您已增援佛界:'.$sanjie['fojie'].'金币<br>';
echo '您已增援魔界:'.$sanjie['mojie'].'金币</div>';
echo '<div class="d h">
游戏说明：<br>
1.每次战事为10分钟。<br>
2.您可以选择向自己喜欢的势力支援军资进行征兵，以助其获取最终战争胜利。<br>
3.您所资源的势力如果获取了战争胜利，作为战胜方，您可以获得支援军资的2.9倍战利品。<br>
4.申明：比赛结果会受天时，地利，人和影响，不保证任何势力肯定获胜或者失败。</div>
';
echo '<div class="d h">
<form name="form" action="?act=sanjiedou&my=c" method="post">
选择您所支持的势力:<br />
<select name="shili">
<option value="shenjie">神界</option>
<option value="fojie">佛界</option>
<option value="mojie">魔界</option>
</select>
<br />
军资:<br /><input name="junzi" value="10" /><br />
<input type="submit" value="投注"/>
</form></div>';
if($my == "c")
{
if(!preg_match("/^[0-9]\d{0,99}$/",$junzi)) {
}else{
if($junzi > $money)
{
echo '<script language=\'javascript\'>alert(\'您输入的赌注大于您的金币,请重新输入！\'); window.location.href="http://'.$ym.'/?act=sanjiedou";</script>';
}else{
if($money <= 0)
{
echo '<script language=\'javascript\'>alert(\'金币必须大于0才可以玩！\'); window.location.href="http://'.$ym.'/?act=sanjiedou";</script>';
}else{

/* 扣除金币 */
$money=$money-$junzi;
$DB->update('user',array('rmb'=>$money),"WHERE user='$user'");

/* 添加军资 */
$junzi=$sanjie[$shili]+$junzi;
$DB->update('sanjiedou',array($shili=>$junzi),"WHERE user='$user'");


/* 添加你支持的势力 */
$youjie1=$youjie.','.$shili;
$DB->update('sanjiedou',array('youjie'=>$youjie1),"WHERE user='$user'");

if($shili == "shenjie")
{
$shili11='神界';
}elseif($shili == "fojie")
{
$shili11='佛界';
}elseif($shili == "mojie")
{
$shili11='魔界';
}
echo "<script language='javascript'>alert('".$shili11."获得您的增援，军威大振！'); ".'window.location.href="http://'.$ym.'/?act=sanjiedou";</script>';
}
}
}
}
include('foot.php');
}
}


/* 论坛 */
if($act == "lt")
{
echo $ha;
echo '
<a name="top"></a><title>板块列表 - '.$title.'</title>
';
echo '
<div class="tops mar">
<div class="tops1">板块列表</div>
</div>
';
$result=$DB->select('*','bankuai')->fetchAll();
if($result)
{
echo '<div class="boxs">';
foreach($result as $row){
echo '
<a href="?act=tzlist&bkid='.$row{'id'}.'">
<div class="box">
<div class="text">
'.$row{'bk'}.'
</div>
</div></a>
';
}
echo '</div>';
}else{
echo '<div class="bc">目前论坛没有创建板块</div>';
}
include('foot.php');
}

/* 所有加精帖子列表 */
if($act == "jin")
{
echo $ha;
echo '<a name="top"></a><title>精品帖子列表 - 18idc官方论坛</title>
<a name="top"/>
<div class="tops mar">
<a href="'.$ly.'"><div class="tops1">返回</div></a> 
<a href="?'.$_SERVER["QUERY_STRING"].'"><div class="tops1">刷新</div></a>
</div>
';



$pagesize = 10;
$cou=$DB->select('count(*)','tiezi')->fetch(PDO::FETCH_COLUMN);
$numrows = $cou;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize) {
$pages++;
}
if (isset($_GET['page'])) {
$page = intval($_GET['page']);
} else {
$page = 1;
}
$offset = $pagesize * ($page - 1);
$result=$DB->select('*','tiezi',"WHERE jin='yes' order by ding  desc,hfsj desc limit  ?,?", $offset,$pagesize)->fetchAll();
if($result){
foreach($result as $myrow){


/* 显示用户的头像 */
$row11111=$DB->select('*','touxiang','WHERE user=?',$myrow['user'])->fetchALL();
$row11111=$row11111[0];
if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}

/* 统计帖子回复数（不统计楼层回复） */
$huinum=$DB->select('count(*)','tiezihf',"WHERE tzid='".$myrow['Id']."'")->fetchALL(PDO::FETCH_COLUMN);
$huinum=$huinum['0'];

/* 判断帖子精品 */
if($myrow['jin'] == "yes")
{
$tzjin='<span class="light">精</span>';
}else{
$tzjin='';
}

/* 判断帖子置顶 */
if($myrow['ding'] == "yes")
{
$tzding='<span class="light">顶</span>';
}else{
$tzding='';
}

echo '
<a href="?act=cktz&tzid='.$myrow['Id'].'&bkid='.$myrow['bk'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div>
</a>
<a href="?act=cktz&tzid='.$myrow['Id'].'&bkid='.$myrow['bk'].'">
<div class="boxx">
'.$tzjin.$tzding.'
<div class="text">'.$myrow['bt'].'</div>
</div>
<div class="boxxs">
<div class="boxxs1">'.$myrow['user'].'</div>
<div class="boxxs1">点'.$myrow['dian'].'</div>
<div class="boxxs1">回'.$huinum.'</div>
<div class="boxxs1">'.$myrow['sj'].'</div>
</div>
</a>
';
}
echo '<div class="page">';
$prev = $page - 1;//上一页
if($prev <= "0")
{
$prev='1';
}
$next = $page + 1;//下一页
echo '<a href="?act=jin&page='.$prev.'"><div class="page1">上一页</div></a>';
for ($i = 1; $i < $page; $i++)
echo "<a href='?act=jin&page=".$i."'><div class='page3'>".$i."</div></a> ";
echo "<a href='?act=jin&page=".$page."'><div class='page3' style='background: #D5D5D5;'>" . $page . "</div></a> ";
if($next > $pages)
{
$next=$i;
}
for ($i = $page + 1; $i <= $pages; $i++)
echo "<a href='?act=jin&page=".$i."'><div class='page3'>" . $i . "</div></a> ";
echo '<a href="?act=jin&page='.$next.'"><div class="page1">下一页</div></a>';
echo '</div>';
}else
{
echo '<div class="d h">目前论坛还没有加精的帖子！</div>';
}
include('foot.php');
}

/* 帖子列表 */
if($act == "tzlist")
{
if($bkid == "")
{
echo '<script language=\'javascript\'>alert(\'查看板块错误，参数错误！\'); window.location.href="http://'.$ym.'/";</script>';
}else{
$ac=$DB->select('*','bankuai',"WHERE id='$bkid'")->fetchALL();
$ac=$ac['0'];
if($ac['bk'] == "")
{
echo '<script language=\'javascript\'>alert(\'查看板块错误，该板块未创建！\'); window.location.href="http://'.$ym.'/";</script>';
}
}
$btname=$DB->select('*','bankuai',"WHERE id='$bkid'")->fetchALL();
$btname=$btname['0'];
echo $ha;
echo '
<a name="top"></a><title>'.$btname['bk'].' - '.$title.'</title>
';
echo '
<form>
<input type="hidden" name="act" value="search">
<input type="hidden" name="bkid" value="'.$bkid.'">
<input class="search" name="search">
<input class="searchok" type="submit" value="搜索">
</form>
<div class="searchmar"></div>
';

echo '
<div class="top"><a href="?act=tzlist&bkid='.$bkid.'"><img src="theme/img/bk1logo.jpg"></a></div>
<div class="tops"><a href="?act=tzlist&bkid='.$bkid.'"><div class="tops1">'.$btname['bk'].'</div></a>
<a href="#post"><div class="tops1">发帖</div></a></div>
';



$pagesize = 10;
$cou=$DB->select('count(*)','tiezi')->fetch(PDO::FETCH_COLUMN);
$numrows = $cou;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize) {
$pages++;
}
if (isset($_GET['page'])) {
$page = intval($_GET['page']);
} else {
$page = 1;
}
$offset = $pagesize * ($page - 1);
$result=$DB->select('*','tiezi','where bk =? order by ding desc,hfsj desc limit ?,?', $bkid,$offset,$pagesize)->fetchAll();
if($result){

foreach($result as $myrow){

/* 显示用户的头像 */
$row11111=$DB->select('*','touxiang','WHERE user=?',$myrow['user'])->fetchALL();
$row11111=$row11111[0];
if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}

/* 统计帖子回复数（不统计楼层回复） */
$huinum=$DB->select('count(*)','tiezihf',"WHERE tzid='".$myrow['Id']."'")->fetchALL(PDO::FETCH_COLUMN);
$huinum=$huinum['0'];

/* 判断帖子精品 */
if($myrow['jin'] == "yes")
{
$tzjin='<span class="light">精</span>';
}else{
$tzjin='';
}

/* 判断帖子置顶 */
if($myrow['ding'] == "yes")
{
$tzding='<span class="light">顶</span>';
}else{
$tzding='';
}
echo '
<a href="?act=cktz&tzid='.$myrow['Id'].'&bkid='.$myrow['bk'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div>
</a>
<a href="?act=cktz&tzid='.$myrow['Id'].'&bkid='.$myrow['bk'].'">
<div class="boxx">
'.$tzjin.$tzding.'
<div class="text">'.$myrow['bt'].'</div>
</div>
<div class="boxxs">
<div class="boxxs1">'.$myrow['user'].'</div>
<div class="boxxs1">点'.$myrow['dian'].'</div>
<div class="boxxs1">回'.$huinum.'</div>
<div class="boxxs1">'.$myrow['sj'].'</div>
</div>
</a>
';
}
echo '<div class="page">';
$prev = $page - 1;//上一页
if($prev <= "0")
{
$prev='1';
}
$next = $page + 1;//下一页
echo '<a href="?act=tzlist&bkid='.$bkid.'&page='.$prev.'"><div class="page1">上一页</div></a>';
for ($i = 1; $i < $page; $i++)
echo "<a href='?act=tzlist&bkid=".$bkid."&page=".$i."'><div class='page3'>".$i."</div></a> ";
echo "<a href='?act=tzlist&bkid=".$bkid."&page=".$page."'><div class='page3' style='background: #D5D5D5;'>".$page."</div></a> ";
if($next > $pages)
{
$next=$i;
}
for ($i = $page + 1; $i <= $pages; $i++)
echo "<a href=?act=tzlist&bkid=".$bkid."&page=".$i."><div class='page3'>".$i."</div></a> ";
echo '<a href="?act=tzlist&bkid='.$bkid.'&page='.$next.'"><div class="page1">下一页</div></a>';
echo '</div>';
}else
{
echo '<div class="d h">该板块目前没有帖子哦！</div>';
}
echo $fatie;
include('foot.php');
}

/* 查看帖子 */
if($act == "cktz")
{
if($tzid == "" or $bkid == "")
{
echo '<script language=\'javascript\'>alert(\'查看帖子错误，参数错误！\'); window.location.href="http://'.$ym.'/";</script>';
}else{
$ac=$DB->select('*','tiezi',"WHERE Id='$tzid'")->fetchALL();
$ac=$ac['0'];
if($ac['bk'] == "")
{
echo '<script language=\'javascript\'>alert(\'查看帖子错误，参数错误！\'); window.location.href="http://'.$ym.'/";</script>';
}
}

echo $ha;
$tz=$ac;

$bkname=$DB->select('*','bankuai',"WHERE id='$bkid'")->fetchALL();
$bkname=$bkname['0'];

$dian=$tz['dian'] + 1;
$DB->update('tiezi',array('dian'=>$dian),"WHERE Id = '".$tz['Id']."'");//增加帖子点击

/* 统计帖子回复数（不统计楼层回复） */
$huinum=$DB->select('count(*)','tiezihf',"WHERE tzid='".$tz['Id']."'")->fetchALL(PDO::FETCH_COLUMN);
$huinum=$huinum['0'];

/* 判断帖子精品 */
if($tz['jin'] == "yes")
{
$tzjin='<span class="light">精</span>';
}else{
$tzjin='';
}

/* 判断帖子置顶 */
if($tz['ding'] == "yes")
{
$tzding='<span class="light">顶</span>';
}else{
$tzding='';
}

/* 显示用户的头像 */
$row11111=$DB->select('*','touxiang','WHERE user=?',$tz['user'])->fetchALL();
$row11111=$row11111[0];
if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}

echo '<a name="top"></a><title>'.$tz['bt'].' - '.$title.'</title>';
echo '
<div class="tops mar"><a href="?act=tzlist&bkid='.$tz['bk'].'"><div class="tops1">'.$bkname['bk'].'</div></a>
<a href="#post"><div class="tops1">回帖</div></a> <a href="?act=shoucangtz&tzid='.$tz['Id'].'&bkid='.$tz['bk'].'"><div class="tops1">收藏</div></a>
</div>
';

if(user() == "no")
{
$xxx='';
}else{
$bzqx=banzhuqx();
if($bzqx == "yes")
{
if($tz['jin'] == 'yes')
{
$xxx='<a href="?act=ltqx&my=qujing&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">去精</div></a>';
}else{
$xxx='<a href="?act=ltqx&my=jiajing&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">加精</div></a>';
}

if($tz['ding'] == 'yes')
{
$xxx=$xxx.'<a href="?act=ltqx&my=quding&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">去顶</div></a>';
}else{
$xxx=$xxx.'<a href="?act=ltqx&my=ziding&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">置顶</div></a>';
}

$xxx=$xxx.'<a href="?act=xgtz&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">修改</div></a><a href="?act=sctz&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">删除</div></a>';
}else{
if($tz['user'] == $user)
{
$xxx='<a href="?act=xgtz&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">修改</div></a><a href="?act=sctz&bkid='.$tz['bk'].'&tzid='.$tz['Id'].'"><div class="boxxs1">删除</div></a>';
}else{
$xxx='';
}
}
}

/* 显示帖子标题 */
echo '
<a href="?act=user&id='.$tz['user'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div>
</a>
<div class="boxx">
'.$tzjin.$tzding.$tz['bt'].'(共'.$huinum[0].'贴) <br>点击:'.$tz['dian'].'
<br>
</div>
<div class="boxxs">
<a href="?act=cktz&my=lz&tzid='.$tz['Id'].'&bkid='.$tz['bk'].'"><div class="boxxs1">只看楼主</div></a>
<a href="#bottom"><div class="boxxs1">去底部</div></a>
<a href="'.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].'"><div class="boxxs1">刷新</div></a>
'.$xxx.'
</div>
';

/* 显示帖子内容 */
echo '
<div class="boxxer boxx">'.bbs_ubb($tz['nr']).'</div>
<div class="boxxs">
<a href="?act=user&id='.$tz['user'].'">
<div class="boxxs1">'.$tz['user'].'</div>
</a>
<div class="boxxs1">'.$tz['sj'].'</div>
</div>
';

/* 显示帖子回复内容 */
$pagesize = 10;
$cou=$DB->select('count(*)','tiezihf')->fetch(PDO::FETCH_COLUMN);
$numrows = $cou;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize) {
$pages++;
}
if (isset($_GET['page'])) {
$page = intval($_GET['page']);
} else {
$page = 1;
}
$offset = $pagesize * ($page - 1);

if($my == "lz")
{
$result=$DB->select('*','tiezihf','WHERE tzid = ? and user = ? order by id limit ?,?', $tz['Id'],$tz['user'],$offset,$pagesize)->fetchAll();
}else{
$result=$DB->select('*','tiezihf','WHERE tzid = ? order by id limit ?,?', $tz['Id'],$offset,$pagesize)->fetchAll();
}


foreach($result as $myrow){
/* 显示回复用户的头像 */
$row11111=$DB->select('*','touxiang','WHERE user=?',$myrow['user'])->fetchALL();
$row11111=$row11111[0];
if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}

$i++;
$iij = $i % 2;

$louid=$i;
if($louid <= 3)
{
$lou=str_replace('1','沙发',$louid);
$lou=str_replace('2','板凳',$lou);
$lou=str_replace('3','地板',$lou);
}else{
$lou=$louid.'楼';
}

/* 显示统计楼层回复 */
$lchfnum=$DB->select('count(*)','lchf',"WHERE tiezihfid='".$myrow['Id']."'")->fetchALL(PDO::FETCH_COLUMN);
$lchfnum=$lchfnum['0'];
if($lchfnum == 0)
{
$lchfnum='';
}else{
$lchfnum=' ('.$lchfnum.')';
}

/* 修改回帖 */
if($myrow['user'] == $user)
{
$xght='<a href="?act=xght&tzid='.$tz['Id'].'&bkid='.$tz['bk'].'&htid='.$myrow['Id'].'"><div class="boxxs2">修改</div></a>';
}else{
$xght='';
}


echo '
<a href="?act=user&id='.$myrow['user'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div>
</a>
<div class="boxx">
<div class="lou">'.$lou.'
</div>'.bbs_ubb($myrow['nr']).'</div>
<div class="boxxs">
<a href="?act=user&id='.$myrow['user'].'"><div class="boxxs1">'.$myrow['user'].'</div></a>
<div class="boxxs1">'.$myrow['sj'].'</div>
<a href="?act=lchf&tzid='.$tz['Id'].'&htid='.$myrow['Id'].'&bkid='.$tz['bk'].'"><div class="boxxs2">回复'.$lchfnum.'
</div></a>
'.$xght.'
</div>
';
}

echo '<div class="page">';
if($my == "lz")
{
$prev = $page - 1;//上一页
if($prev <= "0")
{
$prev='1';
}
$next = $page + 1;//下一页
echo '<a href="?act=cktz&my=lz&tzid='.$tz['Id'].'&bkid='.$tz['bk'].'&page='.$prev.'"><div class="page1">上一页</div></a>';
for ($i = 1; $i < $page; $i++)
echo "<a href='?act=cktz&my=lz&tzid=".$tz['Id']."&bkid=".$tz['bk']."&page=".$i."'><div class='page3'>".$i."</div></a> ";
echo "<a href='?act=cktz&my=lz&tzid=".$tz['Id']."&bkid=".$tz['bk']."&page=".$page."'><div class='page3' style='background: #D5D5D5;'>".$page."</div></a> ";
if($next > $pages)
{
$next=$i;
}
for ($i = $page + 1; $i <= $pages; $i++)
echo "<a href=?act=cktz&my=lz&tzid=".$tz['Id']."&bkid=".$tz['bk']."&page=".$i."><div class='page3'>" . $i . "</div></a> ";
echo '<a href="?act=cktz&my=lz&tzid='.$tz['Id'].'&bkid='.$tz['bk'].'&page='.$next.'"><div class="page1">下一页</div></a>';
}else{
$prev = $page - 1;//上一页
if($prev <= "0")
{
$prev='1';
}
$next = $page + 1;//下一页
echo '<a href="?act=cktz&tzid='.$tz['Id'].'&bkid='.$tz['bk'].'&page='.$prev.'"><div class="page1">上一页</div></a>';
for ($i = 1; $i < $page; $i++)
echo "<a href='?act=cktz&tzid=".$tz['Id']."&bkid=".$tz['bk']."&page=".$i."'><div class='page3'>".$i."</div></a> ";
echo "<a href='?act=cktz&tzid=".$tz['Id']."&bkid=".$tz['bk']."&page=".$page."'><div class='page3' style='background: #D5D5D5;'>".$page."</div></a> ";
if($next > $pages)
{
$next=$i;
}
for ($i = $page + 1; $i <= $pages; $i++)
echo "<a href=?act=cktz&tzid=".$tz['Id']."&bkid=".$tz['bk']."&page=".$i."><div class='page3'>" . $i . "</div></a> ";
echo '<a href="?act=cktz&tzid='.$tz['Id'].'&bkid='.$tz['bk'].'&page='.$next.'"><div class="page1">下一页</div></a>';
}
echo '</div>';

/*
if($result)
{

}elseif($my == 'lz')
{

}else{
echo '<div class="d h">没有回复，赶快抢沙发吧！</div>';
}*/



echo $huitie;
include('foot.php');
}

/* 修改回帖 */
if($act == "xght")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($tzid == "" or $bkid == "" or $htid == "")
{
echo '<script language=\'javascript\'>alert(\'修改错误,参数错误！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}else{
$tz=$DB->select('*','tiezihf',"WHERE Id='$htid' and tzid='$tzid'")->fetchALL();
$tz=$tz['0'];
if($tz['user'] == $user)
{
echo $ha;
echo '
<a name="top"></a><title>编辑回复 - 18idc论坛官网</title>
<div class="d h"> 
<form action="?act=xght1" method="post" enctype="multipart/form-data">
<input type="hidden" name="tzid" value="'.$tz['tzid'].'"/>
<input type="hidden" name="htid" value="'.$tz['Id'].'"/>
<input type="hidden" name="bkid" value="'.$bkid.'"/>
内容:<br/>
<textarea name="nr" rows="2" cols="35">'.$tz['nr'].'</textarea>
<input type="submit" name="sub1" value="修改"/>
</form>

<form>
<input type="hidden" name="act" value="cktz">
<input type="hidden" name="tzid" value="'.$tz['tzid'].'">
<input type="hidden" name="bkid" value="'.$bkid.'">
<input type="submit" value="取消">
</form>
</div>';
include('foot.php');
}else{
echo '<script language=\'javascript\'>alert(\'修改错误,这个回帖不是你的！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
}
}
}
if($act == "xght1")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$tzid=$_POST['tzid'];
$bkid=$_POST['bkid'];
$htid=$_POST['htid'];
$nr=$_POST['nr'];
if($tzid == "" or $htid == "" or $bkid == "" or $nr == "")
{
echo '<script language=\'javascript\'>alert(\'修改错误,参数错误！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}else{
$tz=$DB->select('*','tiezihf',"WHERE Id='$htid' and tzid='$tzid'")->fetchALL();
$tz=$tz['0'];
if($tz['user'] == $user)
{
$nr=htmlspecialchars($nr);
$DB->update('tiezihf',array('nr'=>$nr,'sj'=>$date),"WHERE Id = '$htid'");
echo '<script language=\'javascript\'>alert(\'修改回复成功！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}else{
echo '<script language=\'javascript\'>alert(\'修改错误,这个回帖不是你的！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
}
}
}

/* 收藏帖子 */
if($act == "shoucangtz")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($tzid == "" or $bkid == "")
{
echo '<script language=\'javascript\'>alert(\'收藏帖子失败，参数错误！\'); window.location.href="'.$ly.'";</script>';
}else
{
$cx=$DB->select('*','tiezi','where Id = ?',$tzid)->fetchALL();
$cx=$cx['0'];
if($cx == "")
{
echo '<script language=\'javascript\'>alert(\'收藏帖子失败，该帖子不存在！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}else{
$a=$DB->select('*','shoucangtz',"where user='$user' and tzid='$tzid'")->fetchALL();
$a=$a['0'];
if($a == "")
{
$DB->insert('shoucangtz',array('user'=>$user,'tzid'=>$tzid,'bkid'=>$bkid));//收藏帖子
echo '<script language=\'javascript\'>alert(\'收藏帖子成功！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}else{
echo '<script language=\'javascript\'>alert(\'收藏帖子失败，您已收藏过该帖子！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
}
}
}
}
/* 发帖 */
if($act == "fatie")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($bt == "" or $nr == "" or $bkid == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="'.$ly.'";</script>';
}else{
/* 判断最新贴与现在所发的贴的内容时间比对 */
$aa=$DB->select('*','tiezi',"WHERE user='$user' order by Id desc limit 0,1")->fetchALL();
$aa=$aa['0'];
$ytsj=$aa['sj'];
$ytnr=$aa['nr'];
$ytbt=$aa['bt'];
$sj=strtotime($date)-strtotime($ytsj);//两次发帖时间
if($sj <= 10)
{
echo '<script language=\'javascript\'>alert(\'请10秒后发帖！\'); window.location.href="'.$ly.'";</script>';
}elseif($ytbt == $bt or $ytnr == $nr)
{
echo '<script language=\'javascript\'>alert(\'内容或者标题与前篇帖子一样，请重新发帖！\'); window.location.href="'.$ly.'";</script>';
}else{
$hfsj=strtotime($date);
$bt=htmlspecialchars($bt);
$nr=htmlspecialchars($nr);
$DB->insert('tiezi',array('user'=>$user,'bt'=>$bt,'nr'=>$nr,'sj'=>$date,'bk'=>$bkid,'jin'=>'no','dian'=>'0','hfsj'=>$hfsj,'ding'=>'no'));
echo '<script language=\'javascript\'>alert(\'发帖成功！\'); window.location.href="'.$ly.'";</script>';
}
}
}
}


/* 回帖 */
if($act == "huitie")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($nr == "" or $tzid == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="'.$ly.'";</script>';
}else{
/* 判断最新贴与现在所发的贴的内容时间比对 */
$aa=$DB->select('*','tiezihf',"WHERE user='$user' order by Id desc limit 0,1")->fetchALL();
$aa=$aa['0'];
$ytsj=$aa['sj'];
$ytnr=$aa['nr'];
$sj=strtotime($date)-strtotime($ytsj);//两次发帖时间
if($sj <= 10)
{
echo '<script language=\'javascript\'>alert(\'请10秒后回帖！\'); window.location.href="'.$ly.'";</script>';
}elseif($ytnr == $nr)
{
echo '<script language=\'javascript\'>alert(\'请不要回复相同内容！\'); window.location.href="'.$ly.'";</script>';
}else{
$hfsj=strtotime($date);
$nr=htmlspecialchars($nr);
$DB->insert('tiezihf',array('user'=>$user,'nr'=>$nr,'sj'=>$date,'tzid'=>$tzid));
$DB->update('tiezi',array('hfsj'=>$hfsj),"WHERE Id = '$tzid'");
echo '<script language=\'javascript\'>alert(\'回复成功！\'); window.location.href="'.$ly.'";</script>';
}
}
}
}

/* 楼层回帖 */
if($act == 'lcht')
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($nr == "" or $htid == "" or $tzid == "")
{
echo '<script language=\'javascript\'>alert(\'禁止提交空信息！\'); window.location.href="'.$ly.'";</script>';
}else{
/* 判断最新贴与现在所发的贴的内容时间比对 */
$aa=$DB->select('*','lchf',"WHERE user='$user' order by Id desc limit 0,1")->fetchALL();
$aa=$aa['0'];
$ytsj=$aa['sj'];
$ytnr=$aa['nr'];
$sj=strtotime($date)-strtotime($ytsj);//两次发帖时间
if($sj <= 10)
{
echo '<script language=\'javascript\'>alert(\'请10秒后回帖！\'); window.location.href="'.$ly.'";</script>';
}elseif($ytnr == $nr)
{
echo '<script language=\'javascript\'>alert(\'请不要回复相同内容！\'); window.location.href="'.$ly.'";</script>';
}else{
$hfsj=strtotime($date);
$nr=htmlspecialchars($nr);
$DB->insert('lchf',array('user'=>$user,'nr'=>$nr,'sj'=>$date,'tiezihfid'=>$htid));
$DB->update('tiezi',array('hfsj'=>$hfsj),"WHERE Id = '$tzid'");
echo '<script language=\'javascript\'>alert(\'回复成功！\'); window.location.href="'.$ly.'";</script>';
}
}
}
}

/* 搜索帖子 */
if($act == 'search')
{
$search=htmlspecialchars($search);
$bkname=$DB->select('*','bankuai',"WHERE id='$bkid'")->fetchALL();
$bkname=$bkname['0'];
echo $ha;
echo '<a name="top"></a><title>搜索关键字 "'.$search.'" 结果 - 18idc官方论坛</title>
<form>
<input type="hidden" name="act" value="search">
<input type="hidden" name="bkid" value="'.$bkid.'">
<input class="search" name="search">
<input class="searchok" type="submit" value="搜索">
</form>
<div class="searchmar"></div>
';
echo '
<div class="tops mar">
<div class="tops1"><a href="?act=tzlist&bkid='.$bkid.'">'.$bkname['bk'].'</a></div>
<div class="tops1">搜索关键字 '.$search.' 结果</div>
</div>
';


$pagesize = 10;
$cou=$DB->select('count(*)','tiezi')->fetch(PDO::FETCH_COLUMN);
$numrows = $cou;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize) {
$pages++;
}
if (isset($_GET['page'])) {
$page = intval($_GET['page']);
} else {
$page = 1;
}
$offset = $pagesize * ($page - 1);
$result=$DB->select('*','tiezi',"where bt like '%$search%' or nr like '%$search%' order by ding desc,id desc limit ?,?" ,$offset,$pagesize)->fetchAll();
if($result){
foreach($result as $myrow){


/* 显示用户的头像 */
$row11111=$DB->select('*','touxiang','WHERE user=?',$myrow['user'])->fetchALL();
$row11111=$row11111[0];
if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}

/* 统计帖子回复数（不统计楼层回复） */
$huinum=$DB->select('count(*)','tiezihf',"WHERE tzid='".$myrow['Id']."'")->fetchALL(PDO::FETCH_COLUMN);
$huinum=$huinum['0'];

/* 判断帖子精品 */
if($myrow['jin'] == "yes")
{
$tzjin='<span class="light">精</span>';
}else{
$tzjin='';
}

/* 判断帖子置顶 */
if($myrow['ding'] == "yes")
{
$tzding='<span class="light">顶</span>';
}else{
$tzding='';
}


echo '
<a href="?act=cktz&tzid='.$myrow['Id'].'&bkid='.$myrow['bk'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div>
</a>
<a href="?act=cktz&tzid='.$myrow['Id'].'&bkid='.$myrow['bk'].'">
<div class="boxx">'.$tzjin.$tzding.'
<div class="text">'.$myrow['bt'].'</div>
</div>
<div class="boxxs">
<div class="boxxs1">'.$myrow['user'].'</div>
<div class="boxxs1">点'.$myrow['dian'].'</div>
<div class="boxxs1">回'.$huinum.'</div>
<div class="boxxs1">'.$myrow['sj'].'</div>
</div>
</a>
';
}
echo '<div class="page">';
$prev = $page - 1;//上一页
if($prev <= "0")
{
$prev='1';
}
$next = $page + 1;//下一页
echo '<a href="?act=search&bkid='.$bkid.'&search='.$search.'&page='.$prev.'"><div class="page1">上一页</div></a>';
for ($i = 1; $i < $page; $i++)
echo "<a href='?act=search&bkid=".$bkid."&search=".$search."&page=".$i."'><div class='page3'>".$i."</div></a> ";
echo "<a href='?act=search&bkid=".$bkid."&search=".$search."&page=".$page."'><div class='page3' style='background: #D5D5D5;'>".$page."</div></a> ";
if($next > $pages)
{
$next=$i;
}
for ($i = $page + 1; $i <= $pages; $i++)
echo "<a href='?act=search&bkid=".$bkid."&search=".$search."&page=".$i."'><div class='page3'>" . $i . "</div></a> ";
echo '<a href="?act=search&bkid='.$bkid.'&search='.$search.'&page='.$next.'"><div class="page1">下一页</div></a>';
echo '</div>';
}else
{
echo '<div class="d h">搜索关键字无结果！</div>';
}
include('foot.php');
}

/* 楼层回复 */
if($act == "lchf")
{
echo $ha;
echo '<a name="top"></a><title>回帖列表 - 18idc官方论坛</title>
<a name="top"/>
<div class="tops mar">
<a href="?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'"><div class="tops1">返回帖子</div></a> 
<a href="?'.$_SERVER["QUERY_STRING"].'"><div class="tops1">刷新</div></a>
</div>
';


$aa=$DB->select('*','tiezihf'," WHERE Id='$htid'")->fetchALL();
$aa=$aa['0'];

/* 显示楼层用户的头像 */
$row11111=$DB->select('*','touxiang','WHERE user=?',$aa['user'])->fetchALL();
$row11111=$row11111[0];

if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}


echo '
<a href="?act=user&id='.$aa['user'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div>
</a>
<div class="boxx">'.bbs_ubb($aa['nr']).'
<div class="lou">层主</div>
</div>
<div class="boxxs">
<a href="?act=user&id='.$aa['user'].'">
<div class="boxxs1">'.$aa['user'].'</div></a> 
<div class="boxxs1">'.$aa['sj'].'</div>
</div>
';

$cou=$DB->select('count(*)','lchf')->fetch(PDO::FETCH_COLUMN);
$size=10;
$nv=ceil($cou/$size);
$page=(int)$_GET['page'];
$p=empty($page)?'1':$page;
$p=$p>$nv?'1':$p;
$start=$size*($p-1);
$result=$DB->select('*','lchf',"WHERE tiezihfid='$htid'".' order by id limit ?,?',$start,$size)->fetchAll();
if($result){
foreach($result as $myrow){

/* 显示回复用户的头像 */
$row11111=$DB->select('*','touxiang','WHERE user=?',$myrow['user'])->fetchALL();
$row11111=$row11111[0];
if($row11111['user'] == "")
{
$uurl='theme/img/no.gif';
}else{
$uurl=$row11111['txurl'];
}

echo '
<a href="?act=user&id='.$myrow['user'].'">
<div class="boxxs3" style="background: url('.$uurl.');background-size: 100% 100%;"></div>
</a>
<div class="boxx">
'.bbs_ubb($myrow['nr']).'
</div>
<div class="boxxs">
<a href="?act=user&id='.$myrow['user'].'">
<div class="boxxs1">'.$myrow['user'].'</div></a> 
<div class="boxxs1">'.$myrow['sj'].'</div>
</div>
';

}
echo'<div class="page">';
if($p>1){
echo'<a href="?act=lchf&htid='.$htid.'&page='.($p-1).'"><div class="page1">上一页</div></a>';
}
if($p<$nv&&$nv>1){
echo'<a href="?act=lchf&htid='.$htid.'&page='.($p+1).'"><div class="page1">下一页</div></a>';
}
echo '</div>';
}else
{
echo '<div class="d h">该楼层还没有人回复！</div>';
}
echo $lchuitie;
include('foot.php');
}

/* 删除帖子 */
if($act == "sctz")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($bkid == "" or $tzid == "")
{
echo '<script language=\'javascript\'>alert(\'删除帖子失败！\'); window.location.href="http://'.$ym.'/?act=tzlist&bkid='.$bkid.'";</script>';
}else{
$tz=$DB->select('*','tiezi',"WHERE Id='$tzid'")->fetchALL();
$tz=$tz['0'];
$bzqx=banzhuqx();
if($bzqx == "yes" or $tz['user'] == $user)
{
echo $ha;
echo '<a name="top"></a><title>删除帖子？ - 18idc官方论坛</title>';
echo '<div class="d h">是否删除帖子？</div>';
echo '<div class="d h">
<form>
<input type="hidden" name="act" value="sctz"/>
<input type="hidden" name="my" value="sc"/>
<input type="hidden" name="tzid" value="'.$tzid.'"/>
<input type="hidden" name="bkid" value="'.$bkid.'"/>
<input type="submit" name="sub1" value="是"/>
</form>

<form>
<input type="hidden" name="act" value="cktz"/>
<input type="hidden" name="tzid" value="'.$tzid.'"/>
<input type="hidden" name="bkid" value="'.$bkid.'"/>
<input type="submit" name="sub1" value="否"/>
</form>

</div>
';
include('foot.php');
}else{
echo '<script language=\'javascript\'>alert(\'删除失败，这个帖子不是你的！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}

}

if($my == "sc")
{
$tz=$DB->select('*','tiezi',"WHERE Id='$tzid'")->fetchALL();
$tz=$tz['0'];
$bzqx=banzhuqx();
if($bzqx == "yes" or $tz['user'] == $user)
{
$DB->delete('tiezi',"WHERE Id='$tzid'");
echo '<script language=\'javascript\'>alert(\'删除帖子成功！\'); window.location.href="http://'.$ym.'/?act=tzlist&bkid='.$bkid.'";</script>';
}else{
echo '<script language=\'javascript\'>alert(\'删除帖子失败！\'); window.location.href="http://'.$ym.'/?act=tzlist&bkid='.$bkid.'";</script>';
}
}
}
}

/* 修改帖子 */
if($act == "xgtz")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($tzid == "" or $bkid == "")
{
echo '<script language=\'javascript\'>alert(\'修改帖子失败，参数错误！\'); window.location.href="http://'.$ym.'/?act=tzlist&bkid='.$bkid.'";</script>';
}else{
$tz=$DB->select('*','tiezi',"WHERE Id='$tzid'")->fetchALL();
$tz=$tz['0'];
$bzqx=banzhuqx();
if($bzqx == "yes" or $tz['user'] == $user)
{
echo $ha;
echo '<a name="top"></a><title>修改帖子 - 18idc官方论坛</title>';
echo '<div class="d h">
<form action="?act=xgtz1" method="post" enctype="multipart/form-data">
<input type="hidden" name="tzid" value="'.$tzid.'"/>
<input type="hidden" name="bkid" value="'.$bkid.'"/>
标题:<br/>
<input type="text" name="bt" maxlength="50" class="q" value="'.$tz['bt'].'"/>
内容:<span class="g">[必填]</span><br/>
<textarea name="nr" rows="2" cols="35"">'.$tz['nr'].'</textarea><br>
<input type="submit" name="sub1" value="修改"/></form>

<form>
<input type="hidden" name="act" value="cktz"/>
<input type="hidden" name="tzid" value="'.$tzid.'"/>
<input type="hidden" name="bkid" value="'.$bkid.'"/>
<input type="submit"  value="返回"/></form>
</div>
';
include('foot.php');
}else{
echo '<script language=\'javascript\'>alert(\'这个帖子不是你的，请不要修改！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
}
}
}
if($act == "xgtz1")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
$tzid=$_POST['tzid'];
$bkid=$_POST['bkid'];
if($tzid == "" or $bkid == "")
{
echo '<script language=\'javascript\'>alert(\'修改帖子失败，参数错误！\'); window.location.href="http://'.$ym.'/?act=tzlist&bkid='.$bkid.'";</script>';
}else{
$tz=$DB->select('*','tiezi',"WHERE Id='$tzid'")->fetchALL();
$tz=$tz['0'];
$bzqx=banzhuqx();
if($bzqx == "yes" or $tz['user'] == $user)
{
$bt=$_POST['bt'];
$nr=$_POST['nr'];
$bt=htmlspecialchars($bt);
$nr=htmlspecialchars($nr);
$DB->update('tiezi',array('bt'=>$bt,'nr'=>$nr,'sj'=>$date),"WHERE Id = '$tzid'");
$hfsj=strtotime($date);
$DB->update('tiezi',array('hfsj'=>$hfsj),"WHERE Id = '$tzid'");
echo '<script language=\'javascript\'>alert(\'修改帖子成功！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}else{
echo '<script language=\'javascript\'>alert(\'这个帖子不是你的，请不要修改！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
}
}
}


/* 论坛权限  加精 置顶等 */
if($act == "ltqx")
{
if(user() == "no")
{
echo '<script language=\'javascript\'>alert(\'请先登录在执行操作！\'); window.location.href="http://'.$ym.'/?act=login";</script>';
}elseif(user() == "yes"){
if($tzid == "" or $bkid == "" or $my == "")
{
echo '<script language=\'javascript\'>alert(\'操作失败,参数错误！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}else{
$bzqx=banzhuqx();
if($bzqx == "yes")
{
/* 置顶 */
if($my == "ziding")
{
$DB->update('tiezi',array('ding'=>'yes'),"WHERE Id = '$tzid'");
echo '<script language=\'javascript\'>alert(\'帖子置顶成功！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
/* 去顶 */
if($my == "quding")
{
$DB->update('tiezi',array('ding'=>'no'),"WHERE Id = '$tzid'");
echo '<script language=\'javascript\'>alert(\'帖子取消置顶成功！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
/* 加精 */
if($my == "jiajing")
{
$DB->update('tiezi',array('jin'=>'yes'),"WHERE Id = '$tzid'");
echo '<script language=\'javascript\'>alert(\'帖子加精成功！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
/* 去精 */
if($my == "qujing")
{
$DB->update('tiezi',array('jin'=>'no'),"WHERE Id = '$tzid'");
echo '<script language=\'javascript\'>alert(\'帖子取消加精成功！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
}else{
echo '<script language=\'javascript\'>alert(\'操作失败,您不是版主！\'); window.location.href="http://'.$ym.'/?act=cktz&tzid='.$tzid.'&bkid='.$bkid.'";</script>';
}
}
}
}
?>












<?php
class image {

/**
* 添加背景
* @param string $src 图片路径
* @param int $w 背景图像宽度
* @param int $h 背景图像高度
* @param String $first 决定图像最终位置的，w 宽度优先 h 高度优先 wh:等比
* @return 返回加上背景的图片
* **/
static function addBg($src,$w,$h,$fisrt="w")
{
$bg=imagecreatetruecolor($w,$h);
$white = imagecolorallocate($bg,255,255,255);
imagefill($bg,0,0,$white);//填充背景
//获取目标图片信息
$info=self::getImageInfo($src);
$width=$info[0];//目标图片宽度
$height=$info[1];//目标图片高度
$img=self::create($src);
if($fisrt=="wh")
{
//等比缩放
return $src;
}
else
{
if($fisrt=="w")
{
$x=0;
$y=($h-$height)/2;//垂直居中
}
if($fisrt=="h")
{
$x=($w-$width)/2;//水平居中
$y=0;
}
imagecopymerge($bg,$img,$x,$y,0,0,$width,$height,100);
imagejpeg($bg,$src,100);
imagedestroy($bg);
imagedestroy($img);
return $src;
}
}

static function getImageInfo($src)
{
return getimagesize($src);
}

static function create($src)
{
$info=self::getImageInfo($src);
switch ($info[2])
{
case 1:
$im=imagecreatefromgif($src);
break;
case 2:
$im=imagecreatefromjpeg($src);
break;
case 3:
$im=imagecreatefrompng($src);
break;
}
return $im;
}


/**
* 缩略图主函数
* @param string $src 图片路径
* @param string $dir 缩略图存放目录
* @param int $w 缩略图宽度
* @param int $h 缩略图高度
* @return mixed 返回缩略图路径
* **/
function resize($src,$dir,$w,$h)
{
$temp=pathinfo($src);
$name=$temp["basename"];//文件名
$extension=$temp["extension"];//文件扩展名
$savepath="./{$dir}/{$name}";//缩略图保存路径
//获取图片的基本信息
if (file_exists($savepath)){
return $savepath;
}
if(!file_exists($dir)){
mkdir($dir);
}
$info=self::getImageInfo($src);
$width=$info[0];//获取图片宽度
$height=$info[1];//获取图片高度
$per1=round($width/$height,2);//计算原图长宽比
$per2=round($w/$h,2);//计算缩略图长宽比
//计算缩放比例
if($per1>$per2||$per1==$per2)
{
//原图长宽比大于或者等于缩略图长宽比，则按照宽度优先
$per=$w/$width;
}
if($per1<$per2)
{
//原图长宽比小于缩略图长宽比，则按照高度优先
$per=$h/$height;
}
$temp_w=intval($width*$per);//计算原图缩放后的宽度
$temp_h=intval($height*$per);//计算原图缩放后的高度
$temp_img=imagecreatetruecolor($temp_w,$temp_h);//创建画布
$im=self::create($src);
imagecopyresampled($temp_img,$im,0,0,0,0,$temp_w,$temp_h,$width,$height);
if($per1>$per2)
{
imagejpeg($temp_img,$savepath, 100);
imagedestroy($im);
return self::addBg($savepath,$w,$h,"w");
//宽度优先，在缩放之后高度不足的情况下补上背景
}
if($per1==$per2)
{
imagejpeg($temp_img,$savepath, 100);
imagedestroy($im);
return $savepath;
//等比缩放
}
if($per1<$per2)
{
imagejpeg($temp_img,$savepath, 100);
imagedestroy($im);
return self::addBg($savepath,$w,$h,"h");
//高度优先，在缩放之后宽度不足的情况下补上背景
}
}
}
?>