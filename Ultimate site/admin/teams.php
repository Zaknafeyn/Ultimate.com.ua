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

$art_res=$db->sql_query("SELECT t.id as tid, team_name, city, division
	FROM `teams` AS t
	LEFT JOIN cities AS c ON t.id_city=c.id
	LEFT JOIN divisions AS d ON t.id_division=d.id
    order by division ASC, team_rating DESC");

	$title = "Команды";
	include "include/header.php";

?>

<table width="100%" border="0" cellspacing="1" cellpadding="10" align="left">
<?
while($row=$db->sql_fetchrow($art_res)):
	if ($rights['all_rights']||($rights['team_'.$row['tid'].'_edit'])) {
?>

<tr>
<td class="light">
<?
		print "<b>".$row['team_name']."</b> (".$row['city'].")";
		if ($rights['all_rights']) { ?>
			<input type="button" value="редактировать" class="inp" onClick="document.forms.edit<?=$row["tid"]?>.submit(); return false;">
			<input type="submit" value="удалить" class="inp red" onClick="if(confirm('Удалить <?=$row["team_name"]?>?')) document.forms.delete<?=$row["tid"]?>.submit();">
		<? }

		print "<p><ul style=\"list-style-type: none\">";

		$e=get_extra(MY_TEAMS, $row['tid']);

		print "<li><a href=\"teams_det.php?action=extra&id=".$row['tid']."&id_extra=".$e['team']['id']."\">Команда</a>";

		print "<li><a href=\"players.php#".$row['tid']."\">Игроки</a><p></li>";


		if ($e)
			foreach ($e as $e1) {
				if ($e1['char_id']!="team") {
					print "<li><a href=\"teams_det.php?action=extra&id=".$row['tid']."&id_extra=".$e1['id']."\">".$e1['title']."</a>";
		            print " <a onClick=\"if(confirm('Удалить?')) document.forms.deleteextra".$e1['id'].".submit(); return false;\" href=\"teams.php\"><font size=-1 color=red>[удалить]</font></a>";
	                print " <a href=teams_det.php?action=moveup&id=".$row['tid']."&extra_id=".$e1['id']."&o=".$e1["o"]."><font size=-1>вверх</font></a>";
	                print " <a href=teams_det.php?action=movedown&id=".$row['tid']."&extra_id=".$e1['id']."&o=".$e1["o"]."><font size=-1>вниз</font></a>";
		            print "<form action=\"teams_det.php?action=deleteextra&id_extra=".$e1['id']."\" method=\"post\" name=\"deleteextra".$e1['id']."\" id=\"deleteextra".$e1['id']."\"></form>";
				}
			}
		if ($rights['all_rights']||($rights['team_'.$row['tid'].'_edit'])) {
		?>
		<li><p><input type="submit" value="добавить страницу" class="inp" onClick="document.forms.extra<?=$row["tid"]?>.submit(); return false;">
		<? } ?>
		</ul>
</td>

<td width="10%" nowrap class="light">
<form action="teams_det.php" method="post" name="edit<?=$row["tid"]?>" id="edit<?=$row["tid"]?>">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" value="<?=$row['tid']?>">
</form>
<form action="teams_det.php" method="post" name="delete<?=$row["tid"]?>" id="delete<?=$row["tid"]?>">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?=$row['tid']?>">
</form>
<form action="teams_det.php" method="post" name="extra<?=$row["id"]?>" id="extra<?=$row["tid"]?>">
<input type="hidden" name="action" value="extra">
<input type="hidden" name="id" value="<?=$row['tid']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
</td>
</tr>

<?
	}
endwhile;
?>

<? if ($rights['all_rights']) { ?>
<tr><td colspan="6" class="light">
	<form action="teams_det.php" method="post">
	    <input type="hidden" name="action" value="add">
	    <input type="submit" value="Новая команда" class="inp">
    </form>
</td></tr>
<? } ?>
</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>