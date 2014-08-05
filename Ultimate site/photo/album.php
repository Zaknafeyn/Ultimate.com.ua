<?
    function GetAlbumByPath($char_id, $parent_id=0) {
	global $db;
		if (substr($char_id,-1)=="/")
			$char_id = substr($char_id,0,strlen($char_id)-1);
    	$a = explode("/",$char_id);
        $sql = "SELECT * FROM  photo_albums WHERE char_id='".$a[0]."' AND parent_id=$parent_id";
        $rst = $db->sql_query ($sql) or die(mysql_error());
        if ($db->sql_affectedrows($rst)) {
        	$line = $db->sql_fetchrow($rst);
        	if (sizeof($a)==1) {
        		return $line['id'];
        	} else {
        		array_shift($a);
        		return GetAlBumByPath(implode("/",$a),$line['id']);
        	}

        } else return 0;
    }

	function ShowThumbs($aid) {
		global $aid0;
		global $com;
		global $db;
		global $com_p;
		global $al;
		if ($al[$aid]) {
			$album = $al[$aid]['titlefull'];
			$parent_id = $al[$aid]['parent_id'];
		}
		$r = $db->sql_query("SELECT * FROM photo_albums WHERE parent_id=$aid");
		if ($db->sql_affectedrows($r))
			while ($l = $db->sql_fetchrow($r))
				ShowThumbs($l['id']);
		$r = $db->sql_query("SELECT * FROM photo WHERE id_album=$aid ORDER BY dateadd");
		if ($db->sql_affectedrows($r)) {
		    //print "<br style=\"clear:both\" />";
			if ($aid0!=$aid) {
			    print "<br style=\"clear:both;\" />";
			    print "<br /><p>";
				if ($aid0!=$parent_id)
				print GetPathFromTo($aid0,$aid)."<br />";
				print "<h2><a class=\"hid\" href=\"/photo/".GetPath($db, $aid)."/\">$album</a></h2>";
				if ($al[$aid]['descr']) print "<div class=\"albumdescr\">".$al[$aid]['descr']."</div>";
				print "</p>";
			}
			print "<p>";

			$url = $al[$aid]['url'];
			print "<div class=\"thumbnails\">";
			if ($al[$aid]['picasa']) {
				while ($l = $db->sql_fetchrow($r)) {
			    	print "<div class=\"picasa0\"><div class=\"picasa\">";
	    			print "<a href=\"/photo/$url/-".$l['id']."\">";
	    			print "<img width=\"".$l["tn_width"]."\" height=\"".$l["tn_height"]."\" src=\"".$l['tn_fname']."\" />";
	    			print "</a>";
	    			print "</div>";
	    			print "</div>";
				}
			} else {
				while ($l = $db->sql_fetchrow($r)) {
	    			//print "<div class=\"thumb\" style=\"width: 18%;\">";
					print "<ins class=\"thumbnail\" style=\"width: 18%;\">";
	   				print "<div class=\"r\">";
		    		print "<div style=\"width: 150px; height: 150px; position: relative;\">";
	    			print "<a href=\"/photo/$url/-".$l['id']."\">";
	    			print "<img width=\"150\" height=\"150\" src=\"/photo/albums/$url/small/".$l['fname']."\" style=\"border: none\" />";
	    			print "</a>";
		    		$cn = $com_p[$l['id']]; // кол-во каментов к фото
		    		print $cn?"<a href=\"/photo/$url/-".$l['id']."#com\" title=\"$cn ".num_decline($cn,"комментарий","комментария","комментариев")."\"><div class=\"rbc\">$cn</div></a>":"";
	    			print "</div>";
	    			print "</div>";
		    		print "</ins>";
		   		}
			}
			print "</div>";
		}
	}


/*
    if (isset($_GET['album'])) {
    	$album=$_GET['album'];
		if ($album) {
			if ($album = GetAlbumByPath($album)) {
				$title = $album['titlefull'];
				$aid0 = $album['id'];
				$pathtxt = GetPathTxt($aid0);
			} else
				die();
		} else
			die();
    } else
    	die();
*/


    if (isset($_GET['album'])) {
    	$char_id=$_GET['album'];
		if ($char_id) {
			if ($album = $al[GetAlbumByPath($char_id)]) {
				$title = $album['titlefull'];
				$aid0 = $album['aid'];
				$pathtxt = GetPathTxt($aid0);
			} else
				die();
		} else
			die();
    } else
    	die();

	$meta_description = strip_tags($album['titlefull']." ".$album['descr']);
	$meta_keywords = "фотографии,".$title.",фрисби,алтимат,ultimate frisbee photo";
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/header.php";
?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="100%" style="padding-left: 15">
			<?
				$aid = $album['aid'];
			    $r = $db->sql_query("SELECT * FROM photo_albums WHERE id=$aid");
			    if ($db->sql_affectedrows($r)) {
					$l = $db->sql_fetchrow($r);
			    	$aid0=$aid;
			?>
				<p><?=$loveurl?>
				<br /><?=$pathtxt?>
				</p><p><h1><?=stripslashes($album['titlefull'])?></h1>
				<? if ($album['descr']) { ?>
				<div class="albumdescr"><p><?=$album['descr']?></p></div>
				<? } ?>
				</p>
		</td>
	</tr>
				<?
					$r = $db->sql_query("SELECT * FROM photo_albums WHERE parent_id=$aid");
					if ($db->sql_affectedrows($r)) {
				?>
	<tr style="background-color: #ededed;">
		<td style="padding-left: 15; padding-bottom: 20;">
				<?
					print "<p>Альбомы в&nbsp;&laquo;".stripslashes($l['title'])."&raquo;:</p><p>";
					ShowAlbums($aid);
					print "</p>";
				?>
		</td>
	</tr>
				<?
					}
				?>
	<tr>
		<td style="padding-left: 15">
				<?
				    ShowThumbs($aid);
			    } else
			    	die();
			?>
		</td>
	</tr>
</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>