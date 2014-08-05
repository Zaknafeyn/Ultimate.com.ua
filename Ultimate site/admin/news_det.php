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

if (!check_access('sms'))
{
	header("location:login.php");
	exit;
};


if($action=="delete" && $_REQUEST['id']!="")
{
	$db->sql_query("DELETE FROM news WHERE id=".$_REQUEST['id']);
	header("location:sms.php");
	exit;
}else if($action=="save")
{
	if(!$_REQUEST['id']) {
		$rst = $db->sql_query("SELECT MAX(id) FROM news");
		$line = $db->sql_fetchrow($rst);
		$id = $line[0]+1;
		$db->sql_query("INSERT INTO news (id,id_forum_user,date,txt) values($id,".$rights['id_forum_user'].",".time().",'".mysql_escape_string($_REQUEST['txt'])."')");
		save_tags(MY_NEWS,$id,$_REQUEST['tags']);
	} else {
		$db->sql_query("UPDATE news SET txt='".mysql_escape_string($_REQUEST['txt'])."' WHERE id=".$_REQUEST['id']);
		save_tags(MY_NEWS,$_REQUEST['id'],$_REQUEST['tags']);
	}
	header("location:news.php");

	exit;
}

if($_REQUEST['id']!='')
{
	$art_res=$db->sql_query("SELECT * FROM news WHERE id=".$_REQUEST['id']);
	$news=$db->sql_fetchrow($art_res);
	$tags = get_tags(MY_NEWS,$news['id'],0);
}else{
	$art_res=$db->sql_query("select * from news where 1=0");
	$news=$db->sql_fetchrow($art_res);
	$tags = "";
}

	$title = "Новости";
	include('include/header.php');
?>

<form action="news_det.php" method="post">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?=$news['id']?>">
<table width="100%" border="0" cellspacing="10">
<tr valign="top">
  <td>
  	<p>Новость
		<div>
			<textarea id="txt" name="txt" rows="30" cols="80" style="width: 80%" class="tinymce">
			<?=stripslashes($news['txt'])?>
			</textarea>
		</div>
		<!-- Some integration calls -->
		<a href="javascript:;" onmousedown="$('#txt').tinymce().show();">[Show]</a>
		<a href="javascript:;" onmousedown="$('#txt').tinymce().hide();">[Hide]</a>
		<a href="javascript:;" onmousedown="$('#txt').tinymce().execCommand('Bold');">[Bold]</a>
		<a href="javascript:;" onmousedown="alert($('#txt').html());">[Get contents]</a>
		<a href="javascript:;" onmousedown="alert($('#txt').tinymce().selection.getContent());">[Get selected HTML]</a>
		<a href="javascript:;" onmousedown="alert($('#txt').tinymce().selection.getContent({format : 'text'}));">[Get selected text]</a>
		<a href="javascript:;" onmousedown="alert($('#txt').tinymce().selection.getNode().nodeName);">[Get selected element]</a>
		<a href="javascript:;" onmousedown="$('#txt').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
		<a href="javascript:;" onmousedown="$('#txt').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a>
	</p>
</td>
</tr>
<tr><td><input type="submit" value="Сохранить" class="inp"></td></tr>
</table>
</form>


<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>