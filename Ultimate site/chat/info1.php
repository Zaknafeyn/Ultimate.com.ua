<?php
	include ("c_config.php");
	$mid = $_REQUEST['mid'];
	if (!$mid) exit;
	$id_user = sp_room($mid);
	if ($id_user == -1) exit;
	$id_user1 = $_REQUEST['uid'];
  if ( (!$id_user1) || ($id_user1==-1) ) {
  	$name = $_REQUEST['user'];
    if ($name)
	    $id_user1 = get_user_id_by_name($name);
  } else $name = get_user_param($id_user1,"name");
  $all = $_REQUEST['all'];
?>
<html><head><title>Пользователи :: Чат :: ULTIMATE ФРИЗБИ НА УКРАИНЕ (КИЕВ)</title></head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
</head>
<style>
	p {font-size: 12px;}
</style>
<body><center>
	<form action='info2.php' target=info2 method=post>
  	<p><b>Найти пользователя</b>
  	<input type=hidden name='mid' value='<?php print $mid; ?>'>
  	<p><input class=i style="font-size: 14px;" name='user' value='<?php print $name; ?>'>
    <p><input type=checkbox name='all' value='1'<?php if ($all==1) print " checked";?>> <?php print $str_info_findall; ?>
    	<br><font size=-2 color=gray><?php print $str_info_findall_descr; ?></font>
    <p><?php print $str_info_orderby; ?>:
    <br><select class=i name=order>
			<option value='name'><?php print $str_info_nick; ?></option>
			<option value='sex'><?php print $str_info_sex; ?></option>
			<option value='regdate'><?php print $str_info_regdate; ?></option>
			<option value='ltime'><?php print $str_info_lastvisit; ?></option>
			<option value='days' selected><?php print $str_info_days; ?></option>
		</select>
    <p><input type=checkbox name='desc' value='DESC' checked> <?php print $str_info_order_desc; ?>

    <p><input class=b type=submit value='<?php print $str_info_button_find; ?>'>
  </form>
</body></html>