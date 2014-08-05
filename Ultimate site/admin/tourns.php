<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];

if (!$rights['all_rights'])
	if (! ($rights['tourn_edit'] || $rights['tourn_add']) ) {
		header("location:login.php");
		exit;
	}


$art_res=$db->sql_query("SELECT * FROM `tourn` ORDER BY dat_begin DESC");

	$title = "Турниры";
	include "include/header.php";
?>

<table border="0" cellspacing="1" cellpadding="5" align="left">
<? if ($rights['all_rights']||$rights['tourn_add']) { ?>
<tr><td colspan="3" class="light">
	<form action="tourns_det.php" method="post">
	    <input type="hidden" name="action" value="add">
	    <input type="submit" value="Добавить турнир" class="inp">
    </form>
</td></tr>
<? } ?>
<tr>
<td class="dark">Турнир</td>
<td class="dark">Текст</td>
<td class="dark">Действия</td>
</tr>
<?
while($row=$db->sql_fetchrow($art_res)):
?>

<tr>
<td class="light">
<table cellpadding="0" cellspacing="0">
<tr valign="top"><td>
	<p>
		<?="<b>".stripslashes($row["short_name"])."</b> (".$row['char_id'].")"?>
		<br />
		<?=stripslashes($row["full_name"])?>
		<br />
		<?=my_create_date($row["dat_begin"],$row['dat_end']).", ".stripslashes($row["country"]).", ".stripslashes($row["city"])?>
		<? if ( $tags=get_tags(2,$row["id"],0) ) print "<div class=\"smalldate0\">Теги: $tags</div>"; ?>
	</p>
</td>
		<?
			if ($e=get_extra(MY_TOURN, $row['id'])) {
				print "<td><div style=\"padding-left: 25\">Дополнительные страницы:";
				foreach ($e as $e1) {
					print "<p><a href=\"tourns_det.php?action=extra&id=".$row['id']."&id_extra=".$e1['id']."\">".$e1['title']."</a>";
	                print " <a onClick=\"if(confirm('Удалить?')) document.forms.deleteextra".$e1['id'].".submit(); return false;\" href=\"tourns.php\"><font size=-1 color=red>[удалить]</font></a>";
	                print "<form action=\"tourns_det.php?action=deleteextra&id_extra=".$e1['id']."\" method=\"post\" name=\"deleteextra".$e1['id']."\" id=\"deleteextra".$e1['id']."\"></form>";
				}
				print "</td></div>";
			}
		?>

</tr>
</table>
<td class="light" width="10" align="center">
	<?=$row["doc"]?"+":""?>
</td>
<td width="10%" nowrap class="light">
<form action="tourns_det.php" method="post" name="edit<?=$row["id"]?>" id="edit<?=$row["id"]?>">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" value="<?=$row['id']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
<form action="tourns_det.php" method="post" name="delete<?=$row["id"]?>" id="delete<?=$row["id"]?>">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?=$row['id']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
<form action="tourns_det.php" method="post" name="extra<?=$row["id"]?>" id="extra<?=$row["id"]?>">
<input type="hidden" name="action" value="extra">
<input type="hidden" name="id" value="<?=$row['id']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
<? if ($rights['all_rights']||$rights['tourn_edit']||(!$rights['tourn_edit']&&($row['id_forum_user']==$rights['id_forum_user']))) { ?>
<input type="button" value="редактировать" class="inp" onClick="document.forms.edit<?=$row["id"]?>.submit(); return false;">
<input type="submit" value="удалить" class="inp red" onClick="if(confirm('Удалить?')) document.forms.delete<?=$row["id"]?>.submit();">
<p><input type="submit" value="добавить страницу" class="inp" onClick="document.forms.extra<?=$row["id"]?>.submit(); return false;">
<? } ?>
</td>
</tr>

<?
endwhile;
?>

</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>