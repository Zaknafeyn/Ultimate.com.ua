
<?php
//	include("../tmpl/init.php");
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
<html><head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
<style>
p {font-family:Tahoma;font-size:13px; margin-top: 0; margin-bottom: 5;}
a{color: black;font-size: 12px;font-weight: bold;font-family: tahoma;}
a.nav {color: black; font-size: 10px; text-decoration: none;}
</style>
</head>
<body>
<table border=0 width=100%>
	<tr>
  	<td>
    <?php
	    $sql = "SELECT MIN(mtime), MAX(mtime) FROM my4_messages";
	    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	    $line = $db->sql_fetchrow($rst, MYSQL_NUM);
	    $low_date = mktime(0,0,0,date("m",$line[0]),date("d",$line[0]),date("Y",$line[0]));
	    $high_date = mktime(0,0,0,date("m",$line[1]),date("d",$line[1]),date("Y",$line[1]));
	    $d = $high_date;
	    while ($d>=$low_date) {
      	print "<a class=nav href=archive2.php?mid=$mid&day=$d target=archive2>";
        //if (date("Ymd",$d)==date("Ymd",$day)) print "<font color=white>";
        print date("d.m", $d);
        //if (date("Ymd",$d)==date("Ymd",$day)) print "</font>";
        print "</a> ";
	      $d -= 24*60*60;
	    }
    ?>
    </td>
  </tr>
</table>
</body></html>