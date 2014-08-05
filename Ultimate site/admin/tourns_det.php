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

if (!check_access('tourn'))
{
	header("location:login.php");
	exit;
};


if($action=="delete" && $_REQUEST['id']!="")
{
	$db->sql_query("delete from tourn where id=".$_REQUEST['id']);
	$db->sql_query("delete from tags where id_category=".MY_TOURN." AND id_item=".$_REQUEST['id']);
	$db->sql_query("delete from extra where id_category=".MY_TOURN." AND parent_id=".$_REQUEST['id']);
	header("location:tourns.php");
	exit;
}else if($action=="deleteextra") {
	$db->sql_query("delete from extra where id=".$_REQUEST['id_extra']);
	header("location:tourns.php");
	exit;
}else if($action=="save")
{
	if($_REQUEST['id']=="") {
		$char_id = $_REQUEST['char_id'];
		$rst = $db->sql_query("SELECT char_id FROM tourn WHERE char_id='$char_id'");
		if (!$db->sql_affectedrows($rst)) {
			$rst = $db->sql_query("SELECT MAX(id) FROM tourn");
			$line = $db->sql_fetchrow($rst);
			$id = $line[0]+1;
			$date_begin=substr($_REQUEST['start-date'],6,4).substr($_REQUEST['start-date'],3,2).substr($_REQUEST['start-date'],0,2);
			$date_end=substr($_REQUEST['end-date'],6,4).substr($_REQUEST['end-date'],3,2).substr($_REQUEST['end-date'],0,2);
			$db->sql_query("insert into tourn
	        	(id,id_forum_user,short_name,char_id,full_name,dat_begin,dat_end,country,city,short,doc,teamsO,teamsW,teams_can_set_results,players_can_set_results)
	            values($id,".$rights['id_forum_user'].",
	            '".mysql_escape_string($_REQUEST['short_name'])."',
	            '".$_REQUEST['char_id']."',
	            '".mysql_escape_string($_REQUEST['full_name'])."',
	            '$date_begin',
	            '$date_end',
	            '".mysql_escape_string($_REQUEST['country'])."',
	            '".mysql_escape_string($_REQUEST['city'])."',
	            '".mysql_escape_string($_REQUEST['short'])."',
	            '".mysql_escape_string($_REQUEST['doc'])."',
	            0".mysql_escape_string($_REQUEST['teamsO']).",
	            0".mysql_escape_string($_REQUEST['teamsW']).",
	            ".(isset($_REQUEST['teams_can_set_results'])?1:0).",
	            ".(isset($_REQUEST['players_can_set_results'])?1:0)."
	            )");
			//save_tags(2,$id,$_REQUEST['tags']);
		}
	} else {
		$char_id = $_REQUEST['char_id'];
		$rst = $db->sql_query("SELECT char_id FROM tourn WHERE char_id='$char_id' AND id<>".$_REQUEST['id']);
		if (!$db->sql_affectedrows($rst)) {
			$date_begin=substr($_REQUEST['start-date'],6,4).substr($_REQUEST['start-date'],3,2).substr($_REQUEST['start-date'],0,2);
			$date_end=substr($_REQUEST['end-date'],6,4).substr($_REQUEST['end-date'],3,2).substr($_REQUEST['end-date'],0,2);
			$db->sql_query("update tourn set
	        	short_name='".mysql_escape_string($_REQUEST['short_name'])."',
	        	char_id='".$_REQUEST['char_id']."',
	        	full_name='".mysql_escape_string($_REQUEST['full_name'])."',
	            dat_begin='$date_begin',
	            dat_end='$date_end',
	        	country='".mysql_escape_string($_REQUEST['country'])."',
	        	city='".mysql_escape_string($_REQUEST['city'])."',
	        	short='".mysql_escape_string($_REQUEST['short'])."',
	        	doc='".mysql_escape_string($_REQUEST['doc'])."',
	        	teamsO=".digits_only($_REQUEST['teamsO']).",
	        	teamsW=".digits_only($_REQUEST['teamsW']).",
	        	teams_can_set_results=".(isset($_REQUEST['teams_can_set_results'])?1:0).",
	        	players_can_set_results=".(isset($_REQUEST['players_can_set_results'])?1:0)."
	        	where id=".$_REQUEST['id']) or die(mysql_error());
			//save_tags(2,$_REQUEST['id'],$_REQUEST['tags']);
		}
	}
	header("location:tourns.php");

	exit;
} elseif ($action == "saveextra") {
	if(!$_REQUEST['id_extra']) {
		$char_id = $_REQUEST['char_id'];
		$rst = $db->sql_query("SELECT char_id FROM extra WHERE id_category=".MY_TOURN." AND parent_id=".$_REQUEST['id']." AND char_id='$char_id'");
		if (!$db->sql_affectedrows($rst)) {
			$sql = "INSERT INTO extra (char_id, id_category, parent_id, id_forum_user, title, doc)
				VALUES ('$char_id',".MY_TOURN.",".$_REQUEST['id'].",".$rights['id_forum_user'].",'".mysql_escape_string($_REQUEST['title'])."','".mysql_escape_string($_REQUEST['doc'])."')";
			$db->sql_query($sql);
		}
	} else {
		$db->sql_query("UPDATE extra SET doc='".mysql_escape_string($_REQUEST['doc'])."', title='".mysql_escape_string($_REQUEST['title'])."' WHERE id=".$_REQUEST['id_extra']);
	}
	header("location:tourns.php");
	exit;
}


if ($action != "extra") {
	if($_REQUEST['id']!='')
	{
		$art_res=$db->sql_query("select * from tourn where id=".$_REQUEST['id']);
		$news=$db->sql_fetchrow($art_res);
		$short_name=htmlspecialchars(stripslashes($news['short_name']));
		$full_name=htmlspecialchars(stripslashes($news['full_name']));
		$date_begin=substr($news['dat_begin'],6,2).".".substr($news['dat_begin'],4,2).".".substr($news['dat_begin'],0,4);
		$date_end=substr($news['dat_end'],6,2).".".substr($news['dat_end'],4,2).".".substr($news['dat_end'],0,4);
		$country=htmlspecialchars(stripslashes($news['country']));
		$city=htmlspecialchars(stripslashes($news['city']));
		$short=htmlspecialchars(stripslashes($news['short']));
		$doc=htmlspecialchars(stripslashes($news['doc']));
		$char_id = $news['char_id'];
		$teamsO = digits_only($news['teamsO']);
		$teamsW = digits_only($news['teamsW']);
		$teams_can_set_results = digits_only($news['teams_can_set_results']);
		$players_can_set_results = digits_only($news['players_can_set_results']);
	    //$tags = get_tags(2,$_REQUEST['id'],0);
	} else {
		$year_begin=date(Y);
		$month_begin=date(m);
		$day_begin=date(d);
		$year_end=$year_begin;
		$month_end=$month_begin;
		$day_end=$day_begin;
		$teams_can_set_results = 1;
		$players_can_set_results = 1;
	}
} else {
	if(isset($_REQUEST['id_extra'])) {
		$id_extra = $_REQUEST['id_extra'];
		$rst = $db->sql_query("SELECT * FROM extra WHERE id='$id_extra'");
		$line = $db->sql_fetchrow($rst);
		$char_id = $line['char_id'];
		$doc = htmlspecialchars(stripslashes($line['doc']));
		$title = htmlspecialchars(stripslashes($line['title']));
	}
}

include ('include/header.php');

?>

<?
	if ($action!="extra") {
?>

<form action="tourns_det.php" method="post">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?=$news['id']?>">
<table width="100%" border="0" cellpadding="5" cellspacing="0" align="left">
<tr valign="top">
	<td>
	<p>
		Краткое название турнира
		<br /><font size="-2">Например: <strong>Лорд Новгород</strong></font>
		<br /><input name="short_name" style="width:400px" value="<?=$short_name?>" class="inp">
	</p>
	<p>
  		Ссылка
  		<br /><font size="-2">Например: <strong>lord-novgorod-2007</strong> или <strong>kiev-hat-2007</strong> (маленькие латинские буквы и арабские цифры only!)</font>
  		<br /><a>http://ultimate.com.ua/tourn/</a><input name="char_id" style="width:200px; color: #0097ee;" value="<?=$char_id?>" class="inp">/
	</p>
	<p>
		Полное название турнира
  		<br /><input name="full_name" style="width:400px" value="<?=$full_name?>" class="inp">
	</p>
	<br />
	<p>
		Дата начала
	    <br /><input name="start-date" id="start-date" value="<?=$date_begin?>" class="date-pick inp" />
	</p>
	<br />
	<br />
	<p>
		Дата окончания
	    <br /><input name="end-date" id="end-date"  value="<?=$date_end?>"class="date-pick inp" />
	</p>
	<br />
	<br />
	<p>
		Страна
  		<br /><input name="country" style="width:400px" value="<?=stripslashes($country)?>" class="inp">
	</p>
	<p>
		Город
  		<br /><input name="city" style="width:400px" value="<?=stripslashes($city)?>" class="inp">
	</p>
	<p>
		Краткая информация (необязательно)<br />
  		<br /><textarea name="short" value="" cols="60" rows="10" class="inp"><?=$short?></textarea>
	</p>
	<br />
	<p>
		Открытый дивизион: <input name="teamsO" style="width:100px" value="<?=stripslashes($teamsO)?>" class="inp"> команд
	</p>
	<p>
		Женский дивизион: <input name="teamsW" style="width:100px" value="<?=stripslashes($teamsW)?>" class="inp"> команд
	</p>
	<p>
		<input type="checkbox" id="teams_can_set_results" name="teams_can_set_results"<?=($teams_can_set_results?" checked":"")?>> <label for="teams_can_set_results">команды могут отмечать свои результаты</label>
		<br /><input type="checkbox" id="players_can_set_results" name="players_can_set_results"<?=($players_can_set_results?" checked":"")?>> <label for="players_can_set_results">игроки могут отмечать участие в этом турнире</label>
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
	} else {
?>
<form action="tourns_det.php" method="post">
<input type="hidden" name="action" value="saveextra">
<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
<input type="hidden" name="id_extra" value="<?=$_REQUEST['id_extra']?>">
<table width="100%" border="0" cellpadding="5" cellspacing="0" align="left">
<tr valign="top">
<td>
	<p>
		Краткое название
		<br /><font size="-2">Например: <strong>Регламент</strong></font>
		<br /><input name="title" style="width:400px" value="<?=$title?>" class="inp">
  	</p>
  	<p>
<? if ($char_id!="team") { ?>
  		Ссылка<br /><font size="-2">Например: <strong>reglament</strong> (маленькие латинские буквы и арабские цифры only!)</font>
		<br /><a>http://ultimate.com.ua/tourn/%турнир%/</a><input name="char_id" style="width:200px; color: #0097ee;" value="<?=$char_id?>">/
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