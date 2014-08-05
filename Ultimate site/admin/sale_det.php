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

if (!check_access('sale'))
{
	header("location:login.php");
	exit;
};

	function DelChild($id) {
        $rst = $db->sql_query("SELECT * FROM sale WHERE parent_id=$id");
        while ($line = $db->sql_fetchrow($rst))
            DelChild($line['id']);
        $db->sql_query("DELETE FROM sale WHERE id=$id");
    }

if($action=="delete" && $_REQUEST['id']!="")
{
	DelChild($_REQUEST['id']);
	header("location:sale.php");
	exit;
} else if ($action=="save") {
	if($_REQUEST['id']) {
	
		$sql = "UPDATE sale
			SET
			title='".mysql_escape_string($_REQUEST['title'])."',
			doc='".mysql_escape_string($_REQUEST['doc'])."',
			char_id='".mysql_escape_string($_REQUEST['char_id'])."',
			descr='".mysql_escape_string($_REQUEST['descr'])."',
			meta='".mysql_escape_string($_REQUEST['meta'])."'
			WHERE id=".$_REQUEST['id'];
		$db->sql_query($sql);
//	} elseif (isset$_REQUEST['parent_id']) {
	} else {
        $rst = $db->sql_query("SELECT MAX(o) as maxo FROM sale WHERE parent_id=".$_REQUEST['parent_id']);
        $line = $db->sql_fetchrow($rst);
		$db->sql_query("INSERT INTO
			sale
			(id_forum_user,parent_id,title,o,doc,char_id,descr,meta)
			values(".$rights['id_forum_user'].",
			".$_REQUEST['parent_id'].",
			'".mysql_escape_string($_REQUEST['title'])."',
			".($line['maxo']+1).",
			'".mysql_escape_string($_REQUEST['doc'])."',
			'".mysql_escape_string($_REQUEST['char_id'])."',
			'".mysql_escape_string($_REQUEST['descr'])."',
			'".mysql_escape_string($_REQUEST['meta'])."'
			)");
    }
    header("location:sale.php");
	exit;
} else if (($action=="moveup")||($action=="movedown")) {
    if ($action=="moveup")
    	$sql = "SELECT MAX(o) as o FROM sale WHERE parent_id=".$_REQUEST['parent_id']." AND o<".$_REQUEST['o'];
    else
    	$sql = "SELECT MIN(o) as o FROM sale WHERE parent_id=".$_REQUEST['parent_id']." AND o>".$_REQUEST['o'];
	$rst = $db->sql_query($sql);
    if ($db->sql_affectedrows($rst)) {
    	$line = $db->sql_fetchrow($rst);
        $db->sql_query("UPDATE sale SET o=".$_REQUEST['o']." WHERE parent_id=".$_REQUEST['parent_id']." AND o=".$line['o']);
        $db->sql_query("UPDATE sale SET o=".$line['o']." WHERE id=".$_REQUEST['id']);
    }
    header("location:sale.php");
    exit;
}

if($_REQUEST['id']!='')
{
	$art_res=$db->sql_query("select * from sale where id=".$_REQUEST['id']);
	$news=$db->sql_fetchrow($art_res);
}else{
	$art_res=$db->sql_query("select * from sale where 1=0");
	$news=$db->sql_fetchrow($art_res);
}

if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
  $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
else
  $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];


	include('include/header.php');
?>


<form action="sale_det.php" method="post">
<input type="hidden" name="action" value="save">
<? if ($action=="edit") { ?>
<input type="hidden" name="id" value="<?=$news['id']?>">
<? } else if ($action=="add") { ?>
<input type="hidden" name="parent_id" value="<?=$_REQUEST['parent_id']?>">
<? } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="10" align="left">
<tr valign="top">
	<td>
	<p>
		Ссылка
		<br /><a>http://ultimate.com.ua/sale/.../</a><input name="char_id" style="width:200px; color: #0097ee;" value="<?=$news['char_id']?>" class="inp">/
	</p>
	<p>
		Название в меню
  		<br /><input name="title" style="width:200px;" value="<?=stripslashes($news['title'])?>" class="inp">
	</p>
	<p>
		Заголовок
		<br /><input name="descr" style="width:400px;" value="<?=$news['descr']?>" class="inp">
	</p>
	<p>
		meta description (необязательно)
		<br /><input name="meta" style="width:400px;" value="<?=$news['meta']?>" class="inp">
	</p>
	<br />
	<p>
		Текст
		<div>
			<textarea id="doc" name="doc" rows="30" cols="80" style="width: 80%" class="tinymce">
			<?=stripslashes($news['doc'])?>
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