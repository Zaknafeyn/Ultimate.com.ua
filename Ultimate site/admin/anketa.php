<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];
if (!$rights['all_rights'])
    if (!$rights['anketa']) {
	    header("location:login.php");
	    exit;
    }


$art_res=$db->sql_query("SELECT * FROM `anketa` ORDER BY id DESC");

	$title = "Анкеты";
	include "include/header.php";

?>


<table width="100%" border="0" cellspacing="1" align="left">
<td class="dark">Дата заполнения</td>
<td class="dark">ФИО</td>
<td class="dark">Город</td>
<td class="dark">Контакты</td>
<td class="dark">О фризби узнал(а)</td>
<td class="dark">Комментарий</td>
<td class="dark">IP</td>
</tr>
<?
while($row=$db->sql_fetchrow($art_res)):
?>

<tr>
<td class="light">
	<?=$row["dat"]?>
</td>
<td class="light">
	<?=$row["name"]?>
</td>
<td class="light">
	<?=$row["city"]?>
</td>
<td class="light">
	<?=$row["contact"]?>
</td>
<td class="light">
	<?=$row["learned_from"]?>
</td>
<td class="light">
	<?=$row["comments"]?>
</td>
<td class="light">
	<?=$row["ip"]?>
</td>

</tr>

<?
endwhile;
?>

</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>