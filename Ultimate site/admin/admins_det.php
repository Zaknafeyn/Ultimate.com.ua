<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];

if (!$rights['all_rights']) {
    header("location:login.php");
    exit;
}

$action = $_REQUEST['action'];

if($action=="delete" && $_REQUEST['id']!="")
{
	$db->sql_query("delete from admins where id=".$_REQUEST['id']);
	header("location:admins.php");
	exit;
} else if($action=="save")
{
	if (isset($_REQUEST['nick'])) $nick = $_REQUEST['nick']; else $nick="";
    if ($nick) {
        $sql = "SELECT user_id FROM phpbb_users WHERE username='$nick'";
        $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
        if ($db->sql_affectedrows($rst)) {
        	$line = $db->sql_fetchrow($rst);
			$db->sql_query("insert into admins (id_forum_user) values(".$line['user_id'].")");
        }
    } else
		$db->sql_query("insert into admins (id_forum_user) values(".$_REQUEST['id'].")");
	header("location:admins.php");
	exit;
} else if($action=="check")
{
	if (isset($_POST['cb'])) {
    	$cb = $_POST['cb'];
        $sql = "SELECT * FROM admins";
        $rst = $db->sql_query($sql);
        while ($line = $db->sql_fetchrow($rst)) {
        	foreach($line as $key=>$val) {
            	if ($key!="id"&&$key!="id_forum_user"&&substr($key,0,5)!="team_") {
                	if (isset($cb[$line['id']][$key]))
                    	$db->sql_query("UPDATE admins SET ".$key."=1 WHERE id=".$line['id']);
                    else
                    	$db->sql_query("UPDATE admins SET ".$key."=0 WHERE id=".$line['id']);
                }
            }
        }
		header("location:admins.php");
		exit;
    }
}

	$art_res=$db->sql_query("SELECT * FROM phpbb_users LEFT JOIN admins ON user_id=id WHERE id IS NULL ORDER BY username");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;">

<link href="css/admin.css" rel="stylesheet">
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" cellspacing="1" cellpadding="0" align="center">
<tr><td height="50 px" colspan="2" class="light">
<!-- Top -->
<?include "include/top.php"?>
<!-- End of Top -->
</td></tr>
<tr><td width="100%" height="2" colspan="2" class="dark">
</td></tr>
<tr>
<td valign="top" class="light">
<!-- Left Menu -->
<?include "include/menu.php"?>
<!-- End of Left Menu -->
</td>
<td width="100%" align="left" valign="top" class="light">
<!-- Main part -->

<form action="admins_det.php" method="post">
<input type="hidden" name="action" value="save">
<table width="100%" border="0" cellspacing="2" align="left">
<tr>
	<td>Выберите пользователя форума&nbsp;
	    <select name="id">
	    <?
	        while ($line=$db->sql_fetchrow($art_res)) {
	            print "<option value=\"$line[user_id]\">$line[username]</option>";
	        }
	    ?>
	    </select>
	</td>
	<td>
    	...или введите его ник&nbsp;<input name="nick" style="width:150px">
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