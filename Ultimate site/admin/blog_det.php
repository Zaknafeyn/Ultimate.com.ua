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

if (!check_access('news'))
{
	header("location:login.php");
	exit;
};

	$max_upload_file_width = 450;

if($action=="delete" && $_REQUEST['id']!="")
{
	$db->sql_query("DELETE FROM blog WHERE id=".$_REQUEST['id']);
	$db->sql_query("DELETE FROM comments WHERE id_category=".MY_BLOG." AND id_item=".$_REQUEST['id']);
	$db->sql_query("DELETE FROM tags WHERE id_category=".MY_BLOG." AND id_item=".$_REQUEST['id']);
	header("location:blog.php");
	exit;
}else if($action=="save")
{

	// загрузка фотографии
    $photo_error='';
    if (isset($_FILES["fname"]))
        if ( preg_match('/\.(jpg|jpeg|gif|png)$/i', $_FILES["fname"]["name"]) ) {
            if (preg_match('#image\/[x\-]*([a-z]+)#', $_FILES["fname"]["type"], $filetype)) {
                //if ($_FILES["fname"]["size"] <= $max_upload_file_size) {
	                list($width, $height) = @getimagesize($_FILES["fname"]["tmp_name"]);
	                $img_type = $filetype[1];
	                $photo_fname = uniqid(rand());
	                $m_photo = $photo_fname.".".$img_type;
	                $m_photo_small = $photo_fname."_small.".$img_type;
		            if (move_uploaded_file($_FILES["fname"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo")) {
		                @chmod($_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo", 0644);
		                if ($width>$max_upload_file_width) {
		                	imageresize($_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo", $_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo", $max_upload_file_width, $max_upload_file_width, 85);
			                @chmod($_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo", 0644);
		           		}
		                imageresize($_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo_small", $_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo", 130, 1000, 85);
						chmod($_SERVER['DOCUMENT_ROOT']."/blog/img/$m_photo_small", 0644);
			            $photo_error='';
	                } else {
	                    $photo_error = "Ошибка загрузки файла ".$_FILES["fname"]["name"]."!";
	                }
                //} else {
                //    $photo_error = "Файл слишком большой!";
                //}
            }
        }

    print $photo_error ? $photo_error : "";

	if (!$m_photo) {
		$m_photo = $_REQUEST['photo'];
		$m_photo_small = $_REQUEST['photo_small'];
	}

	if($_REQUEST['id']=="") {
		$rst = $db->sql_query("SELECT MAX(id), MAX(o) FROM blog");
		$line = $db->sql_fetchrow($rst);
		$id = $line[0]+1;
		$o = $line[1]+1;
		$db->sql_query("insert into blog
        	(id, id_forum_user,date,date1,title,subtitle,text,photo,photo_small,photo_author,photo_descr,o)
            values(
            	$id,".
				$rights['id_forum_user'].",".
				time().",
				'".date("Ymd")."',
				'".mysql_escape_string($_REQUEST['title'])."',
				'".mysql_escape_string($_REQUEST['subtitle'])."',
				'".mysql_escape_string($_REQUEST['text'])."',
				'$m_photo',
				'$m_photo_small',
				'".mysql_escape_string($_REQUEST['photo_author'])."',
				'".mysql_escape_string($_REQUEST['photo_descr'])."',
				$o)");
		save_tags(MY_BLOG,$id,$_REQUEST['tags']);
	} else {
		$db->sql_query("update blog set
            title='".mysql_escape_string($_REQUEST['title'])."',
            subtitle='".mysql_escape_string($_REQUEST['subtitle'])."',
            text='".mysql_escape_string($_REQUEST['text'])."',
            photo='$m_photo',
            photo_small='$m_photo_small',
            photo_author='".mysql_escape_string($_REQUEST['photo_author'])."',
            photo_descr='".mysql_escape_string($_REQUEST['photo_descr'])."'
            where id=".$_REQUEST['id']);
		save_tags(MY_BLOG,$_REQUEST['id'],$_REQUEST['tags']);
    }

	print mysql_error();
	header("location:blog.php");

	exit;
} else if (($action=="moveup")||($action=="movedown")) {
    if ($action=="moveup")
    	$sql = "SELECT MIN(o) as o FROM blog WHERE o>".$_REQUEST['o']." AND active=1";
    else
    	$sql = "SELECT MAX(o) as o FROM blog WHERE o<".$_REQUEST['o']." AND active=1";
	$rst = $db->sql_query($sql);
    if ($db->sql_affectedrows($rst)) {
    	$line = $db->sql_fetchrow($rst);
        $db->sql_query("UPDATE blog SET o=".$_REQUEST['o']." WHERE o=".$line['o']);
        $db->sql_query("UPDATE blog SET o=".$line['o']." WHERE id=".$_REQUEST['id']);
    }
    header("location:blog.php");
    exit;
}



if($_REQUEST['id']!='')
{
	$art_res=$db->sql_query("select * from blog where id=".$_REQUEST['id']);
	$news=$db->sql_fetchrow($art_res);
    $tags = get_tags(MY_BLOG,$_REQUEST['id'],0);

}else{
	$art_res=$db->sql_query("select * from blog where 1=0");
	$news=$db->sql_fetchrow($art_res);
}

$title="Блог";
include ("include/header.php");

?>


<form action="blog_det.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?=$news['id']?>">
<table width="100%" border="0" cellspacing="5" align="left">
<tr valign="top">
  <td>
  	<p>
	  	Заголовок
  		<br /><input name="title" style="width:600px; font-size: 1.9em; font-family: Trebuchet MS;" value="<?=htmlspecialchars(stripslashes($news['title']))?>" class="inp">
  	</p>
  	<p>
  		Анонс
	  	<br /><textarea name="subtitle" value="" cols="80" rows="10" class="inp"><?=htmlspecialchars(stripslashes($news['subtitle']))?></textarea>
  	</p>
  	<p>
		Загрузить КРАСИВУЮ фотографию
		<br /><input type="hidden" name="photo" value="<?=$news['photo']?>">
  		<input type="hidden" name="photo_small" value="<?=$news['photo_small']?>">
  		<input type="file" name="fname" style="width:400px" class="inp">
  		<? if ($news['photo']) print "<br />(Уже загружено: ".$news['photo'].")"; ?>
  	</p>
  	<p>
		Авторство фотографии (необязательно)
		<br /><input name="photo_author" style="width:400px" value="<?=stripslashes($news['photo_author'])?>" class="inp">
  	</p>
  	<p>
		Описание фотографии (необязательно)
		<br /><input name="photo_descr" style="width:400px" value="<?=stripslashes($news['photo_descr'])?>" class="inp">
  	</p>
  	<br />
  	<p>
		Текст
		<div>
			<textarea id="text" name="text" rows="30" cols="80" style="width: 80%" class="tinymce">
			<?=stripslashes($news['text'])?>
			</textarea>
		</div>
		<!-- Some integration calls -->
		<a href="javascript:;" onmousedown="$('#text').tinymce().show();">[Show]</a>
		<a href="javascript:;" onmousedown="$('#text').tinymce().hide();">[Hide]</a>
		<a href="javascript:;" onmousedown="$('#text').tinymce().execCommand('Bold');">[Bold]</a>
		<a href="javascript:;" onmousedown="alert($('#text').html());">[Get contents]</a>
		<a href="javascript:;" onmousedown="alert($('#text').tinymce().selection.getContent());">[Get selected HTML]</a>
		<a href="javascript:;" onmousedown="alert($('#text').tinymce().selection.getContent({format : 'text'}));">[Get selected text]</a>
		<a href="javascript:;" onmousedown="alert($('#text').tinymce().selection.getNode().nodeName);">[Get selected element]</a>
		<a href="javascript:;" onmousedown="$('#text').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
		<a href="javascript:;" onmousedown="$('#text').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a>
	</p>
    </td>
</tr>
<tr><td><input type="submit" value="Сохранить" class="inp"></td></tr>
</table>
</form>
<br />
<br />
<br />
<br />


<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>