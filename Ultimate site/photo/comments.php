<?
    if (isset($_GET['album'])) {
    	$album=$_GET['album'];
		if (substr($album,-1)=="/")
			$album = substr($album,0,strlen($album)-1);
		$ok = false;
		foreach($al as $a)
			if ($a['url']==$album) {
				$ok=true;
				$album = $a;
				$aid = $album['aid'];
			}
		if (!$ok)
			die();
    } else
    	die();


	$title = "Все комментарии альбома ".$album['titlefull'];
	$meta_description = $title;
	$meta_keywords = $title;

	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";


?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="100%" colspan="2" style="padding-left: 15;">
				<p><?=$loveurl?>
				<br /><?=GetPathTxt($album['aid']);?>
				</p><p><h1><? print "Все комментарии альбома <a href=\"/photo/".$album['url']."/\">".$album['titlefull']."</a>"; ?></h1></p>
		</td>
	</tr>
	<?
		$url = $album['url'];
		$r = $db->sql_query("SELECT DISTINCT id_item, fname, tn_width, tn_height, tn_fname, picasa_user
			FROM comments c
			LEFT JOIN photo p ON c.id_item=p.id
			LEFT JOIN photo_albums a ON p.id_album=a.id
			WHERE id_category=".MY_PHOTO." AND a.id=$aid
			");
		if ($db->sql_affectedrows($r)) {
		    while ($l = $db->sql_fetchrow($r)) {
				print "<tr valign=\"top\">";
				print "<td style=\"padding-left: 15;\" width=\"250\">";
				if ($l['picasa_user']) {
			    	print "<p><div class=\"thumbnails\"><div class=\"picasa0\"><div class=\"picasa\">";
	    			print "<a href=\"/photo/$url/-".$l['id_item']."\">";
	    			print "<img width=\"".$l["tn_width"]."\" height=\"".$l["tn_height"]."\" src=\"".$l['tn_fname']."\" />";
	    			print "</a>";
	    			print "</div></div></div>";
				} else {
					print "<p><div class=\"thumbnails\">";
					print "<ins class=\"thumbnail\">";
	   				print "<div class=\"r\">";
		    		print "<a href=\"/photo/$url/-".$l['id_item']."#o0\">";
		    		print "<img width=\"150\" height=\"150\" src=\"/photo/albums/$url/small/".$l['fname']."\" style=\"border: none\" />";
		    		print "</a></div></ins></div>";
				}
	    		print "</p><br /></td>";
				print "<td width=\"75%\" style=\"padding-right: 20;\">".get_comments(MY_PHOTO,$l['id_item'],"","ASC")."<br /></td>";
				print "</tr>";
			}
			print "</div>";

		} else {
			print "<tr><td style=\"padding-left: 20;\"><p>Нет комментариев, как вы вообще попали на эту страницу?</p></td></tr>";
		}
	?>
</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>