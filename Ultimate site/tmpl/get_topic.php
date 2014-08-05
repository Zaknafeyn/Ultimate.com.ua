<?
	if (isset($_POST['topic_id'])) {
		include_once $path_site."/tmpl/init.php";
		$topic_id = digits_only($_POST['topic_id']);
		$date_begin = digits_only($_POST['date_begin']);




		$sql = "SELECT f.forum_id, post_time, user_id, username, user_avatar, user_avatar_type, post_text, p.post_id, f.auth_view
			FROM phpbb_posts AS p
			LEFT JOIN phpbb_posts_text AS pt ON p.post_id=pt.post_id
			LEFT JOIN phpbb_users AS u ON p.poster_id=u.user_id
			LEFT JOIN phpbb_forums AS f ON p.forum_id=f.forum_id
			WHERE 0=0
			AND p.topic_id=$topic_id
			AND post_time>=$date_begin
			ORDER BY post_time DESC";
			$can_see = false;
			$rst = $db->sql_query($sql);
			$f_m = "";
			$skip = 2;
			while ($line = $db->sql_fetchrow($rst)) {
				$is_auth0 = array();
				$is_auth0 = auth(AUTH_VIEW, $line['forum_id'], $user->data/*, $line1*/);
				if ($is_auth0['auth_view'] && ($skip--<=0)) {
					$can_see = true;
					$url = "/".$forum_folder_name."/profile.php?mode=viewprofile&u=".$line['user_id'];
					$author = "<a class=\"hid\" href=\"$url\"><b>" . iconv("WINDOWS-1251","UTF-8",$line['username']) . "</b></a>";
					$author_pic = "";
					if ($line['user_avatar_type']==1) {
						$path = $forum_folder_name."/images/avatars";
						$fname = $line['user_avatar'];
						$author_pic = "<div style=\"width: 30;\"><a href=\"$url\"><img style=\"border: none; vertical-align: top\" align=\"right\" src=\"".make_small_avatar($path,$fname,30,30)."\"  /></a></div>";
					} elseif ($line['user_avatar_type']==3) {
						list($path,$fname) = explode("/",$line['user_avatar']);
						$path = $forum_folder_name."/images/avatars/gallery/".$path;
						$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" align=\"right\" src=\"".make_small_avatar($path,$fname,30,30)."\" /></a>";
					} else {
						$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" align=\"right\" src=\"/img/tmp/30x30nophoto.gif\" /></a>";
					}
			    	$f_m .= "<li><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
		            $f_m .= "<tr valign=\"top\"".($line['auth_view']>0?" style=\"background-color: beige;\"":"").">";
		            $f_m .= "<td width=\"30\"><p>$author_pic</p></td>";
		            $f_m .= "<td width=\"99%\" style=\"padding-left: 15;\"><p><div class=\"smalldate0\">$author ".iconv("WINDOWS-1251","UTF-8",make_human_date($line['post_time']))."</div>";
		            $f_m .= "<p style=\"padding-top: 5;\"><a class=\"hid\" href=\"/".$forum_folder_name."/viewtopic.php?p=".$line['post_id']."#".$line['post_id']."\">".iconv("WINDOWS-1251","UTF-8",cut_long_string($line['post_text']))."</a></p>";
	             	$f_m .= "</td></tr></table></li>";
	           	}
	         }
		echo $f_m;
	}
?>
