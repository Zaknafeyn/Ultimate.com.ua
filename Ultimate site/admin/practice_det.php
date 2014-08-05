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

if (!check_access('practice'))
{
	header("location:login.php");
	exit;
};


if ($action=="delete" && $_REQUEST['id']!="") {
	$db->sql_query("DELETE FROM practice WHERE id=".$_REQUEST['id']);
	header("location:practice.php");
	exit;
} elseif ($action=="deletepl" && $_REQUEST['id']!="") {
	$db->sql_query("DELETE FROM practice_places WHERE id=".$_REQUEST['id']);
	header("location:practice.php");
	exit;
} elseif ($action=="deletesch" && $_REQUEST['id']!="") {
	$db->sql_query("DELETE FROM practice_schedule WHERE id=".$_REQUEST['id']);
	header("location:practice.php");
	exit;
} elseif ($action=="editsch_switch" && $_REQUEST['id']!="") {
	$db->sql_query("UPDATE practice_schedule SET active=".$_REQUEST['active']." WHERE id=".$_REQUEST['id']);
	header("location:practice.php");
	exit;
}

elseif ($action=="save") {
	$parent_action = $_REQUEST['parent_action'];
    if (($parent_action == "edit")||($parent_action == "add")) {
	    if ($_REQUEST['id']=="") {
	        $db->sql_query("INSERT INTO practice
	            (id_forum_user,dat,id_city,id_place,place,comment)
	            values(".$rights['id_forum_user'].",".
                mktime($_REQUEST['hour'],$_REQUEST['minute'],0,$_REQUEST['month'],$_REQUEST['day'],$_REQUEST['year']).",".
                mysql_escape_string($_REQUEST['id_city']).",".
                mysql_escape_string($_REQUEST['id_place']).",'".
                ((!$_REQUEST['id_place']) ? mysql_escape_string($_REQUEST['place']) : ""  )."','".
                ((!$_REQUEST['id_place']) ? mysql_escape_string($_REQUEST['comment']) : ""  )."')");
	    } else {
	        $db->sql_query("UPDATE practice SET
	        	id_forum_user=".$rights['id_forum_user'].",
	        	dat=".mktime($_REQUEST['hour'],$_REQUEST['minute'],0,$_REQUEST['month'],$_REQUEST['day'],$_REQUEST['year']).",
	            id_place=".mysql_escape_string($_REQUEST['id_place']).",
	            place='".mysql_escape_string($_REQUEST['place'])."',
                comment='".mysql_escape_string($_REQUEST['comment'])."'
                WHERE id=".$_REQUEST['id']);
	    }
    } elseif (($parent_action == "editpl")||($parent_action == "addpl")) {
	    if ($_REQUEST['id']=="") {
	        $db->sql_query("INSERT INTO practice_places
	            (id_forum_user,id_city,short_name,place,comment)
	            values(".$rights['id_forum_user'].",".mysql_escape_string($_REQUEST['id_city']).",'".mysql_escape_string($_REQUEST['short_name'])."','".mysql_escape_string($_REQUEST['place'])."','".mysql_escape_string($_REQUEST['comment'])."')");
	    } else {
	        $db->sql_query("UPDATE practice_places SET
	            id_city=".mysql_escape_string($_REQUEST['id_city']).",
                short_name='".mysql_escape_string($_REQUEST['short_name'])."',
	            place='".mysql_escape_string($_REQUEST['place'])."',
                comment='".mysql_escape_string($_REQUEST['comment'])."'
                WHERE id=".$_REQUEST['id']);
	    }
    } elseif (($parent_action == "editsch")||($parent_action == "addsch")) {
	    if ($_REQUEST['id']=="") {
	        $db->sql_query("INSERT INTO practice_schedule
	            (id_forum_user,id_city,schedule)
	            values(".$rights['id_forum_user'].",".mysql_escape_string($_REQUEST['id_city']).",'".mysql_escape_string($_REQUEST['schedule'])."')");
	    } else {
	        $db->sql_query("UPDATE practice_schedule SET
	            id_city=".mysql_escape_string($_REQUEST['id_city']).",
	            schedule='".mysql_escape_string($_REQUEST['schedule'])."'
                WHERE id=".$_REQUEST['id']);
	    }
    }

	print mysql_error();
	header("location:practice.php");
	exit;
}


if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
  $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
else
  $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];

define('DR', $_root);
unset($_root);

// set $spaw_root variable to the physical path were control resides
// don't forget to modify other settings in config/spaw_control.config.php
// namely $spaw_dir and $spaw_base_url most likely require your modification
//$spaw_root = '/home/stepanov/public_html/happy/admin/spaw/';
$spaw_root = DR.'/admin/spaw/';


// include the control file
include $spaw_root.'spaw_control.class.php';
// here we add some styles to styles dropdown
//$spaw_dropdown_data['style']['default'] = 'No styles';
//$spaw_dropdown_data['style']['style1'] = 'Style no. 1';
//$spaw_dropdown_data['style']['style2'] = 'Style no. 2';


	$title = "Редактирование тренировок";
	include "include/header.php";

?>


<form action="practice_det.php" method="post">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="parent_action" value="<?=$action?>">
	<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
	<table width="100%" border="0" cellspacing="1" align="left">
    <? if (($action == "edit")||($action == "add")) {
    		$place = "";
            $comment = "";
	        $year=date(Y);
	        $mon=date(m);
	        $day=date(d);
    		if ($_REQUEST['id']) {
	            $sql = "SELECT * FROM practice WHERE id=".$_REQUEST['id'];
	            $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
	            if ($db->sql_affectedrows($rst)) {
	                $line = $db->sql_fetchrow($rst);
                    $d = $line['dat'];
                    $day = date("d",$d);
                    $mon = date("m",$d);
                    $year = date("Y",$d);
                    $hour = date("H",$d);
                    $minute = date("i",$d);
                    $id_place = $line['id_place'];
	                $place = htmlspecialchars(stripslashes($line['place']));
	                $comment = htmlspecialchars(stripslashes($line['comment']));
            	}
            } ?>
       	<tr>
        	<td></td>
            <td><input type="hidden" name="id_city" value="<?=$_REQUEST['id_city']?>"></td>
        </tr>
	    <tr>
	        <td width="20%" class="dark">Дата, время тренировки</td>
	        <td class="light">
            	<p><font color="gray">//надо будет сделать выбор "завтра", "послезавтра" или "вторнег", "четверк"...
            	<p>Дата&nbsp;
	            <select name="day" class="inp">
	                <? for ($i=1;$i<=31; $i++) { ?>
	                     <option value="<?=sprintf("%02d",$i)?>" <? if ($day==sprintf("%02d",$i)) {echo "selected='selected'";}?>><?=$i?></option>
	                   <? } ?>
	            </select>
	            <select name="month" class="inp">
	                <? for ($i=1;$i<=12; $i++) { ?>
	                     <option value="<?=sprintf("%02d",$i)?>" <? if ($mon==sprintf("%02d",$i)){echo "selected='selected'";}?>><?=$month[sprintf("%02d",$i)]?></option>
	                <? } ?>
	            </select>
	            <select name="year" class="inp">
	                <? for ($i=1995;$i<date(Y)+5; $i++) { ?>
	                     <option value="<?=sprintf("%04d",$i)?>" <? if ($year==sprintf("%04d",$i)){echo "selected='selected'";}?>><?=$i?></option>
	                <? } ?>
	            </select>
            	<p>Время&nbsp;
	            <select name="hour" class="inp">
	                <? for ($i=0;$i<24; $i++) { ?>
	                     <option value="<?=sprintf("%02d",$i)?>" <? if ($hour==sprintf("%02d",$i)){echo "selected='selected'";}?>><?=sprintf("%02d",$i)?></option>
	                <? } ?>
	            </select>&nbsp;:
	            <select name="minute" class="inp">
	                <? for ($i=0;$i<60; $i++) { ?>
	                     <option value="<?=sprintf("%02d",$i)?>" <? if ($minute==sprintf("%02d",$i)){echo "selected='selected'";}?>><?=sprintf("%02d",$i)?></option>
	                <? } ?>
	            </select>
            </td>
	    </tr>
       	<tr>
        	<td width="20%" class="dark">Место тренировки</td>
            <td class="light">
            	<p><br>Выберите из списка...
            	<select name="id_place" class="inp">
                <option value="0">- Выберите из списка -</option>
                <?
	                $sql = "SELECT * FROM practice_places WHERE id_city=".$_REQUEST['id_city'];
	                $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
                    if ($db->sql_affectedrows($rst))
	                    while ($line = $db->sql_fetchrow($rst)) {
	                        print "<option value=\"$line[id]\"".(($line['id']==$id_place)?" selected":"").">".stripslashes($line['short_name'])."</option>";
	                    }
                ?>
                </select>
                <p>... <b>ИЛИ</b> введите вручную:
                <p>Место <input name="place" style="width:400px" value="<?=$place?>">
                <p>Комментарий <input name="comment" style="width:400px" value="<?=$comment?>"><p>
            </td>
        </tr>
    <? } elseif (($action == "editpl")||($action == "addpl")) {
    		$short_name = "";
    		$place = "";
            $comment = "";
    		if ($_REQUEST['id']) {
	            $sql = "SELECT * FROM practice_places WHERE id=".$_REQUEST['id'];
	            $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
	            if ($db->sql_affectedrows($rst)) {
	                $line = $db->sql_fetchrow($rst);
	                $short_name = stripslashes($line['short_name']);
	                $place = htmlspecialchars(stripslashes($line['place']));
	                $comment = htmlspecialchars(stripslashes($line['comment']));
            	}
            } ?>
       	<tr>
        	<td width="20%" class="dark">Город</td>
            <td class="light">
            	<select name="id_city" class="inp">
                <?
	                $sql = "SELECT * FROM cities ORDER BY city";
	                $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
	                while ($line = $db->sql_fetchrow($rst)) {
                    	print "<option value=\"$line[id]\"".(($line['id']==$_REQUEST['id_city'])?" selected":"").">".stripslashes($line['city'])."</option>";
                    }
                ?>
                </select>
            </td>
        </tr>
	    <tr>
	        <td class="dark">Краткое название (чисто для себя, чтобы потом выбирать из списка при добавлении тренировок)</td>
	        <td class="light"><input name="short_name" style="width:400px" value="<?=$short_name?>"></td>
	    </tr>
	    <tr>
	        <td class="dark">Место тренировок</td>
	        <td class="light"><input name="place" style="width:400px" value="<?=$place?>"></td>
	    </tr>
	    <tr>
	        <td class="dark">Комментарии</td>
	        <td class="light"><input name="comment" style="width:400px" value="<?=$comment?>"></td>
	    </tr>
    <? } elseif (($action == "editsch")||($action == "addsch")) {
    		$schedule="";
    		if ($_REQUEST['id']) {
	            $sql = "SELECT * FROM practice_schedule WHERE id=".$_REQUEST['id'];
	            $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
	            if ($db->sql_affectedrows($rst)) {
	                $line = $db->sql_fetchrow($rst);
	                $schedule = stripslashes($line['schedule']);
            	}
            } ?>
       	<tr>
        	<td width="20%" class="dark">Город</td>
            <td class="light">
            	<select name="id_city" class="inp">
                <?
	                $sql = "SELECT * FROM cities ORDER BY city";
	                $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
	                while ($line = $db->sql_fetchrow($rst)) {
                    	print "<option value=\"$line[id]\"".(($line['id']==$_REQUEST['id_city'])?" selected":"").">".stripslashes($line['city'])."</option>";
                    }
                ?>
                </select>
            </td>
        </tr>
	    <tr>
	        <td class="dark">Расписание тренировок</td>
	        <td class="light">
	            <?
	                $sd = new SPAW_Wysiwyg('schedule' /*name*/,htmlspecialchars(stripslashes($schedule)) /*value*/,'ru','full');
	                $sd->show();
	            ?>
	        </td>
	    </tr>
    <? } ?>
		<tr><td colspan="2" align="center"><input type="submit" value="Сохранить" class="inp"></td></tr>
	</table>
</form>

<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>