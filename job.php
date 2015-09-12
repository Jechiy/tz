<?
include('db.php');
include('conn.php');
include('system.php');
$kj=$_GET['kj'];

if($kj == "job")
{
openu('http://'.$_SERVER['SERVER_NAME'].'/job.php?kj=saima');

openu('http://'.$_SERVER['SERVER_NAME'].'/job.php?kj=touzi');

openu('http://'.$_SERVER['SERVER_NAME'].'/job.php?kj=sanjiedou');

echo 'ok';
}


/* 赛马开奖 */
if($kj == "saima")
{
unlink('saimadb.txt');
$saimajg=saima();
$a1=$DB->select('*','saima',"WHERE youma LIKE '%$saimajg%'")->fetchALL();
foreach($a1 as $row1){
$kjuser=$row1['user'];//输出押注成功用户
$mamoney=$row1['ma'.$saimajg];//取赌注金币
$row0=$DB->select('*','user',"WHERE user='$kjuser'")->fetchALL();
$row0=$row0['0'];
$money1=$row0['rmb'];//取用户原来金币
$q=$money1;
$dz=$mamoney*2;
$dzjg=$q+$dz;
$DB->update('user',array('rmb'=>$dzjg),"WHERE user='$kjuser'");
}
echo '赛马:开'.$saimajg.'号马'.imgsaima($saimajg);
$DB->query("delete from saima");
date_default_timezone_set("PRC");
$time = date("Y-m-j H:i");
$c=$saimajg.'|'.$time;
file_put_contents('saimadb.txt', $c, FILE_APPEND);
}


/* 赌骰子开奖 */
if($kj == "touzi")
{
unlink('touzidb.txt');
//$saizi=touzi();//摇骰子
$saizi='6,6,1';//摇骰子
$saizijg=jsdxds($saizi);//计算大小单双

if($saizijg == "baozi")
{
$a1=$DB->select('*','touzi',"WHERE dxds LIKE '%$saizijg%'")->fetchALL();
foreach($a1 as $row){
$kjuser=$row['user'];//输出押注成功用户
$mamoney=$row[$saizijg.'money'];//取赌注金币
$row0=$DB->select('*','user',"WHERE user='$kjuser'")->fetchALL();
$row0=$row0['0'];
$money1=$row0['rmb'];//取用户原来金币
$q=$money1;
$dz=$mamoney*10;
$dzjg=$q+$dz;
$DB->update('user',array('rmb'=>$dzjg),"WHERE user='$kjuser'");//添加用户金币
}
}else{
$jg=explode(',',$saizijg);
$jg1=$jg[0];
$jg2=$jg[1];

$b=$DB->select('*','touzi',"WHERE dxds LIKE '%$jg1%'")->fetchALL();
$c=$DB->select('*','touzi',"WHERE dxds LIKE '%$jg2%'")->fetchALL();

foreach($b as $row1){
$kjuser1=$row1['user'];//输出押注成功用户
$mamoney1=$row1[$jg1.'money'];//取赌注金币
$row1=$DB->select('*','user',"WHERE user='$kjuser1'")->fetchALL();
$row1=$row1['0'];
$money2=$row1['rmb'];//取用户原来金币
$q1=$money2;
$dz1=$mamoney1*2;
$dzjg1=$q1+$dz1;
$DB->update('user',array('rmb'=>$dzjg1),"WHERE user='$kjuser1'");//添加用户金币
}

foreach($c as $row2){
$kjuser2=$row2['user'];//输出押注成功用户
$mamoney2=$row2[$jg2.'money'];//取赌注金币
$row2=$DB->select('*','user',"WHERE user='$kjuser2'")->fetchALL();
$row2=$row2['0'];
$money3=$row2['rmb'];//取用户原来金币
$q2=$money3;
$dz2=$mamoney2*2;
$dzjg2=$q2+$dz2;
$DB->update('user',array('rmb'=>$dzjg2),"WHERE user='$kjuser2'");//添加用户金币
}
}

echo '赌骰子:开'.$saizi.$saizijg.imgtouzi($saizi);
$DB->query("delete from touzi");
date_default_timezone_set("PRC");
$time = date("Y-m-j H:i");
if($saizijg == "baozi")
{
$saizijg5='豹子';
}else{
$saizijg=explode(',',$saizijg);
$saizijg1=$saizijg[0];
$saizijg2=$saizijg[1];
if($saizijg1 == "da")
{
$saizijg3='大';
}elseif($saizijg1 == "xiao")
{
$saizijg3='小';
}elseif($saizijg1 == "dan")
{
$saizijg3='单';
}elseif($saizijg1 == "shuang")
{
$saizijg3='双';
}

if($saizijg2 == "da")
{
$saizijg4='大';
}elseif($saizijg2 == "xiao")
{
$saizijg4='小';
}elseif($saizijg2 == "dan")
{
$saizijg4='单';
}elseif($saizijg2 == "shuang")
{
$saizijg4='双';
}
}
$saizijg=$saizijg3.$saizijg4.$saizijg5;
$c1=$saizi.'/'.$saizijg.'|'.$time;
file_put_contents('touzidb.txt', $c1, FILE_APPEND);
}

/* 三界斗 */
if($kj == "sanjiedou")
{
$shenjiemoney1=$DB->select('sum(shenjie)','sanjiedou')->fetchALL();
$shenjiemoney1=$shenjiemoney1['0']['sum(shenjie)'];
$fojiemoney1=$DB->select('sum(fojie)','sanjiedou')->fetchALL();
$fojiemoney1=$fojiemoney1['0']['sum(fojie)'];
$mojiemoney1=$DB->select('sum(mojie)','sanjiedou')->fetchALL();
$mojiemoney1=$mojiemoney1['0']['sum(mojie)'];
$a=$shenjiemoney1;
$b=$fojiemoney1;
$c=$mojiemoney1;

$d=$a + $b + $c ;

if($d == "0")
{
echo '结束';
}else{
if($a === $b and $b === $c)
{
$r1=timejian5();
$DB->update('time',array('sanjiedoutime'=>$r1));
echo '结束';
}
unlink('sanjiedoudb.txt');
function compare(&$x,&$y)//计算大小
{
if($x>$y){
$temp=$y;
$y=$x;
$x=$temp;
}
}
compare($a,$b);//  a,b 中小的值存a 大的值存在b
compare($a,$c);//   a,c 中小的值存a 大的值存在//到这一步 a中值最小
compare($b,$c);//   b,c 中小的值存b 大的值存在  //到这一步步 c中值最大
$jg_array=array("shenjie"=>$shenjiemoney1,"fojie"=>$fojiemoney1,"mojie"=>$mojiemoney1);
$jg1=array_search($c,$jg_array);
$a1=$DB->select('*','sanjiedou',"WHERE youjie LIKE '%$jg1%'")->fetchALL();
foreach($a1 as $row1){
$kjuser=$row1['user'];//输出押注成功用户
$mamoney=$row1[$jg1];//取赌注金币
$row2=$DB->select('*','user',"WHERE user='$kjuser'")->fetchALL();
$row2=$row2['0'];
$money1=$row2['rmb'];//取用户原来金币
$q=$money1;
$dz=$mamoney*2.9;
$dzjg=$q+$dz;
$dzjg=ceil($dzjg);
$DB->update('user',array('rmb'=>$dzjg),"WHERE user='$kjuser'");//添加用户金币
}
echo '三界斗结果:'.$jg1;
$DB->query("delete from sanjiedou");
date_default_timezone_set("PRC");
$time1 = date("Y-m-j H:i");
if($jg1 == "shenjie")
{
$shili11='神界';
}elseif($jg1 == "fojie")
{
$shili11='佛界';
}elseif($jg1 == "mojie")
{
$shili11='魔界';
}
$c1=$shili11.'|'.$time1;
file_put_contents('sanjiedoudb.txt', $c1, FILE_APPEND);
}
}


?>