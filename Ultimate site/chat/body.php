<?php
	include ("c_config.php");
	if (getenv("REQUEST_METHOD")!='GET'){exit;}
	if (isset($_GET['mid'])){$mid = $_GET['mid']; $id_user=sp_room($mid); $name=get_user_param($id_user,"name");}
	else{$id_user=-1;}
	if ($id_user == -1){ exit;}

	include ("vars.php");
?><html><head>
<META HTTP-EQUIV="Expires" CONTENT=Sun", 1 Mar 2099 00:00:05 GMT">
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link rel="stylesheet" href="style.css" type="text/css">
<style>
body { font-size:14px; }
a.lnk {color: black; font-size: 12px; font-weight: bold;font-family: tahoma;}
</style>
</head>
<script language="JavaScript">function f(Top2Bot) {
	var txt='';
	parent.msgs = parent.new_msgs.concat(parent.msgs);
  work_array = parent.msgs.slice(0, <?php print ($lines_on_main_frame-1); ?>);
  if (Top2Bot) work_array.reverse();
  txt='';
	for (i=0; i<work_array.length; i++) {
  	txt = txt+work_array[i]+'<br>';
  }
	document.all["chat"].innerHTML = txt;
  if (Top2Bot) {
    parent.bodyframe.scroll(1,20000000);
  }
}</script>

<body><div id=chat>
<layer name=chat><?php print $str_body_load; ?>
</layer></div>
<script language="JavaScript">
parent.hiddenframe.location.href='hiddenframe.php?mid=<?php print $mid; ?>&lmid=&lut=';
</script>
</body></html>