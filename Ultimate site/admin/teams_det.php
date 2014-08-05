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


$id=digits_only($_REQUEST['id']);
$id_extra=digits_only($_REQUEST['id_extra']);



if (!$rights['all_rights'])
    if (!$rights['team_'.$id.'_edit']) {
	    header("location:login.php");
	    exit;
    }



if($action=="delete" && $id)
{
	$db->sql_query("DELETE FROM teams WHERE id=$id");
	$db->sql_query("ALTER TABLE admins DROP team_".$id."_edit");
	header("location:teams.php");
}else if($action=="deleteextra") {
//	$db->sql_query("delete from extra where id=".$id_extra);
	$db->sql_query("update extra set parent_id=999 where id=".$id_extra);
	header("location:teams.php");
	exit;
}else if($action=="save")
{
	if(!$id) {
		$char_id = $_REQUEST['char_id'];
		$rst = $db->sql_query("SELECT char_id FROM teams WHERE char_id='$char_id'");
		if (!$db->sql_affectedrows($rst)) {
	    	$sql = "INSERT INTO teams (team_name, char_id, id_city, id_division,team_contacts)
	        	VALUES('".mysql_escape_string($_REQUEST['team_name'])."','$char_id',".digits_only($_REQUEST['id_team_city']).",".digits_only($_REQUEST['id_team_division']).",'".mysql_escape_string($_REQUEST['team_contacts'])."')";
	        $rst = $db->sql_query($sql) or die ($sql."<p>".mysql_error());
	        $sql = "SELECT * FROM teams WHERE char_id='$char_id'";
	        $rst = $db->sql_query($sql);
	        $line = $db->sql_fetchrow($rst);
	        if ($line['id']) {
				$db->sql_query("ALTER TABLE admins ADD team_".$line['id']."_edit TINYINT DEFAULT 0");
				$db->sql_query("INSERT INTO extra (char_id, id_category, parent_id, id_forum_user, title) VALUES (
					'team',".MY_TEAMS.",".$line['id'].",".$rights['id_forum_user'].",'Команда')"
				);
			}
		}
	} else {
    	$sql = "UPDATE teams SET
        	team_name='".mysql_escape_string($_REQUEST['team_name'])."',
        	char_id='".mysql_escape_string($_REQUEST['char_id'])."',
            id_city=".digits_only($_REQUEST['id_team_city']).",
            id_division=".digits_only($_REQUEST['id_team_division']).",
            team_contacts='".mysql_escape_string($_REQUEST['team_contacts'])."'
            WHERE id=$id";
        $db->sql_query($sql);
    }

	header("location:teams.php");

	exit;
} elseif ($action == "saveextra") {
	if(!$id_extra) {
		$char_id = $_REQUEST['char_id'];
		$rst = $db->sql_query("SELECT char_id FROM extra WHERE id_category=".MY_TEAMS." AND parent_id=$id AND char_id='$char_id'");
		if (!$db->sql_affectedrows($rst)) {			$rst = $db->sql_query("SELECT MAX(o) FROM extra WHERE id_category=".MY_TEAMS." AND parent_id=$id");
			$line=$db->sql_fetchrow($rst);
			$sql = "INSERT INTO extra (char_id, id_category, parent_id, id_forum_user, title, doc,o)
				VALUES ('$char_id',".MY_TEAMS.",$id,".$rights['id_forum_user'].",'".mysql_escape_string($_REQUEST['title'])."','".mysql_escape_string($_REQUEST['doc'])."',".($line[0]+10).")";
			$db->sql_query($sql);
		}
	} else {
		$db->sql_query("UPDATE extra SET doc='".mysql_escape_string($_REQUEST['doc'])."', title='".mysql_escape_string($_REQUEST['title'])."' WHERE id=$id_extra");
	}
	header("location:teams.php");
	exit;
} elseif (($action=="moveup")||($action=="movedown")) {
    if ($action=="moveup")
    	$sql = "SELECT MAX(o) as o FROM extra WHERE id_category=".MY_TEAMS." AND parent_id=".$id." AND o<".$_REQUEST['o']." AND char_id<>'team'";
    else
    	$sql = "SELECT MIN(o) as o FROM extra WHERE id_category=".MY_TEAMS." AND parent_id=".$id." AND o>".$_REQUEST['o']." AND char_id<>'team'";
	$rst = $db->sql_query($sql);
    if ($db->sql_affectedrows($rst)) {    	$line = $db->sql_fetchrow($rst);
        $db->sql_query("UPDATE extra SET o=".digits_only($_REQUEST['o'])." WHERE id_category=".MY_TEAMS." AND parent_id=".$id." AND o=".$line['o']);
        $db->sql_query("UPDATE extra SET o=".$line['o']." WHERE id=".$extra_id);
    }
    header("location:teams.php");
    exit;
}

if ($action != "extra") {
	if($id)
	{
		$art_res=$db->sql_query("SELECT * FROM teams WHERE id=$id");
		$news=$db->sql_fetchrow($art_res);
		$team_name=$news['team_name'];
		$team_contacts=$news['team_contacts'];
		$char_id=$news['char_id'];
	    $id_team_city = $news['id_city'];
	    $id_team_division = $news['id_division'];

	} else {
		$art_res=$db->sql_query("SELECT * FROM teams WHERE 1=0");
		$news=$db->sql_fetchrow($art_res);
	}
} elseif($id_extra) {
	$rst = $db->sql_query("SELECT * FROM extra WHERE id='$id_extra'");
	$line = $db->sql_fetchrow($rst);
	$char_id = $line['char_id'];
	$doc = $line['doc'];
	$title = $line['title'];
}


?>

<?include "include/header.php"?>


<?
	if ($action!="extra") {
?>

<form action="teams_det.php" method="post">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?=$news['id']?>">
<table width="100%" border="0" cellspacing="2" align="left">
<tr>
  <td>
  	<p>
  		Название команды
  		<br /><input name="team_name" style="width:400px; font-size: 1.9em;" value="<?=stripslashes($team_name)?>" class="inp">
  	</p>
  	<p>
		Ссылка
		<br /><a>http://ultimate.com.ua/teams/</a><input name="char_id" style="width:200px; color: #0097ee;" value="<?=stripslashes($char_id)?>" class="inp">/
  	</p>
  	<p>
		Город
  		<br /><select name="id_team_city" style="width:200px;" class="inp">
	      <?
	        $sql = "SELECT * FROM cities ORDER BY city";
	        $rst = $db->sql_query($sql);
	        while ($line = $db->sql_fetchrow($rst)) {
	            print "<option value=\"$line[id]\"";
                if ($line['id']==$id_team_city) print " selected='selected'";
                print ">$line[city]</option>";
	      	}
          ?>
  		</select>
  	</p>
  	<p>
		Дивизион
  		<br /><select name="id_team_division" style="width:200px;" class="inp">
	      <?
	        $sql = "SELECT * FROM divisions ORDER BY division";
	        $rst = $db->sql_query($sql);
	        while ($line = $db->sql_fetchrow($rst)) {
	            print "<option value=\"$line[id]\"";
                if ($line['id']==$id_team_division) print " selected='selected'";
                print ">$line[division]</option>";
	      	}
          ?>
	  	</select>
  	</p>
  	<br />
  	<p>
		Контактная информация (показывается на странице "Команды")
		<div>
			<textarea id="team_contacts" name="team_contacts" rows="30" cols="80" style="width: 80%" class="tinymce">
			<?=stripslashes($team_contacts)?>
			</textarea>
		</div>
		<!-- Some integration calls -->
		<a href="javascript:;" onmousedown="$('#team_contacts').tinymce().show();">[Show]</a>
		<a href="javascript:;" onmousedown="$('#team_contacts').tinymce().hide();">[Hide]</a>
		<a href="javascript:;" onmousedown="$('#team_contacts').tinymce().execCommand('Bold');">[Bold]</a>
		<a href="javascript:;" onmousedown="alert($('#team_contacts').html());">[Get contents]</a>
		<a href="javascript:;" onmousedown="alert($('#team_contacts').tinymce().selection.getContent());">[Get selected HTML]</a>
		<a href="javascript:;" onmousedown="alert($('#team_contacts').tinymce().selection.getContent({format : 'text'}));">[Get selected text]</a>
		<a href="javascript:;" onmousedown="alert($('#team_contacts').tinymce().selection.getNode().nodeName);">[Get selected element]</a>
		<a href="javascript:;" onmousedown="$('#team_contacts').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
		<a href="javascript:;" onmousedown="$('#team_contacts').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a>
	</p>
  	</td>
  </tr>
<tr><td align="center"><input type="submit" value="Сохранить" class="inp"></td></tr>
</table>
</form>


<?
	} else {
?>
<form action="teams_det.php" method="post">
<input type="hidden" name="action" value="saveextra">
<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
<input type="hidden" name="id_extra" value="<?=$_REQUEST['id_extra']?>">
<table width="100%" border="0" cellpadding="5" cellspacing="0" align="left">
<tr valign="top">
<td>
	<p>
		Краткое название
		<br /><font size="-2">Например: <strong>История</strong> или <strong>Награды</strong></font>
		<br /><input name="title" style="width:400px" value="<?=$title?>" class="inp">
  	</p>
  	<p>
<? if ($char_id!="team") { ?>
  		Ссылка<br /><font size="-2">Например: <strong>history</strong> или <strong>prize</strong> (маленькие латинские буквы и арабские цифры only!)</font>
		<br /><a>http://ultimate.com.ua/teams/%команда%/</a><input name="char_id" style="width:200px; color: #0097ee;" value="<?=$char_id?>" class="inp">/
<? } else { ?>
		<input name="char_id" style="width:400px" value="team" type="hidden">
<? } ?>
  	</p>
  	<br />
  	<p>
		Текст
		<div>
			<textarea id="doc" name="doc" rows="30" cols="80" style="width: 80%" class="tinymce">
			<?=stripslashes($doc)?>
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

<?
	}
?>


<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>