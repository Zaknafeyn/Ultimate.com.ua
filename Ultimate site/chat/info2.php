<?php
	include ("c_config.php");
	$mid = $_REQUEST['mid'];
	if (!$mid) exit;
	$id_user = sp_room($mid);
	if ($id_user == -1) exit;
	$rights = get_user_param($id_user, "rights");
  $id_user1 = 0;
  if (isset($_GET['uid'])) $id_user1=$_GET['uid'];
  if (isset($_POST['uid'])) $id_user1=$_POST['uid'];
//	$id_user1 = $_REQUEST['uid'];
  $user1 = "";
  if (isset($_GET['user'])) $user1=$_GET['user'];
  if (isset($_POST['user'])) $user1=$_POST['user'];
  $all = 0;
  if (isset($_GET['all'])) $all=$_GET['all'];
  if (isset($_POST['all'])) $all=$_POST['all'];
  if ($id_user1==0) {
	  if ($all==0)
	    $id_user1 = get_user_id_by_name($user1);
	  else
	    $id_user1 = -1;
  }
  $allowed_order = array("name"=>0, "sex"=>1, "regdate"=>2, "ltime"=>3, "days"=>4);
	$order="days";
  if (isset($_GET['order'])) $order=$_GET['order'];
  if (isset($_POST['order'])) $order=$_POST['order'];
  if (!array_key_exists($order, $allowed_order))
		$order="days";
  $desc="DESC";
  if (isset($_GET['desc'])) $desc=$_GET['desc'];
  if (isset($_POST['desc'])) $desc=$_POST['desc'];
  if ($desc!="ASC") $desc="DESC";
?>
<html><head><title>Пользователи :: Чат :: ULTIMATE ФРИЗБИ НА УКРАИНЕ (КИЕВ)</title></head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
</head>
<style>
	p {font-size: 12px;}
	.uname { font-size: 16px; font-weight: bold;}
	.uinfo { font-weight: bold; }
	a.nav {color: black; font-size: 11px; text-decoration: none;}
</style>
<body><center>
<?php
	$page = 0;
  if (isset($_GET['page']))	$page = $_GET['page'];
  $users_per_page = $users_per_column;

	$sql = "SELECT * FROM my4_users";
  if (!$all) $sql .= " WHERE id_user=$id_user1";
  $sql .= " ORDER BY $order $desc";
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

  $co = 0;
	$k = 0;
  $uinfo = array();
  while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
  	$id_user1 = $line['id_user'];
    $name = $line['name'];
    if ( (!$user1) || (strstr(strtoupper($name),strtoupper($user1))) ) {

    	// надо пропустить первых $page*$users_per_page пользователей
      if ( ($co>=$page*$users_per_page) && ($co<($page+1)*$users_per_page) ) {
      	$uinfotmp = array();
      	$uinfotmp["id"] = $id_user1;
      	$uinfotmp["name"] = $name;
      	$uinfotmp["sex"] = $line['sex'];
      	$uinfotmp["dr"] = $line['dr'];
      	$uinfotmp["info"] = $line['info'];
      	$uinfotmp["regdate"] = $line['regdate'];
      	$uinfotmp["email"] = $line['email'];
      	$uinfotmp["icq"] = $line['icq'];
      	$uinfotmp["city"] = $line['city'];
      	$uinfotmp["days"] = $line['days'];
      	$uinfotmp["avatar"] = $line['avatar'];
        $uinfotmp["ltime"] = $line['ltime'];
        $uinfotmp["photo"] = $line['photo'];
	if (($rights=='guard')||($rights=='admin')) {
	    $sql1 = "SELECT ip FROM my4_log WHERE id_user=$id_user1";
	    $rst1 = $db->sql_query($sql1) or die("<p>$sql1<p>".mysql_error());
	    if ($db->sql_affectedrows($rst1)) {
		$line1 = $db->sql_fetchrow($rst1, MYSQL_NUM);
		$uinfotmp["ip"] = "<a>$id_user1</a>&nbsp;:&nbsp;id<br><a class=lnk href=\"http://www.frisbee.by/ipinfo.php?ip=$line1[0]\" target=_blank>$line1[0]</a>&nbsp;:&nbsp;ip";
	    } else {
		$uinfotmp["ip"] = "";
	    }

	} else {
	    $uinfotmp["ip"] = "";
	}

      	array_push($uinfo, $uinfotmp);

	      $k++;
      }
      $co++;
    }
  }

  print "<p>$str_info_found: <font class=uinfo>$co</font>";

  $num_pages = ceil($co/$users_per_page);
  if ($num_pages>1) {
    $nav = "<p align=center><font color=gray>"; // навигация
    for ($i=0;$i<$num_pages;$i++) {
      $nav .= " <a class=nav href=\"info2.php?mid=$mid&page=$i&user=$user1&all=$all&order=$order&desc=$desc\">";
      if ($i == $page) $nav .= "<font color=white size=+1>";
      $nav .= $i+1;
      if ($i == $page) $nav .= "</font>";
      $nav .= "</a> ";
    }
    $nav .= "</font>";
    print $nav;
	}

	print "<table width=100% cellpadding=5>";
  print "<tr><td valign=top width=50%>";
  $k=0;
  foreach ($uinfo as $userinfo) {
    print "<p><table style=\"border: 1px solid #409C27; \" width=100% cellspacing=1 cellpadding=2>";
    print "<tr><td colspan=2 bgcolor=#7DCF23>";
    print "<table cellspacing=0 cellpadding=0 border=0><tr><td width=99%><p>";
    print ($userinfo["avatar"])?"&nbsp;<a href=info2.php?mid=$mid&uid=".$userinfo["id"]."><img src=\"avatars/".$userinfo["avatar"]."\" style=\"vertical-align:middle; border: none;\"></a>":"&nbsp;<a href=info2.php?mid=$mid&uid=".$userinfo["id"]."><img src=\"images/i_".$userinfo["sex"].".gif\" style=\"vertical-align:middle; border: none;\"></a>";
	  print "<font class=uname color=".$sex_color[$userinfo["sex"]].">&nbsp;".$userinfo["name"]."</font>";
    print ($userinfo["days"]) ? "&nbsp;&nbsp;&nbsp;".$str_info_days2.":&nbsp;<font class=uinfo>".$userinfo["days"]."</font>&nbsp;</td>" : "</td>";
    print "<td align=right>".$userinfo["ip"]."</td></tr></table></td></tr>";
    print "<tr><td align=center valign=top width=150><img src=photo/";
    print ($userinfo["photo"]) ? $userinfo["photo"] : "nofoto.gif";
    print "></td>";
    print "<td valign=top><table>";
    print ($userinfo["regdate"]) ? "<tr><td valign=top><p>".$str_info_regdate2.":</td><td><p><font class=uinfo>".date("d.m.Y H:i:s", $userinfo["regdate"])."</font></td><tr>":"";
    print ($userinfo["ltime"]) ? "<tr><td valign=top><p>".$str_info_lastvisit2.":</td><td><p><font class=uinfo>".date("d.m.Y H:i:s", $userinfo["ltime"])."</font></td></tr>":"";
    print ($userinfo["dr"]) ? "<tr><td valign=top><p>".$str_info_dr2.":</td><td><p><font class=uinfo>".$userinfo["dr"]."</font></td></tr>":"";
    print ($userinfo["email"]) ? "<tr><td valign=top><p>".$str_info_email2.":</td><td><p><font class=uinfo>".preg_replace("/@/"," # ",$userinfo["email"])."</font></td></tr>":"";
    print ($userinfo["icq"]) ? "<tr><td><p>".$str_info_icq2.":</td><td><p><font class=uinfo>".$userinfo["icq"]."</font></td></tr>":"";
    print ($userinfo["city"]) ? "<tr><td><p valign=top>".$str_info_city2.":</td><td><p><font class=uinfo>".$userinfo["city"]."</font></td></tr>":"";
    print ($userinfo["info"]) ? "<tr><td colspan=2><p>".$str_info_about2.": <font class=uinfo>".check_url_etc(sp_add_smile($userinfo["info"]),$userinfo["id"])."</font></td></tr>":"";
    print "</table>";
    print "</td></tr></table>";
    $k++;
//    if ($k == $users_per_column) print "</td><td width=50% valign=top>";
    if ($k == ($users_per_column) ) break;
  }
?>
  </td></tr>
	<!--<tr><td colspan=2 align=center><p><input class=b name=b_quit type=button value='Закрыть' onClick='javascript:window.close();' style='color: white; background: coral'></td></tr>-->
  </table>


</body></html>