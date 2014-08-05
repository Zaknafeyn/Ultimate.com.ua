<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];

if (!$rights['all_rights']) {
    header("location:login.php");
    exit;
}

	$title = "Администраторы";
	include "include/header.php";


	$sql = "SELECT *
        FROM `admins` a
        LEFT JOIN phpbb_users u
        ON a.id_forum_user=u.user_id";
    $rst = $db->sql_query($sql);
    $a = array(
    	"all_rights"=>array("desc"=>"<h4>Полный доступ</h4>","val"=>array()),
    	//"main_events_edit"=>array("desc"=>"<b>Редактирование событий на главной странице</b>","val"=>array()),
        //"main_events_add"=>array("desc"=>"<b>Добавление событий на главную страницу</b><br>редактировать можно только СВОИ события","val"=>array()),
    	//"sms_edit"=>array("desc"=>"<b>Редактирование новостей</b>","val"=>array()),
        //"sms_add"=>array("desc"=>"<b>Добавление новостей</b><br>редактировать можно только СВОИ новости","val"=>array()),
    	"practice_edit"=>array("desc"=>"<b>Редактирование тренировок</b>","val"=>array()),
        "practice_add"=>array("desc"=>"<b>Добавление тренировок </b><br>редактировать можно только СВОИ тренировки","val"=>array()),
    	"news_edit"=>array("desc"=>"<b>Редактирование всех новостей</b>","val"=>array()),
        "news_add"=>array("desc"=>"<b>Добавление новостей</b><br>редактировать можно только СВОИ новости","val"=>array()),
    	"tourn_edit"=>array("desc"=>"<b>Редактирование всех турниров</b>","val"=>array()),
        "tourn_add"=>array("desc"=>"<b>Добавление турниров</b><br>редактировать можно только СВОИ турниры","val"=>array()),
        "anketa"=>array("desc"=>"<b>Просмотр анкет</b>","val"=>array()),
    	//"video_edit"=>array("desc"=>"<b>Редактирование всех видео-роликов</b>","val"=>array()),
        //"video_add"=>array("desc"=>"<b>Добавление видео-роликов</b><br>редактировать можно только СВОИ видео-ролики","val"=>array()),
    	//"calendar_edit"=>array("desc"=>"<b>Редактирование всех событий календаря</b>","val"=>array()),
        //"calendar_add"=>array("desc"=>"<b>Добавление событий в календать</b><br>редактировать можно только СВОИ события","val"=>array()),
    	"manual_edit"=>array("desc"=>"<b>Редактирование всех статей в учебнике</b>","val"=>array()),
        "manual_add"=>array("desc"=>"<b>Добавление статей в учебник</b><br>редактировать можно только СВОИ статьи","val"=>array()),
    	"blog_edit"=>array("desc"=>"<b>Редактирование записей в блоге</b>","val"=>array()),
        "blog_add"=>array("desc"=>"<b>Добавление записей в блог</b><br>редактировать можно только СВОИ записи","val"=>array()),
    	"photo_add"=>array("desc"=>"<b>Закачка фотографий</b><br><u>Можно</u>: создавать новые альбомы, закачивать фотографии, отмечать фотографии для отображения на главной странице<br><u>Нельзя</u>: удалять альбомы, удалять фотографии","val"=>array()),
    	//"comments"=>array("desc"=>"<b>Удаление комментариев</b>","val"=>array())
    );
    $names = array();
	while ( $row = $db->sql_fetchrow($rst) ) {
    	array_push($a["all_rights"]["val"], $row['all_rights']);
    	//array_push($a["main_events_edit"]["val"], $row["main_events_edit"]);
    	//array_push($a["main_events_add"]["val"], $row["main_events_add"]);
    	//array_push($a["sms_edit"]["val"], $row["sms_edit"]);
    	//array_push($a["sms_add"]["val"], $row["sms_add"]);
    	array_push($a["practice_edit"]["val"], $row["practice_edit"]);
    	array_push($a["practice_add"]["val"], $row["practice_add"]);
    	array_push($a["news_edit"]["val"], $row["news_edit"]);
    	array_push($a["news_add"]["val"], $row["news_add"]);
    	array_push($a["tourn_edit"]["val"], $row["tourn_edit"]);
    	array_push($a["tourn_add"]["val"], $row["tourn_add"]);
    	array_push($a["anketa"]["val"], $row["anketa"]);
    	//array_push($a["video_edit"]["val"], $row["video_edit"]);
    	//array_push($a["video_add"]["val"], $row["video_add"]);
    	//array_push($a["calendar_edit"]["val"], $row["calendar_edit"]);
    	//array_push($a["calendar_add"]["val"], $row["calendar_add"]);
    	array_push($a["manual_edit"]["val"], $row["manual_edit"]);
    	array_push($a["manual_add"]["val"], $row["manual_add"]);
    	array_push($a["blog_edit"]["val"], $row["blog_edit"]);
    	array_push($a["blog_add"]["val"], $row["blog_add"]);
    	array_push($a["photo_add"]["val"], $row["photo_add"]);
    	//array_push($a["comments"]["val"], $row["comments"]);
    	array_push($names, array($row['id'],$row["username"]));
    }
?>
<table border="0" cellspacing="1" align="left" valign="top">
<tr><td class="light">
	<form action="admins_det.php" method="post">
	    <input type="hidden" name="action" value="add">
	    <input type="submit" value="Новый администратор" class="inp">
	</form>
</td></tr>
<tr><td>
<form action="admins_det.php" method="post">
	<table border="0" cellspacing="1" align="left" valign="top">
	<tr><td width="200" class="dark"></td>
	<?
	    foreach ($names as $value) {
	        print "<td class=\"dark\" width=\"10\" style=\"padding: 5\"><h3><font color=white>".$value[1]."</font></h3></td>";
	    }
	?>
	</tr>
	<?
	    foreach ($a as $key1=>$value1) {
	        print "<tr>";
	        print "<td class=\"light\" style=\"padding: 5\">".$value1["desc"]."</td>";
            $i=0;
	        foreach ($value1["val"] as $value2) {
	            print "<td class=\"light\" align=\"center\">";
                print "<input type=\"checkbox\" name=\"cb[".$names[$i][0]."][".$key1."]\" value=\"\"";
                if ($value2) print " checked";
                print "></td>";
                $i++;
            }
	        print "</tr>";
	    }
	?>
    <tr><td colspan="<?=$i+1?>" align="center">
    	<input type="hidden" name="action" value="check">
    	<input type="submit" value="Сохранить" class="inp">
    </td></td>
	</table>
</form>
</td></tr>

</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>