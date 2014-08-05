<?php
	include ("c_config.php");
	$mid = $_REQUEST['mid'];
	if (!$mid) exit;
	$id_user = sp_room($mid);
	if ($id_user == -1) exit;

  $day = $_REQUEST['day'];
  if (!$day)
  	$day=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
  else
  	$day=mktime(0,0,0,date("m",$day),date("d",$day),date("Y",$day));
?>
<html><head><title>Архив сообщений :: Чат :: ULTIMATE ФРИЗБИ НА УКРАИНЕ (КИЕВ)</title></head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
</head>
<style>
	p {font-size: 12px; color: gray; }
	.uname { font-size: 16px; font-weight: bold;}
	.uinfo { color: silver; }
	a.nav {color: gray; font-size: 11px; text-decoration: none;}
</style>
  <frameset rows='100,*' frameborder=0>
    <frame src=archive1.php?mid=<?php echo $mid; ?>&day=<?php echo $day; ?> name=archive1 frameborder=0>
    <frame src=archive2.php?mid=<?php echo $mid; ?>&day=<?php echo $day; ?> name=archive2 frameborder=0>
  </frameset>
</html>

