<?
include('head.php');

$aa=newxin();
if($aa <= 0)
{
$aaa='';
}else{
$aaa='('.$aa.'未读信息)';
}

if(user() == "yes")
{
$daohang='<li class="qnav_three"><a href="?act=xin">信箱</a></li>
<li class="qnav_four"><a href="?act=user&id='.$user.'">空间</a></li>
';
}else{
$daohang='
<li class="qnav_three"><a href="?act=login">登录</a></li>
<li class="qnav_four"><a href="?act=reg">注册</a></li>
';
}

/* 左下角弹出菜单特效 */
echo <<<HTML
<div class="quick_nav" id="quick_nav" style="left: 12px; bottom: 12px;">
<div class="quick_btn"></div>
<div class="quick_con hide">
<ul class="quick_list">
<li class="qnav_home"><a href="index.php">首页</a></li>
<li class="qnav_one"><a href="?act=jin">精品</a></li>
<li class="qnav_two"><a href="?act=youxi">游戏</a></li>
{$daohang}
<li class="qnav_five"><a href="?act=liao">聊天室</a></li>
<li class="qnav_six"><a href="?act=lt">论坛</a></li>
</ul>
</div>
</div>
<script src="theme/js/bottom.js"></script>
HTML;



echo '
<a href="?act=liao">
<div class="liao">
<div class="liao1">聊天</div>';
$look=$DB->select('*','liaot','order by id desc limit 0,1')->fetchAll();
$look=$look['0'];
echo '
<div class="liao2">'.$look['nr'].'</div>
</div>
</a>
';



if ($user != '' and $pass !='')
{
echo '
<div class="foot"><a href="/"><div class="foot1">首页</div></a>
<a href="?act=xin"><div class="foot1">信箱'.$aaa.'</div></a> <a href="?act=user&id='.$user.'"><div class="foot1">'.$user.'</div></a>
</div>
';
$DB->query("update user set zhfwsj='$date' where user='$user'");
}else{
echo '
<div class="foot"><a href="/"><div class="foot1">首页</div></a>
<a href="?act=login"><div class="foot1">登录</div></a>
</div>
';
}


echo <<<HTML
<div class="foot mars">
<div class="left">
{$date}
</div>
<a href="#top"><div class="rights">
<img src="theme/img/top.gif" alt="TOP"/></div></a>
</div>
<a name="bottom"></a>
</body></html>
HTML;
?>