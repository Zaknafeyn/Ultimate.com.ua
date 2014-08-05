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
	if (! ($rights['video_edit'] || $rights['video_add']) ) {
		header("location:login.php");
		exit;
	}

$art_res=$db->sql_query("SELECT * FROM `video` ORDER BY dateadded DESC");

	$title = "Видео-ролики";
	include "include/header.php";
?>
<style>
.video {
	padding: 10;
	background: white;
	line-height: 18px;
}
.video_on_main {
	background: lightgreen;
}
.invisible {
	display: none;
}
</style>

<script language="javascript">
	var preloaderImg = new Image();
	preloaderImg.src = '/img/ajax-loader.gif';
	function set_post_mode(id,set_mode) {
		$('#preloader_'+id).html('<img src="/img/ajax-loader.gif" alt="wait for 2 hours" style="vertical-align: middle;" />');
		$.post("video_set_mode.php", { id: id, mode: set_mode },
			function(data){
				$('#preloader_'+id).html('');
				if (1==data) {
					$('#post'+id).toggleClass('video_on_main');
					$('#post'+id+' a').toggleClass('invisible');
					return false;
				} else alert('error');
		});
	}
</script>

<table width="100%" border="0" cellspacing="10" align="left" valign="top">
<? if ($rights['all_rights']||$rights['video_add']) { ?>
<tr><td colspan="7" class="light">
	<form action="video_det.php" method="post">
	    <input type="hidden" name="action" value="add">
	    <input type="submit" value="Добавить видео-ролик" class="inp"></td> <td class="light">
    </form>
</td></tr>
<? } ?>
<td class="dark">Название</td>
<td class="dark">Действия</td>
</tr>
<?
	while($row=$db->sql_fetchrow($art_res)) {
		$id = $row['id'];
		$show = $row['showonmain'];
?>

<tr>
<td class="light">
<div id="post<?=$id?>" class="video<?=$show?" video_on_main":""?>">
<b><?=stripslashes($row["title"])?></b>
<?
	print "<a class=\"pseudo_link clickable".($show?"":" invisible")."\" onclick=\"set_post_mode($id,0);return false;\">убрать с&nbsp;главной</a>";
	print "<a class=\"pseudo_link clickable".($show?" invisible":"")."\" onclick=\"set_post_mode($id,1);return false;\">на&nbsp;главную</a>";
?>
&nbsp;<span id="preloader_<?=$id?>"></span>
<br />Ссылка: <?=$row["url"]?>
<br />Автор: <?=$row["author"]?>
<br />Описание: <?=stripslashes($row["description"])?>
</div>
</td>
<td width="10%" nowrap class="light">
<form action="video_det.php" method="post" name="edit<?=$row["id"]?>" id="edit<?=$row["id"]?>">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" value="<?=$row['id']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
<form action="video_det.php" method="post" name="delete<?=$row["id"]?>" id="delete<?=$row["id"]?>">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?=$row['id']?>">
<input type="hidden" name="id_forum_user" value="<?=$row['id_forum_user']?>">
</form>
<? if ($rights['all_rights']||$rights['video_edit']||(!$rights['video_edit']&&($row['id_forum_user']==$rights['id_forum_user']))) { ?>
<input type="button" value="редактировать" class="inp" onClick="document.forms.edit<?=$row["id"]?>.submit(); return false;">
<input type="submit" value="удалить" class="inp red" onClick="if(confirm('Удалить?')) document.forms.delete<?=$row["id"]?>.submit();">
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