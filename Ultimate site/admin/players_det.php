<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];
$action = $_REQUEST['action'];

$id = digits_only($_REQUEST['id']);

if (!$rights['all_rights']) {
	if ($action=="delete"||$action=="activate") {
	    $sql = "SELECT id_team FROM players WHERE id=$id";
	    $rst = $db->sql_query($sql);
	    $line = $db->sql_fetchrow($rst);
	    if (!$rights['team_'.$line['id_team'].'_edit']) {
	        header("location:login.php");
	        exit;
	    }
    }
}

if($_REQUEST['action']=="delete" && $id)
{	if (digits_only($_REQUEST['tid']))		$db->sql_query("update players set id_team=0 where id=$id");
	else
		$db->sql_query("update players set active=0 where id=$id");
	header("location:players.php");
	exit;
}else if($_REQUEST['action']=="activate") {
	$db->sql_query("update players set active=1 where id=$id");
	header("location:players.php");
	exit;
}else if($_REQUEST['action']=="save")
{
	$id = digits_only($_POST['id']);
	$id_team = digits_only($_POST['id_team']);
    if ($rights['all_rights']||$rights['team_'.$id_team.'_edit']) {
	    $rst = $db->sql_query("SELECT username FROM phpbb_users WHERE user_id=$id");
	    $line = $db->sql_fetchrow($rst);
	    $name = $line['username'];
	    $rst = $db->sql_query("SELECT * FROM players WHERE id=$id");
	    if ($db->sql_affectedrows($rst)) {
		    $db->sql_query("UPDATE players SET active=1, id_team=$id_team WHERE id=$id");
	    } else {
		    $db->sql_query("INSERT INTO players (id, Nick, Name, id_team, active )VALUES ($id, '$name', '$name', $id_team, 1)");
	    }
    }
	header("location:players.php");
	exit;
}

	$art_res=$db->sql_query("SELECT * FROM phpbb_users LEFT JOIN players ON user_id=id WHERE id_team=0 ORDER BY username");
	$p = array();
   	while ($line=$db->sql_fetchrow($art_res))   		$p[strtolower($line['username'])] = $line;
   	ksort($p);?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;">

<link href="css/admin.css" rel="stylesheet">
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0" cellspacing="1" cellpadding="0" align="center">
<tr><td height="50 px" colspan="2" class="light">
<!-- Top -->
<?include "include/top.php"?>
<!-- End of Top -->
</td></tr>
<tr>
<td valign="top" class="light">
<!-- Left Menu -->
<?include "include/menu.php"?>
<!-- End of Left Menu -->
</td>
<td width="100%" align="left" valign="top" class="light">
<!-- Main part -->

<form action="players_det.php" method="post">
<input type="hidden" name="action" value="save">
<table border="0" cellspacing="10" align="left">
<tr>
  <td align="right" width="50%">Выберите игрока</td>
  <td><select name="id">
    <?
    	foreach ($p as $p1)
        	print "<option value=\"".$p1['user_id']."\">".$p1['username']."</option>";
    ?>
    </select>
  </td>
</tr>
<tr>
  <td align="right">Выберите команду</td>
  <td><select name="id_team" style="width:150px;">
  	<option value="0">-нет команды-</option>
  	<?
    $rst = $db->sql_query("SELECT * FROM teams ORDER BY id");
    while ($line = $db->sql_fetchrow($rst)) {
    	if ($rights['all_rights']||$rights['team_'.$line['id'].'_edit']) {
	        print "<option value=\"".$line['id']."\"";
	        if ($line['id']==$m_id_team) print " selected=\"selected\"";
	        print ">".$line['team_name']."</option>";
        }
    }

	?></select>
  </td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" value="Добавить" class="inp"></td></tr>
</table>
</form>


<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>