<?php
	include ("c_config.php");
	$mid = $_REQUEST['mid'];
	if (!$mid) exit;
	$id_user = sp_room($mid);
	if ($id_user == -1) exit;
  $id_user1 = $_REQUEST['uid'];
  $name = $_REQUEST['user'];
  $all = $_REQUEST['all'];
?>
<html><head><title>Пользователи :: Чат :: ULTIMATE ФРИЗБИ НА УКРАИНЕ (КИЕВ)</title></head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
</head>
<style>
	p {font-size: 12px; }
	.uname { font-size: 16px; font-weight: bold;}
	.uinfo { color: black; }
	a.nav {color: black; font-size: 11px; text-decoration: none;}
</style>
  <frameset cols='200,*' frameborder=0>
    <frame src=info1.php?mid=<?php echo $mid; ?>&uid=<?php echo $id_user1; ?>&user=<?php echo $name; ?>&all=<?php echo $all; ?> name=info1 scrolling='no' NORESIZE frameborder=0>
    <frame src=info2.php?mid=<?php echo $mid; ?>&uid=<?php echo $id_user1; ?>&user=<?php echo $name; ?>&all=<?php echo $all; ?> name=info2 frameborder=0>
  </frameset>
</html>