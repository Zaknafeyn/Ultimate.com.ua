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

	$title = "Администраторы команд";
	include "include/header.php";


	$sql = "SELECT * FROM `teams`";
    $rst = $db->sql_query($sql);
    $a = array();
	while ( $row = $db->sql_fetchrow($rst) ) {
        $a["team_".$row['id']."_edit"]=array("team_name"=>$row['team_name'],"val"=>array());
    }

	$sql = "SELECT *
        FROM `admins` a
        LEFT JOIN phpbb_users u
        ON a.id_forum_user=u.user_id
        WHERE all_rights<>1";
    $rst = $db->sql_query($sql);
    $names = array();
	while ( $row = $db->sql_fetchrow($rst) ) {
    	foreach ($row as $key=>$val) {
        	if (substr($key,0,4)=="team") {
            	array_push($a[$key]["val"], $val);
            }
        }
    	array_push($names, array($row['id'],$row["username"]));
    }
?>
<table width="100%" border="0" cellspacing="1" align="left" valign="top">
<tr><td class="light">
	<form action="admins_det.php" method="post">
	    <input type="hidden" name="action" value="add">
	    <input type="submit" value="Новый администратор" class="inp">
	</form>
</td></tr>
<tr><td>
<form action="admins_teams_det.php" method="post">
	<table width="100%" border="0" cellspacing="1" align="left" valign="top">
	<tr><td width="10%" class="dark"></td>
	<?
	    foreach ($names as $value) {
	        print "<td class=\"dark\" width=\"10%\"><h3><font color=white>".$value[1]."</font></h3></td>";
	    }
	?>
	</tr>
	<?
	    foreach ($a as $key1=>$value1) {
	        print "<tr>";
	        print "<td class=\"light\" style=\"padding: 5\">".$value1["team_name"]."</td>";
            $i=0;
	        foreach ($value1["val"] as $value2) {
	            print "<td class=\"light\" align=\"center\">";
                print "<input type=\"checkbox\" name=\"cb[".$names[$i][0]."][".$key1."]\" value=\"\"";
                if ($value2) print " checked";
                print "></td>";
                $i++;
            }
	        print "</tr>";
	    }
	?>
    <tr><td colspan="<?=$i+1?>" align="center">
    	<input type="submit" value="Сохранить" class="inp">
    </td></td>
	</table>
</form>
</td></tr>

</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>