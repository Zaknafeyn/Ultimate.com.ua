<?
   	$p_char_id = preg_replace("/[^0-9a-z\-_\.]/i","",strtolower($_REQUEST['u']));
   	$r = $db->sql_query("SELECT id FROM players WHERE p_char_id='$p_char_id'");
   	if ($db->sql_affectedrows($r)) {
   		$l = $db->sql_fetchrow($r);
   		$pid = $l['id'];
   	} else {
   		$pid = digits_only($p_char_id);
   		if ($pid) {
	    	$r = $db->sql_query("SELECT p_char_id FROM players WHERE id=$pid");
	    	if ($db->sql_affectedrows($r)) {
	    		$l = $db->sql_fetchrow($r);
	    		if ($l['p_char_id']) {
		    		header("location:/players/".$l['p_char_id']."/");
		    		exit;
				}
	    	}
   		}
   	}
   	if ($pid=='') $pid=0;
	//$pid = digits_only($_GET['u']);
	$title="Фотографии, на которых встречается";
	/*
	$r = $db->sql_query("SELECT * FROM players WHERE id=$pid");
	if ($db->sql_affectedrows($r)) {
		$l = $db->sql_fetchrow($r);
		if (!$l['id_sex']) $title .= "а";
		$name = "<span class=\"sex".$l['id_sex']."\">".NameSurname($pid,true)."</span>";
	} else {
		$r = $db->sql_query("SELECT * FROM phpbb_users WHERE user_id=$pid");
		if ($db->sql_affectedrows($r)) {
			$l = $db->sql_fetchrow($r);
			$url="/4room/profile.php?mode=viewprofile&u=$pid";
			$name = "<a href=\"$url\">".$l['username']."</a>";
		}
	}
	*/
	$name = NameSurname($pid,true);
	$titl = $title." ".$name;;
	$title = strip_tags($titl);
	$meta_description = $title;
	$meta_keywords = "фото,".strip_tags($name);
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>

<table width="100%" cellspacing="0" cellpadding="15" border="0">
    <tr valign="top">
		<td>
			<h1><?=$titl?></h1>
			<p>
			<?
				$r = $db->sql_query("SELECT * FROM photo_marks m
					LEFT JOIN photo p ON p.id=m.photo_id
					LEFT JOIN photo_albums a ON p.id_album=a.id
					WHERE user_id=$pid
					ORDER BY approved, photo_id");
				if ($db->sql_affectedrows($r)) {
					$foto = array();
					$newfoto = array();
					while ($l = $db->sql_fetchrow($r)) {
						$url = $al[$l['id_album']]['url'];
						if ($al[$l['id_album']]['picasa']) {
					    	$ph = "<div class=\"picasa0\"><div class=\"picasa\">";
			    			$ph .="<a href=\"/photo/$url/-".$l['photo_id']."\">";
			    			$ph .="<img width=\"".$l["tn_width"]."\" height=\"".$l["tn_height"]."\" src=\"".$l['tn_fname']."\" />";
			    			$ph .="</a>";
			    			$ph .="</div>";
			    			$ph .="</div>";
						} else {
	    					$ph = "<ins class=\"thumbnail\" style=\"width: 18%;\">";
	        				$ph .="<div class=\"r\">";
				    		$ph .="<a href=\"/photo/$url/-".$l['photo_id']."#o0\">";
				    		$ph .="<img width=\"150\" height=\"150\" src=\"/photo/albums/$url/small/".$l['fname']."\" style=\"border: none\" />";
				    		$ph .="</a></div></ins>";
						}
						if ((!$l['approved']) && ($user->data['user_id']==$pid))
							array_push($newfoto,$ph);
						else
							array_push($foto,$ph);
					}
					if (sizeof($newfoto)) {
						print "<p>Новые фотографии, на которых тебя отметили:</p><p>";
						print "<div class=\"thumbnails\">";
						foreach($newfoto as $f) print $f;
						print "</div></p><br style=\"clear: both\" /><hr size=\"0\" /><p><br /><p>";
					}
					print "<p><div class=\"thumbnails\">";
					foreach($foto as $f) print $f;
					print "</div></p>";
				}
			?>
			</p>
		</td>
	</tr>
</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>