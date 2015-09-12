<?
/* 判断用户是否登录 */
function user()
{
include('head.php');
include('conn.php');
include('config.php');
$con = new PDO('sqlite:'.$sqlitedb);
$a=$con->query("SELECT * from  user WHERE user='$user'")->fetchAll();
$a=$a['0'];
$con=null;
if($user == '' or $pass != $a['pass'])
{
return 'no';
}else{
return 'yes';
}
}

/* 获取未读内信 */
function newxin()
{
include('head.php');
include('conn.php');
include('config.php');
$con = new PDO('sqlite:'.$sqlitedb);
$newxin=$con->query("select count(*) from neixin WHERE kan='0' and `to`='$user';")->fetchALL();
$newxin=$newxin['0']['0'];
$con=null;
return $newxin;
}

/* 获取用户是否有版主权限 */
function banzhuqx()
{
include('head.php');
include('conn.php');
include('config.php');
$con = new PDO('sqlite:'.$sqlitedb);
$a=$con->query("select banzhu from bankuai WHERE id='$bkid'")->fetchAll();
$a=$a['0'];
if(!preg_match("/$user/i",$a['banzhu']))
{
$adminqx=$con->query("SELECT * from  user WHERE user='$user'")->fetchAll();
$adminqx=$adminqx['0'];
$con=null;
if($adminqx['admin'] == "yes")
{
return 'yes';
}else{
return 'no';
}
}else{
return 'yes';
}
}





/* ubb */

function bbs_ubb($t)
{
$exz='['.chr(94).'《》]';
$or=chr(124);
$bds=array(
'＞＞＞','<br/>',
'＜＜＜','<hr/>',
' ','&nbsp;',
'\[b\](.*)\[/b\]','<strong>\\1</strong>',
'\[i\](.*)\[/i\]','<i>\\1</i>',
'\[u\](.*)\[/u\]','<u>\\1</u>',
'\[s\](.*)\[/s\]','<s>\\1</s>',
'\[sub\](.*)\[/sub\]','<sub>\\1</sub>',
'\[sup\](.*)\[/sup\]','<sup>\\1</sup>',
'\[size=(.*)\](.*)\[/size\]','<font size="\\1px">\\2</font>',
'\[url\](.*)\[/url\]','<a href="\\1">\\1</a>',
'\[url=(.*)\](.*)\[/url\]','<a href="\\1">\\2</a>',
'\[email\](.*)\[/email\]','<a href="mailto:\\1">\\1</a>',
'\[email=(.*)\](.*)\[/email\]','<a href="mailto:\\1">\\2</a>',
'\[color=(.*)\](.*)\[/color\]','<font color="\\1">\\2</font>',
'\[img\](.*)\[/img\]','<img src="\\1"/>',
'\[img=(.*)\](.*)\[/img\]','<img src="\\1" alt="\\2"/>',
'\[time\]',date('Y-m-d H:i:s'),
'\[((br)|(hr))\]','<\\1/>',
'\[tab\]','&nbsp;&nbsp;&nbsp;&nbsp;',
"\r\n",'<br/>',
"[\r\n]",'<br/>',
);


$jc=count($bds);
for($a=0;$a<$jc;$a=$a+2)
{
$b=$a+1;
$t=preg_replace('!'.$bds[$a].'!uisU',$bds[$b],$t);
}
if (preg_match('/@/', $t)) {
$t = preg_replace("/\@(.*)\s/iUs", '<a href="xing.php?my=fs&to=\\1">@\\1</a> ', $t);
}
return $t;
}


/* 时间戳转换时间 */
function sjzh($sjc)
{
return date("Y-m-j H:i:s",$sjc);
}


/* 猜拳游戏函数 */
function caiquan($chuquan){
$c=array(
"剪刀"=>array(
"石头"=>"输",
"布"=>"赢",
"剪刀"=>"平局"
),
"石头"=>array(
"布"=>"输",
"剪刀"=>"赢",
"石头"=>"平局"
),
"布"=>array(
"剪刀"=>"输",
"石头"=>"赢",
"布"=>"平局"
)
);
$arr=array(
"剪刀",
"石头",
"布",
"剪刀",
"石头",
"布",
"剪刀",
"石头",
"布",
"剪刀",
"石头",
"布"
);//随机得到更乱的系统出拳
$rand=rand(0,count($arr)-1);
$r=$arr[$rand];
$result=array(
"结果"=>$c[$chuquan][$r],
"系统"=>$r,
"你"=>$chuquan
);
return $result;
}

/* 输出赛马结果 */
function saima()
{
return mt_rand(1,6);
}

/* 输出赛马结果图片 */
function imgsaima($saima)
{
return '<img src="/theme/img/ma'.$saima.'.gif">';
}

/* 现行时间后5分钟 */
function timejian5()
{
return date("Y-m-j H:i",strtotime("5 minute"));
}


//打开网址
 function openu($url)
{
$ua='Opera/9.80 (Android; Opera Mini/7.8.33940/32.1214; U; zh) Presto/2.8.119';
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch,CURLOPT_USERAGENT,$ua);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$contents = curl_exec($ch);
curl_close($ch);
return $contents;
}

/* 生成骰子点数 */
function roll()
{  
return rand(1,6);  
}

/* 摇骰子 */
function touzi()
{
$a1=roll();
$a2=roll();
$a3=roll();
return $a1.','.$a2.','.$a3;
}

/* 计算大小单双 */
function jsdxds($touzi)
{
$js=explode(',',$touzi);
$a=$js['0'];
$b=$js['1'];
$c=$js['2'];
$jg=$a+$b+$c;
$jg=array($jg);
if($a==$b and $b==$c)
{
return 'baozi';
}else{
foreach($jg as $val){
if($val<=10){
$a="xiao,";
}else{
$a="da,";
}
if($val%2==1){
return $a."dan";
}else{
return $a."shuang";
}
}
}
}

/* 输出摇骰子图片 */
function imgtouzi($touzi)
{
$js=explode(',',$touzi);
$a=$js['0'];
$b=$js['1'];
$c=$js['2'];
return '<img src="./theme/img/'.$a.'.jpg"> <img src="/theme/img/'.$b.'.jpg"> <img src="/theme/img/'.$c.'.jpg">';
}


/* 随机生成战斗结果 */
function sjjg()
{
$sanjie[1]='神界';
$sanjie[2]='佛界';
$sanjie[3]='魔界';

$begin=1;
$end=3;
$limit=2;
$rand_array=range($begin,$end);
shuffle($rand_array);
$jg=array_slice($rand_array,0,$limit);
$jg1=$sanjie[$jg[0]];//输出随机的三界
$jg2=$sanjie[$jg[1]];//输出随机的三界


$zdjg[1]= $jg1.'武将单挑，取'.$jg2.'武将首级';
$zdjg[2]= $jg1.'山路埋伏，'.$jg2.'中伏惨败';
$zdjg[3]= $jg1.'偷袭佛界后方，'.$jg2.'士气大落';
$zdjg[4]= $jg1.'使用诈降计，'.$jg2.'中计受损';
$zdjg[5]= $jg1.'迂回攻击，'.$jg2.'侧翼受损';
$zdjg[6]= $jg1.'猛将直取'.$jg2.'主将，士气大振';
$zdjg[7]= $jg1.'趁夜劫粮，'.$jg2.'士气大减';
$zdjg[8]= $jg1.'全军冲锋，'.$jg2.'战线后退';
$zdjg[9]= $jg1.'得奇才军事，'.$jg2.'人心浮动';
$zdjg[10]= $jg1.'新得名将，'.$jg2.'士气低落';
$zdjg[11]= $jg1.'增兵前线，'.$jg2.'士气低落';
$zdjg[12]= $jg1.'深夜偷营，'.$jg2.'损失严重';
$zdjg[13]= $jg1.'奉旨讨逆，'.$jg2.'人心不稳';
$zdjg[14]= $jg1.'长驱直入，'.$jg2.'主将受伤';
$zdjg[15]= $jg1.'大受鼓舞，强攻'.$jg2.'大营';
$zdjg[16]= $jg1.'假传军令，'.$jg2.'中计内斗';
$zdjg[17]= $jg1.'援军到达，'.$jg2.'受夹击大败';
$zdjg[18]= $jg1.'调动船队突击，'.$jg2.'水军大败';
$zdjg[19]= $jg1.'使用火计，'.$jg2.'中计损兵';
$zdjg[20]= $jg1.'阵法精奇，'.$jg2.'无以应对';

$begin1=1;
$end1=20;
$limit=1;
$rand_array1=range($begin1,$end1);
shuffle($rand_array1);
$zdjgsj=array_slice($rand_array1,0,$limit);
$zdjgsj=$zdjgsj[0];
return $zdjg[$zdjgsj];
}

?>