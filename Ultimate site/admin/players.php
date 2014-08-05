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
	$sql = "SELECT * FROM teams";
    $rst = $db->sql_query($sql);
    $access = false;
    while ($line = $db->sql_fetchrow($rst))
    	if ($rights['team_'.$line['id'].'_edit'])
        	$access = true;
    if (!$access) {
	    header("location:login.php");
	    exit;
    }
}

	$art_res=$db->sql_query("
		SELECT p.id as pid, p.*, t.id as tid, t.team_name, u.username
		FROM players AS p
		LEFT JOIN teams AS t ON p.id_team=t.id
		LEFT JOIN phpbb_users AS u ON p.id=u.user_id
		WHERE p.active=1 AND p.is_ukr=1
		ORDER BY t.team_rating DESC, t.id_division ");

	$title = "Игроки";
	include "include/header.php";
?>

<table border="0" cellspacing="1" align="left">
	<tr><td colspan="4" class="light">
	    <form action="players_det.php" method="post">
	        <input type="hidden" name="action" value="add">
	        <input type="submit" value="Добавить игрока" class="inp">
	    </form>
	</td></tr>
	<?
    	$team_name = "";
		while($row=$db->sql_fetchrow($art_res)) {
        	if ($row['team_name']!=$team_name) {
            	print "<tr><td colspan=\"3\"><br /></td></tr>";
            	print "<tr><td colspan=\"3\"><a id=\"".$row['tid']."\"></a><h4>$row[team_name]</h4></td></tr>";
                $team_name = $row['team_name'];
            }
	?>

    <tr>
        <td class="light"><?="<b>".$row['Surname']." ".$row['Name']."</b><br />".$row['username'].""?>
        </td>
        <td class="light"><?=$row['team_name']?>
        </td>

        <td width="10%" nowrap class="light">
            <form action="players_det.php" method="post" name="activate<?=$row["pid"]?>" id="activate<?=$row["pid"]?>">
                <input type="hidden" name="action" value="activate">
                <input type="hidden" name="id" value="<?=$row['pid']?>">
            </form>
            <form action="players_det.php" method="post" name="delete<?=$row["pid"]?>" id="delete<?=$row["pid"]?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?=$row['pid']?>">
                <input type="hidden" name="tid" value="<?=$row['tid']?>">
            </form>
            <? if ($rights['all_rights']||$rights['team_'.$row['tid'].'_edit']) { ?>
            <? /* if (!$row['active']) { ?><input type="button" value="Показать" class="inp" onClick="document.forms.activate<?=$row["pid"]?>.submit(); return false;"> <? } */ ?>
            <? if ($row['tid']) { ?>
            	<input type="submit" value="Выгнать" class="inp red" onClick="document.forms.delete<?=$row["pid"]?>.submit(); return false;">
            <? } else { ?>
            	<input type="submit" value="Удалить" class="inp red" onClick="document.forms.delete<?=$row["pid"]?>.submit(); return false;">
           	<? } ?>
            <? } ?>
        </td>
    </tr>

	<?
		}
	?>

</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>