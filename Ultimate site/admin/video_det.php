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

if (!check_access('video'))
{
	header("location:login.php");
	exit;
};

	if($_REQUEST['action']=="delete" && $_REQUEST['id']!="")
	{
	    $db->sql_query("delete from video where id=".$_REQUEST['id']);
	    header("location:video.php");
	    exit;
	} else if ($_REQUEST['action']=="save") {

        $url = $_REQUEST['url'];
        $type = 1;
        $youtubecode = $_REQUEST['youtubecode'];

	    if($_REQUEST['id']=="")
	        $db->sql_query("INSERT INTO video
	            (id_forum_user,title,description,type,url,youtubecode,author,dateadded)
	            values(".$rights['id_forum_user'].",'".
	            mysql_escape_string($_REQUEST['title'])."','".
	            mysql_escape_string($_REQUEST['description'])."',".
	            $type.",'".
	            mysql_escape_string($url)."','".
	            mysql_escape_string($youtubecode)."','".
	            mysql_escape_string($_REQUEST['author'])."',".
	            time().")");
	    else {
	    	$sql = "UPDATE video SET
	            title='".mysql_escape_string($_REQUEST['title'])."',
	            description='".mysql_escape_string($_REQUEST['description'])."',
	            type=".$type.",
	            author='".mysql_escape_string($_REQUEST['author'])."',
	            url='".mysql_escape_string($url)."',
	            youtubecode='".mysql_escape_string($youtubecode)."'
	            WHERE id=".$_REQUEST['id'];
          	$db->sql_query($sql);
	     }

		//die($sql);
	    header("location:video.php");

	    exit;
	}

if($_REQUEST['id']!='')
{
	$art_res=$db->sql_query("select * from video where id=".$_REQUEST['id']);
	$news=$db->sql_fetchrow($art_res);
	$title=stripslashes($news['title']);
	$description=stripslashes($news['description']);
	$type=$news['type'];
	$url=$news['url'];
	$youtubecode=stripslashes($news['youtubecode']);
	$author=stripslashes($news['author']);
} else {
	$title='';
	$description='';
	$type=0;
	$url='';
	$youtubecode='';
	$author='';
}

include ('include/header.php');

?>


<form action="video_det.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?=$news['id']?>">
<input type="hidden" name="url" value="<?=$url?>">
<input type="hidden" name="type" value="<?=$type?>">
<table width="100%" border="0" cellspacing="2" align="left">
<tr>
  <td>
  	<p>Ссылка: <br /><input name="url" style="width:300px;" value="<?= ($type==1) ? stripslashes($url) : "" ?>">
  	<p>Код: <br /><textarea rows="5" name="youtubecode" style="width:350px;"><?=$youtubecode?></textarea>
  	<p>Название: <br /><input name="title" style="width:400px" value="<?=stripslashes($title)?>">
  	<p>Краткое описание: <br /><textarea rows="5" name="description" style="width:350px;"><?=stripslashes($description)?></textarea>
  	<p>Автор: <br /><input name="author" style="width:400px" value="<?=stripslashes($author)?>">
  </td>
</tr>

<tr>
	<td align="center" class="dark"><input type="submit" value="Сохранить" class="inp"></td>
</tr>

</table>
</form>


<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>