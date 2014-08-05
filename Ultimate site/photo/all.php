<?
	$title = "Все фотографии";
	$pg = "photo/1";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>
<style>
.th {
	float: left;
	padding: 0;
	margin: 0;
}
</style>
<table width="100%" cellspacing="0" cellpadding="15" border="0">
	<tr valign="top">
		<td>

		<?
			$r = $db->sql_query("SELECT * FROM photo ORDER BY dateadd DESC");
			while ($l = $db->sql_fetchrow($r)) {
				$url = GetPath($db, $l['id_album']);
				$path = "/photo/albums/$url";
				$fname = $l['fname'];
				if (file_exists($_SERVER['']."$path/small/_$fname"))
					print "<div class=\"th\"><a href=\"/photo/$url/-".$l['id']."\"><img src=\"$path/small/_$fname\" /></a></div>";
			}
		?>


		</td>
	</tr>
</table>


<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>

