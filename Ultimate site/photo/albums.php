<?
	$title = "Альбомы";
	$meta_description = "Алтимат фризби фото ultimate frisbee photo";
	$meta_keywords = "фото, альбомы, фотографии, алтимат, ultimate, frisbee, photo, albums";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td style="padding-left: 15;">
			<p><?=$loveurl?>
			</p><p><h1>Альбомы</h1></p>
		</td>
	</tr>
	<tr style="background-color: #ededed;">
		<td style="padding-left: 15;">
			<p>
			<?
				ShowAlbums(0);
			?>
			</p>
		</td>
	</tr>
</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>