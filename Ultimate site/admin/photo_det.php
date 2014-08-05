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

if (!check_access('photo'))
{
	header("location:login.php");
	exit;
};

	function DelChild($id) {
        $rst = $db->sql_query("SELECT * FROM photo_albums WHERE parent_id=$id");
        while ($line = $db->sql_fetchrow($rst))
            DelChild($line['id']);
        $db->sql_query("DELETE FROM photo_albums WHERE id=$id");
        $db->sql_query("DELETE FROM tags WHERE id_category=3 AND id_item=$id");
    }

    /*function GetPath($id) {
        $sql = "SELECT * FROM photo_albums WHERE id=$id";
        $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
		$line = $db->sql_fetchrow($rst);
        if ($line['parent_id']==0) {
        	return $line['char_id'];
        } else
        	return GetPath($line['parent_id'])."/".$line['char_id'];
    }*/

    function GetPathTxt($id) {
        $sql = "SELECT * FROM photo_albums WHERE id=$id";
        $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
		$line = $db->sql_fetchrow($rst);
        if ($line['parent_id']==0) {
        	return $line['title'];
        } else
        	return GetPathTxt($line['parent_id'])."/".$line['title'];
    }


if ($action=="delete" && $_REQUEST['id']!="")
{
	$album = GetPath($_REQUEST['id']);
    list($usec, $sec) = explode(' ', microtime()); srand((float) $sec + ((float) $usec * 100000)); $i=rand(10000,99999);
	if (rename($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album",$_SERVER['DOCUMENT_ROOT']."/photo/albums/$album.$i")) {
		DelChild($_REQUEST['id']);
	}
	header("location:photo.php");
	exit;
} elseif ($action=="addphoto") {
	if (isset($_FILES["userfile"])) {
    	$album = $_REQUEST['album'];
        $upload_res = "";
    	foreach ($_FILES["userfile"]["name"] as $key=>$name) {
	    	if ( preg_match('/\.(jpg|jpeg|gif|png)$/i', $name) ) {
	            if (move_uploaded_file($_FILES["userfile"]["tmp_name"][$key],$_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$name")) {
	                chmod($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$name", 0644);
                    $a = imageresize($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/large/$name", $_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$name", 750, 3000, 85);
                    //makeIcons_MergeCenter($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$name", $_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/large/$name", 600, 3000);
	                chmod($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/large/$name", 0644);
                    //$b = imageresize($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/small/$name", $_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$name", 180, 180, 85);
                    makeIcons_MergeCenter($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$name", $_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/small/$name", 150, 150, 85);
                    makeIcons_MergeCenter($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$name", $_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/small/_$name", 75, 75, 85);
	                chmod($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/small/$name", 0644);
	                chmod($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/small/_$name", 0644);
                    $upload_res .= "<br>$name <font color=blue><b>OK</b></font>";
                    $photo_author = $_REQUEST['photo_author'];
                    if (!$photo_author)
                    	$photo_author_id = $_REQUEST['photo_author_id'];
                    else $photo_author_id = 0;
                    /*
                    $db->sql_query("INSERT INTO photo
                    	(id_album, fname, dateadd, width, height, width_thumb, height_thumb, photo_author, photo_author_id)
                    	VALUES(".$_REQUEST['id'].",'$name',".time().",".$a['w0'].",".$a['h0'].",".$b['w'].",".$b['h'].",
                    	'".mysql_escape_string($photo_author)."',$photo_author_id)");
                    */
                    $db->sql_query("INSERT INTO photo
                    	(id_album, fname, dateadd, width, height, photo_author, photo_author_id)
                    	VALUES(".$_REQUEST['id'].",'$name',".time().",".$a['w0'].",".$a['h0'].",
                    	'".mysql_escape_string($photo_author)."',$photo_author_id)");
                } else {
                	$upload_res .= "<br><font color=red>Ошибка загрузки файла $name!</font>";
                }
            }
        }
    }
} else if ($action=="save") {
	if(isset($_REQUEST['id'])) {
		$id = digits_only($_REQUEST['id']);
		$picasa_user = $_REQUEST['picasa_user'];
		$picasa_album = $_REQUEST['picasa_album'];
		if ($_REQUEST['picasa_author']==digits_only($_REQUEST['picasa_author'])) {
			$picasa_author = '';
			$picasa_author_id = $_REQUEST['picasa_author'];
		} else {
			$picasa_author = $_REQUEST['picasa_author'];
			$picasa_author_id = 0;
		}
		$sql = "update photo_albums set
			title='".mysql_escape_string($_REQUEST['title'])."',
			titlefull='".mysql_escape_string($_REQUEST['titlefull'])."',
			titleshort='".mysql_escape_string($_REQUEST['titleshort'])."',
			descr='".mysql_escape_string($_REQUEST['descr'])."',
			picasa_user='".mysql_escape_string($picasa_user)."',
			picasa_album='".mysql_escape_string($picasa_album)."',
			picasa_author='".mysql_escape_string($_REQUEST['picasa_author'])."'
			where id=".$id;
		$db->sql_query($sql);
		if ($_REQUEST['picasa_user']) {
			// обновить фотки с Пикасы
			//$db->sql_query("DELETE FROM photo WHERE id_album=".$id);
			ini_set('include_path',ini_get('include_path').'.:'.$_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/');
			$library_path = $_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/';
			include_once $_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/Picasa.php';
			foreach(explode(",",$picasa_album) as $a) {			
				$album_data = get_picasa_album($picasa_user,trim($a));
				//print "<p>sizeof=".sizeof($album_data['images']);
    	    			foreach($album_data["images"] as $i) {	   
				    //print "<p>".$i['url'];
					if (!$db->sql_affectedrows($db->sql_query("SELECT id FROM photo WHERE fname='".$i["url"]."'"))) {
		    			$sql = "INSERT INTO photo
	    	               	(id_album, fname, tn_fname, dateadd, width, height, tn_width, tn_height, photo_author, photo_author_id)
	        	           	VALUES(".$id.",'".$i["url"]."','".$i["tn_url"]."',".time().",".$i['width'].",".$i['height'].",".$i['tn_width'].",".$i['tn_height'].",
	            	       	'".mysql_escape_string($picasa_author)."',".$picasa_author_id.")";
	       				$db->sql_query($sql);
					//print "<br />$sql";
	       			}
	       		}
			//die();
	       		$db->sql_query("UPDATE photo_albums SET cover='".$album_data["url"]."' WHERE id=".$id);
       		}
       		// нужна еще проверка, на удаленные из пикасы фотографии
		}
	} elseif (isset($_REQUEST['parent_id'])) {
			$rst = $db->sql_query("SELECT MAX(id) FROM photo_albums");
			$line = $db->sql_fetchrow($rst);
			$id = $line[0]+1;
			$picasa_user = $_REQUEST['picasa_user'];
			$picasa_album = $_REQUEST['picasa_album'];
			if ($_REQUEST['picasa_author']==digits_only($_REQUEST['picasa_author'])) {
				$picasa_author = '';
				$picasa_author_id = $_REQUEST['picasa_author'];
			} else {
				$picasa_author = $_REQUEST['picasa_author'];
				$picasa_author_id = 0;
			}
	        $rst = $db->sql_query("SELECT MAX(o) as maxo FROM photo_albums WHERE parent_id=".$_REQUEST['parent_id']);
	        $line = $db->sql_fetchrow($rst);
			$sql = "INSERT INTO photo_albums (id, parent_id, id_forum_user,char_id,dat,title,titlefull,titleshort,descr,picasa_user,picasa_album,picasa_author,o)
	        	VALUES ($id,".$_REQUEST['parent_id'].",".$rights['id_forum_user'].",
	        	'".mysql_escape_string($_REQUEST['char_id'])."','".date("Ymd")."',
	        	'".mysql_escape_string($_REQUEST['title'])."',
	        	'".mysql_escape_string($_REQUEST['titlefull'])."',
	        	'".mysql_escape_string($_REQUEST['titleshort'])."',
	        	'".mysql_escape_string($_REQUEST['descr'])."',
	        	'".mysql_escape_string($picasa_user)."',
	        	'".mysql_escape_string($picasa_album)."',
	        	'".mysql_escape_string($_REQUEST['picasa_author'])."',
	        	".($line['maxo']+1).")";
			$db->sql_query($sql) or die ($sql."<p>".mysql_error());
			if ($picasa_user&&$picasa_album) {				ini_set('include_path',ini_get('include_path').'.:'.$_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/');
				$library_path = $_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/';
				include_once $_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/Picasa.php';
				foreach(explode(",",$picasa_album) as $a) {
					$album_data = get_picasa_album($picasa_user,trim($a));
	    			foreach($album_data["images"] as $i) {
	    				$sql = "INSERT INTO photo
	                    	(id_album, fname, tn_fname, dateadd, width, height, tn_width, tn_height, photo_author, photo_author_id)
	                    	VALUES(".$id.",'".$i["url"]."','".$i["tn_url"]."',".time().",".$i['width'].",".$i['height'].",".$i['tn_width'].",".$i['tn_height'].",
	                    	'".mysql_escape_string($picasa_author)."',".$picasa_author_id.")";
	                    $db->sql_query($sql);
	       			}
	       			$db->sql_query("UPDATE photo_albums SET cover='".$album_data["url"]."' WHERE id=".$id);
       			}
			} else {
				$album = GetPath($id);
		    	mkdir($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album", 0755);
	    		mkdir($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original", 0755);
	    		mkdir($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/large", 0755);
		    	mkdir($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/small", 0755);
			}
        /*$rst = $db->sql_query("SELECT MAX(o) as maxo FROM photo_albums WHERE parent_id=".$_REQUEST['parent_id']);
        $line = $db->sql_fetchrow($rst);
		$db->sql_query("insert into photo_albums (id_forum_user,parent_id,title,o,descr) values(".$rights['id_forum_user'].",".$_REQUEST['parent_id'].",'".mysql_escape_string($_REQUEST['title'])."',".($line2['maxo']+1).",'".mysql_escape_string($_REQUEST['descr'])."')");*/
    }
	header("location:photo.php");
	exit;
} elseif (($action=="moveup")||($action=="movedown")) {
    if ($action=="moveup")
    	$sql = "SELECT MAX(o) as o FROM photo_albums WHERE parent_id=".$_REQUEST['parent_id']." AND o<".$_REQUEST['o'];
    else
    	$sql = "SELECT MIN(o) as o FROM photo_albums WHERE parent_id=".$_REQUEST['parent_id']." AND o>".$_REQUEST['o'];
	$rst = $db->sql_query($sql);
    if ($db->sql_affectedrows($rst)) {
    	$line = $db->sql_fetchrow($rst);
        $db->sql_query("UPDATE photo_albums SET o=".$_REQUEST['o']." WHERE parent_id=".$_REQUEST['parent_id']." AND o=".$line['o']);
        $db->sql_query("UPDATE photo_albums SET o=".$line['o']." WHERE id=".$_REQUEST['id']);
    }
    header("location:photo.php");
    exit;
} elseif ($action == "check") {
	if (isset($_POST['cb'])) {
    	$cb = $_POST['cb'];
        $album = $_POST['album'];
        $db->sql_query("UPDATE photo SET showonmain=0 WHERE id_album=".$_REQUEST['id']);
        foreach ($cb as $key=>$value)
        	$db->sql_query("UPDATE photo SET showonmain=1 WHERE id=$value");
    } else
        $db->sql_query("UPDATE photo SET showonmain=0 WHERE id_album=".$_REQUEST['id']);
	if (isset($_POST['cover'])) {
    	$cover = $_POST['cover'];
        $db->sql_query("UPDATE photo SET cover=0 WHERE id_album=".$_REQUEST['id']);
        foreach ($cover as $key=>$value)
        	$db->sql_query("UPDATE photo SET cover=1 WHERE id=$value");
    } else
        $db->sql_query("UPDATE photo SET cover=0 WHERE id_album=".$_REQUEST['id']);
    $action = "view";
} elseif ($action == "delete_photo") {
	if (isset($_POST['cb'])) {
    	$cb = $_POST['cb'];
        $album = $_POST['album'];
        foreach ($cb as $key=>$value) {
        	unlink($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/original/$value");
        	unlink($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/large/$value");
        	unlink($_SERVER['DOCUMENT_ROOT']."/photo/albums/$album/small/$value");
        	$db->sql_query("DELETE FROM photo WHERE id=".$key);
        }
    }
}

	if (($action != "add") && ($action != "edit")) {
		$id = $_REQUEST['id'];
		$album = GetPath($id);
	} elseif (isset($_REQUEST['id'])) {
		$rst2=$db->sql_query("select * from photo_albums where id=".$_REQUEST['id']);
		$line2=$db->sql_fetchrow($rst2);
	    $tags = get_tags(3,$line2['id'],0);
	} else {
		$rst2=$db->sql_query("select * from photo_albums where 1=0");
		$line2=$db->sql_fetchrow($rst2);
		$tags = "";
	}


	include "include/header.php";
?>


<?
	if (($action == "add") || ($action == "edit")) {
?>


<form action="photo_det.php" method="post">
<input type="hidden" name="action" value="save">
<? if ($action=="edit") { ?>
<input type="hidden" name="id" value="<?=$line2['id']?>">
<? } else if ($action=="add") { ?>
<input type="hidden" name="parent_id" value="<?=$_REQUEST['parent_id']?>">
<? } ?>
<table width="100%" border="0" cellspacing="10" align="left">
			<tr valign="top">
				<td align="right" width="30%">Имя для ссылки<div class="smalldate0">Например: <strong>bbg</strong> или <strong>kievhat</strong> или <strong>ln</strong><br />Маленькие латинские буквы и арабские цифры only!</div></td>
				<? if ($action == "edit") { ?>
					<td><input name="char_id" type="hidden" value="<?=$line2['char_id']?>"><b><?=$line2['char_id']?></b></td>
				<? } else { ?>
					<td><input name="char_id" style="width:400px;" value="<?=$line2['char_id']?>"></td>
				<? } ?>
			</tr>
			<tr valign="top">
				<td align="right">Короткое название<div class="smalldate0">Например: <strong>фото Храмова</strong></div></td>
				<td>
					<input name="title" style="width:200px;" value="<?=stripslashes($line2['title'])?>">
				</td>
			</tr>
			<tr valign="top">
				<td align="right">Полное название<div class="smalldate0">Например: <strong>Kiev Hat 2009, фото Евгения Храмова</strong></div></td>
				<td>
					<input name="titlefull" style="width:400px;" value="<?=stripslashes($line2['titlefull'])?>">
				</td>
			</tr>
			<tr valign="top">
				<td align="right">Компактное название<div class="smalldate0">Это надпись над иконкой альбома<br />Например: <strong>Kiev Hat 09, Храмов</strong></div></td>
				<td>
					<input name="titleshort" style="width:200px;" value="<?=stripslashes($line2['titleshort'])?>">
				</td>
			</tr>
			<tr valign="top">
				<td align="right">Описание</td>
				<td><textarea name="descr" rows="10" style="width:400px"><?=stripslashes($line2['descr'])?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<td align="right">PICASA user</td>
				<td>
					<input name="picasa_user" style="width:200px;" value="<?=stripslashes($line2['picasa_user'])?>">
				</td>
			</tr>
			<tr valign="top">
				<td align="right">PICASA album(s)</td>
				<td>
					<input name="picasa_album" style="width:200px;" value="<?=stripslashes($line2['picasa_album'])?>">
				</td>
			</tr>
			<tr valign="top">
				<td align="right">Автор фотографий (Имя или id на форуме)</td>
				<td>
					<input name="picasa_author" style="width:200px;" value="<?=stripslashes($line2['picasa_author'])?>">
				</td>
			</tr>
<tr><td align="right"><input type="submit" value="Сохранить" class="inp"></td><td></td></tr>
</table>
</form>

<?
	} elseif ($action == "view") {
    ?>
	    <form action="photo_det.php" method="post">
	    <input type="hidden" name="action" value="check">
	    <input type="hidden" name="album" value="<?=$album?>">
	    <input type="hidden" name="id" value="<?=$id?>">
	    <table border="0" cellspacing="10">
	        <tr>
	          <td class="dark" colspan="6"><h4><?=$descr?></h4></td>
	        </tr>
	    <?
	        $COLS = 4;
            $i=0;
	        $rst = $db->sql_query("SELECT * FROM photo WHERE id_album=$id");
	        while ($line = $db->sql_fetchrow($rst)) {
                if ( ( $i % $COLS ) == 0 ) print "<tr><td></td>";
                print "<td width=\"200\"><img src=\"/photo/albums/$album/small/".$line['fname']."\" align=\"top\"";
                if ($line['showonmain']) print " style=\"border: 5px solid red;\"";
                print "><br><input type=\"checkbox\" id=\"cb[$i]\" name=\"cb[$i]\" value=\"".$line['id']."\"";
                if ($line['showonmain']) print " checked";
                print ">&nbsp;<label for=\"cb[$i]\">на&nbsp;главную</label>";
                print "<br /><input type=\"checkbox\" id=\"cover[$i]\" name=\"cover[$i]\" value=\"".$line['id']."\"";
                if ($line['cover']) print " checked";
                print ">&nbsp;<label for=\"cover[$i]\">обложка альбома</label>";
                print "<br /><br />";
                print "</td>";
                if ( ( $i % $COLS ) == 3 ) print "<td></td></tr>";
                $i++;
	        }
	        ?>
	        <tr>
	          <td class="dark" colspan="6" align="center"><input type="submit" value="Сохранить выбор" class="inp"></td>
	        </tr>
		</table>
	    <?
	} elseif ($action == "addphoto") {
    ?>
	    <form action="photo_det.php" method="post" enctype="multipart/form-data">
	    <input type="hidden" name="action" value="addphoto">
	    <input type="hidden" name="album" value="<?=$album?>">
	    <input type="hidden" name="id" value="<?=$id?>">
	    <table width="100%" border="0" cellspacing="2" align="left">
        <? if ($upload_res) { ?>
	        <tr>
	          <td class="dark"><p><?=$upload_res?></td>
	        </tr>
        <? } ?>
	        <tr>
	          <td class="light">Загрузить в <b><?=GetPathTxt($id)?></b></td>
	        </tr>
	        <tr>
	          <td class="light">Имя автора фотографий: <input name="photo_author" value="<?=htmlspecialchars(stripslashes($photo_author))?>" style="width: 200px">
	          или выберите пользователя форума:
		        <?
					$art_res=$db->sql_query("SELECT * FROM phpbb_users");
					$p = array();
				   	while ($line=$db->sql_fetchrow($art_res))
				   		$p[strtolower($line['username'])] = $line;
				   	ksort($p);
					print "<select name=\"photo_author_id\">";
					print "<option value=\"0\"></option>";
					if (!$photo_author_id) $photo_author_id = $rights['id_forum_user'];
			    	foreach ($p as $p1) {
			        	print "<option value=\"".$p1['user_id']."\"";
			        	if ($photo_author_id==$p1['user_id']) print " selected";
			        	print ">".$p1['username']."</option>";
			    	}
    				print "</select>";
    		        ?>
	          </td>
	        </tr>
            <tr>
            	<?
                	for ($i=0; $i<1; $i++) {
            			print "<td>";
            			for ($j=0; $j<10; $j++) {
	                		print "<input name=\"userfile[]\" type=\"file\"><br>";
                    	}
            			print "</td>";
                    }
                ?>
            </tr>
	        <tr>
	          <td class="dark" align="center"><input type="submit" value="Загрузить" class="inp"></td>
	        </tr>
        </table>
        </form>
        <?
	} elseif ($action == "delete_photo") {
    ?>
	    <form action="photo_det.php" method="post" name="deletephoto">
	    <input type="hidden" name="action" value="delete_photo">
	    <input type="hidden" name="album" value="<?=$album?>">
	    <input type="hidden" name="id" value="<?=$id?>">
	    <table border="0" cellspacing="2">
	        <tr>
	          <td class="dark" colspan="6"><h4><?=$descr?></h4></td>
	        </tr>
	    <?
	        $COLS = 4;
            $i=0;
	        $rst = $db->sql_query("SELECT * FROM photo WHERE id_album=$id");
	        while ($line = $db->sql_fetchrow($rst)) {
	            if ( ( $i % $COLS ) == 0 ) print "<tr><td></td>";
	            print "<td width=\"200\"><img src=\"/photo/albums/$album/small/".$line['fname']."\" align=\"top\" />";
	            print "<br><input type=\"checkbox\" name=\"cb[".$line['id']."]\" value=\"".$line['fname']."\">";
	            print "&nbsp;".$line['fname']."</td>";
	            if ( ( $i % $COLS ) == 3 ) print "<td></td></tr>";
	            $i++;
	        }
	        ?>
	        <tr>
	          <td class="dark" colspan="6" align="center"><input type="submit" value="Удалить выбранные" class="inp red" onClick="if(confirm('Удалить?')) document.forms.deletephoto.submit();"></td>
	        </tr>
	        </form>
		</table>
	    <?
    }
?>


<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>