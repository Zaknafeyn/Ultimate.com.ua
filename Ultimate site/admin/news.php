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
	if (! ($rights['news_edit'] || $rights['news_add']) ) {
		header("location:login.php");
		exit;
	}

	$art_res=$db->sql_query("SELECT * FROM `news` ORDER BY date DESC");

    $title = "Новости";
	include "include/header.php";

?>

<table width="100%" border="0" cellspacing="1" cellpadding="5" align="left" valign="top">
<? if ($rights['all_rights']||$rights['news_add']) { ?>
<tr><td colspan="4" class="light">
	<form action="news_det.php" method="post">
	    <input type="hidden" name="action" value="add">
	    <input type="submit" value="Новое сообщение" class="inp">
	</form>
</td></tr>
<? } ?>
<td class="dark">Новость</td>
<td class="dark">Действия</td>
</tr>
<?
while($row=$db->sql_fetchrow($art_res)):
?>

<tr>
<td class="light">
<?=stripslashes($row['txt'])?>
<? if ( $tags=get_tags(MY_NEWS,$row["id"],0) ) print "<div class=\"smalldate0\">Теги: $tags</div>"; ?>
</td>

<td width="10%" nowrap class="light">
<form action="news_det.php" method="post" name="edit<?=$row["id"]?>" id="edit<?=$row["id"]?>">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" value="<?=$row['id']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
<form action="news_det.php" method="post" name="delete<?=$row["id"]?>" id="delete<?=$row["id"]?>">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?=$row['id']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
<? if ($rights['all_rights']||$rights['news_edit']||(!$rights['news_edit']&&($row['id_forum_user']==$rights['id_forum_user']))) { ?>
<input type="button" value="редактировать" class="inp" onClick="document.forms.edit<?=$row["id"]?>.submit(); return false;">
<input type="submit" value="удалить" class="inp red" onClick="if(confirm('Удалить?')) document.forms.delete<?=$row["id"]?>.submit();">
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