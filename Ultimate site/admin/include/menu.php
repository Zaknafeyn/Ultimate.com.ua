<?
session_start();
$rights = $_SESSION['rights'];
?>

<table width="120" cellspacing="1" cellpadding="3">
<tr><td>

<?	if ($rights['all_rights']||$rights['sms_add']||$rights['sms_edit']) { ?>
<a href="news.php">Новости</a>
<?	} else { ?>
Новости
<? } ?>

<p>
<?	if ($rights['all_rights']||$rights['practice_add']||$rights['practice_edit']) { ?>
<a href="practice.php">Тренировки</a>
<?	} else { ?>
Тренировки
<? } ?>

<p>
</p>

<?
	if ($rights['all_rights']||$rights['news_add']||$rights['news_edit']) { ?>
<a href="blog.php">Блог</a>
<?	} else { ?>
Блог
<? } ?>

<p>

<?	if ($rights['all_rights']||$rights['tourn_edit']||$rights['tourn_add']) {
?>
<a href="tourns.php">Турниры</a>
<?	} else { ?>
Турниры
<?	} ?>

<p>

<?
	if ($rights['all_rights']||$rights['photo_add']) {
?>
<a href="photo.php">Фотографии</a>
<?	} else { ?>
Фотографии
<?	} ?>
<br />

<p>

<?
	if ($rights['all_rights']||$rights['video_add']) {
?>
<a href="video.php">Видео</a>
<?	} else { ?>
Видео
<?	} ?>
<br />


<p>
<?
	$sql = "SELECT * FROM teams";
    $rst = $db->sql_query($sql);
    $access = false;
    while ($line = $db->sql_fetchrow($rst))
    	if ($rights['team_'.$line['id'].'_edit'])
        	$access = true;
	if ($rights['all_rights']||$access) {
?>
<a href="teams.php">Команды</a>
<!--<br />
<a href="players.php">Игроки</a>-->
<?	} else { ?>
Команды
<!--<br />
Игроки-->
<?	} ?>

<p>
<?
	if ($rights['all_rights']||$rights['manual_edit']||$rights['manual_add']) {
?>
<a href="manual.php">Учебник</a>
<?	} else { ?>
Учебник
<?	} ?>
<br />

<p>
<?
	if ($rights['all_rights']||$rights['sale_edit']||$rights['sale_add']) {
?>
<a href="sale.php">Магазин</a>
<?	} else { ?>
Магазин
<?	} ?>
<br />

<p>
<?
	if ($rights['all_rights']||$rights['anketa']) {
?>
<a href="anketa.php">Анкеты</a>
<?	} else { ?>
Анкеты
<?	} ?>



<p>
Администраторы
<?
	if ($rights['all_rights']) {
?>
&nbsp;&nbsp;<a href="admins.php">сайта</a>
<br />
&nbsp;&nbsp;<a href="admins_teams.php">команд</a>
<?	}  else { ?>
&nbsp;&nbsp;сайта
<br />
&nbsp;&nbsp;команд
<? } ?>


<p>
<a href="login.php?action=logout">Выход</a>
</td></tr>
</table>