<?
session_start();
$rights = $_SESSION['rights'];
?>

<table width="120" cellspacing="1" cellpadding="3">
<tr><td>

<?	if ($rights['all_rights']||$rights['sms_add']||$rights['sms_edit']) { ?>
<a href="news.php">�������</a>
<?	} else { ?>
�������
<? } ?>

<p>
<?	if ($rights['all_rights']||$rights['practice_add']||$rights['practice_edit']) { ?>
<a href="practice.php">����������</a>
<?	} else { ?>
����������
<? } ?>

<p>
</p>

<?
	if ($rights['all_rights']||$rights['news_add']||$rights['news_edit']) { ?>
<a href="blog.php">����</a>
<?	} else { ?>
����
<? } ?>

<p>

<?	if ($rights['all_rights']||$rights['tourn_edit']||$rights['tourn_add']) {
?>
<a href="tourns.php">�������</a>
<?	} else { ?>
�������
<?	} ?>

<p>

<?
	if ($rights['all_rights']||$rights['photo_add']) {
?>
<a href="photo.php">����������</a>
<?	} else { ?>
����������
<?	} ?>
<br />

<p>

<?
	if ($rights['all_rights']||$rights['video_add']) {
?>
<a href="video.php">�����</a>
<?	} else { ?>
�����
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
<a href="teams.php">�������</a>
<!--<br />
<a href="players.php">������</a>-->
<?	} else { ?>
�������
<!--<br />
������-->
<?	} ?>

<p>
<?
	if ($rights['all_rights']||$rights['manual_edit']||$rights['manual_add']) {
?>
<a href="manual.php">�������</a>
<?	} else { ?>
�������
<?	} ?>
<br />

<p>
<?
	if ($rights['all_rights']||$rights['sale_edit']||$rights['sale_add']) {
?>
<a href="sale.php">�������</a>
<?	} else { ?>
�������
<?	} ?>
<br />

<p>
<?
	if ($rights['all_rights']||$rights['anketa']) {
?>
<a href="anketa.php">������</a>
<?	} else { ?>
������
<?	} ?>



<p>
��������������
<?
	if ($rights['all_rights']) {
?>
&nbsp;&nbsp;<a href="admins.php">�����</a>
<br />
&nbsp;&nbsp;<a href="admins_teams.php">������</a>
<?	}  else { ?>
&nbsp;&nbsp;�����
<br />
&nbsp;&nbsp;������
<? } ?>


<p>
<a href="login.php?action=logout">�����</a>
</td></tr>
</table>