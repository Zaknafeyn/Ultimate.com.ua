<?php
include ("c_config.php");
	$mid = $_REQUEST['mid'];
  if (!$mid) exit;
  $id_user = sp_room($mid);
  if ($id_user == -1) exit;
  $name = get_user_param($id_user, "name");

	$page='';
	if (isset($_GET['page'])){$page = $_GET['page'];}
  if ($page=='') $page=0;
  $mes_per_page = 10;

	$msg='';
	if (isset($_POST['msg'])){$msg = $_POST['msg'];}

	if ($msg) {
	    $who = "<font color=".get_user_param($id_user,"nick_color")."><b>$name</b>:</font>";
	    $message = "<font color=".get_user_param($id_user,"mes_color").">".check_url_etc(sp_add_smile($msg), $id_user)."</font>";
	    $message = preg_replace("/$/m","<br>",$message);
	    $message = substr($message,0,strlen($message)-4);

	    $sql = "INSERT INTO my4_board (who,message,mtime) VALUES('$who','$message',".time().")";
	    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());


       header("Location: boardbody.php?mid=$mid&msg=");
	}
?>
<html><head><title>Доска :: Чат :: ULTIMATE ФРИЗБИ НА УКРАИНЕ (КИЕВ)</title>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
<style>
p {font-family:Tahoma;font-size:13px; margin-top: 0; margin-bottom: 5;}
a{color: white;font-size: 12px;font-weight: bold;font-family: tahoma;}
a.nav {color: gray; font-size: 11px; text-decoration: none;}
</style>
</head>
<body>
<table border=0 width=100%>
	<tr>
  	<td></td>
    <td width=550><center>
			<form name=boardform action=boardbody.php method=post>
				<input name='mid' type=hidden value='<?php echo $mid; ?>'>
				<textarea name='msg' rows="5" cols="37"></textarea><br><br>
			  <input type="submit" name="Submit" value="<?php print $str_board_button_say; ?>">
			</form></center>
			<?php
	      $sql = "SELECT COUNT(id) FROM my4_board";
	      $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
        $line = $db->sql_fetchrow($rst, MYSQL_NUM);
        $co = $line[0];
        $start = $page*$mes_per_page;
	      $sql = "SELECT * FROM my4_board ORDER BY -mtime LIMIT $start, $mes_per_page";
	      $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
        while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
          print "<p>$line[who] $line[message]";
          if ($line['mtime'])
	          print "<br><font style=\"font-size: 11px;\" color=gray><i>(".date("d M Y, H:i:s", $line['mtime']).")</i></font>";
	      }
        $num_pages = ceil($co/$mes_per_page);
        if ($num_pages>1) {
	        $nav = "<p align=center><font color=gray>"; // навигация
	        for ($i=0;$i<$num_pages;$i++) {
  	      	$nav .= " <a class=nav href=\"boardbody.php?mid=$mid&page=$i\">";
    	      if ($i == $page) $nav .= "<font color=white size=+1>";
      	    $nav .= $num_pages-$i;
	          if ($i == $page) $nav .= "</font>";
  	        $nav .= "</a> ";
    	    }
  	      $nav .= "</font>";
    	    print $nav;
        }
			?>
    </td>
  	<td></td>
</body></html>