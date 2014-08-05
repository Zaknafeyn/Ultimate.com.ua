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
	if (! ($rights['practice_edit'] || $rights['practice_add']) ) {
		header("location:login.php");
		exit;
	}

	$title = "Где поиграть?";
	include "include/header.php";


	$result = "";
	if (isset($_REQUEST['action'])) {
		if ($db->sql_affectedrows($db->sql_query("SELECT * FROM practice_schedule")))
			$db->sql_query("UPDATE practice_schedule SET schedule='".mysql_escape_string($_REQUEST['doc'])."'");
		else
			$db->sql_query("INSERT INTO practice_schedule SET schedule='".mysql_escape_string($_REQUEST['doc'])."'");
		$result = "<p>Сохранено</p>";
	};

	$r = $db->sql_query("SELECT * FROM practice_schedule LIMIT 1");
	if ($db->sql_affectedrows($r)) $l = $db->sql_fetchrow($r);

?>

<table width="100%" border="0" cellspacing="0" align="left" valign="top">
<tr><td>

<?=$result?>

<form action="practice.php" method="post">
	<input type="hidden" name="action" value="save">
  	<p>
		<div>
			<textarea id="doc" name="doc" rows="30" cols="80" style="width: 80%" class="tinymce">
			<?=stripslashes($l['schedule'])?>
			</textarea>
		</div>
		<!-- Some integration calls -->
		<a href="javascript:;" onmousedown="$('#doc').tinymce().show();">[Show]</a>
		<a href="javascript:;" onmousedown="$('#doc').tinymce().hide();">[Hide]</a>
		<a href="javascript:;" onmousedown="$('#doc').tinymce().execCommand('Bold');">[Bold]</a>
		<a href="javascript:;" onmousedown="alert($('#doc').html());">[Get contents]</a>
		<a href="javascript:;" onmousedown="alert($('#doc').tinymce().selection.getContent());">[Get selected HTML]</a>
		<a href="javascript:;" onmousedown="alert($('#doc').tinymce().selection.getContent({format : 'text'}));">[Get selected text]</a>
		<a href="javascript:;" onmousedown="alert($('#doc').tinymce().selection.getNode().nodeName);">[Get selected element]</a>
		<a href="javascript:;" onmousedown="$('#doc').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
		<a href="javascript:;" onmousedown="$('#doc').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a>
	</p>
	<p><input type="submit" value="Сохранить" class="inp"></td></tr>
</form>


</td></tr>

</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>