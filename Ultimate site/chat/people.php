<?php
include ("c_config.php");
if (getenv("REQUEST_METHOD")!='GET'){exit;}
if (isset($_GET['mid'])){$mid = $_GET['mid']; $id_user = sp_room($mid); $name = get_user_param($id_user, "name"); }
else { $id_user = -1; }

if (!isset($id_user)){$id_user=-1;}
if ($id_user == -1){ sp_pe(1);}
sp_check_users_ip_name($mid);

$my_name_count = 0;
?>
<html><head>
</head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
<style>
a{
	color: white;
  font-size: 12px;
	font-weight: bold;
  font-family: tahoma;
  text-decoration: none;
}
a:hover{text-decoration: underline;}
td{font-family: Tahoma, Times;font-size: 10px;}
body{
 background-color: #409C27;
 scrollbar-face-color:#409C27;
 scrollbar-highlight-color:#409C27;
 scrollbar-darkshadow-color:#409C27;
 scrollbar-3dlight-color:#409C27;
 scrollbar-arrow-color:#409C27;
 scrollbar-base-color:#409C27;
}
a.appl { text-decoration: none; font-weight: lighter; }
a.appl:hover { text-decoration: none; font-weight: lighter; }
</style>
<script language="JavaScript">
function f() {
	var txt='';
	for (i=0; i<parent.ppl.length; i++) {
  		txt += parent.ppl[i];
  	}
	document.all["pplframe"].innerHTML = txt;
}
</script>
<body bgcolor=#ffffff text=002200 link=0000ff>
<table border=0 width=100%><tr><td>
<div id=pplframe>
<layer name=pplframe><?php print $str_body_load; ?>
</layer></div>
<center><hr style="size:0">
</td></tr>
<tr><td>
<center>
<form name=f_status action=empty.php method=post target=f_empty framespacing=0 framepadding=0>
<input name=words type=hidden value='@changestatus'>
<br><a><?php print $str_people_status; ?>:</a><br><input class=i name='w_stat' type=text size=20 style='width=80px;'>
<input style="font-size: 10px; background: white; BORDER: gray 1px outset; color: black;" type=submit value='!'>
<input type=hidden name=mid value='<?php echo $mid; ?>'>
</form>
</center>
</td></tr></table></body></html>