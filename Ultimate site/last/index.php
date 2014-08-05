<?
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	if (isset($_REQUEST['period'])) $period = $_REQUEST['period']; else $period="day";

	$title = "Новое на сайте";
	$pg="last";
	if ($period == "day") {
		$date_end = time();
		$date_begin = $date_end-60*60*24;
		$title .= "";
	} elseif ($period == "week") {
		$date_end = time();
		$date_begin = $date_end-60*60*24*7;
		$title .= " за неделю";
		$pg="last/1";
	} elseif ($period == "month") {
		$date_end = time();
		$date_begin = $date_end-60*60*24*30;
		$title .= " за месяц, читер";
		$pg="last/1";
	} elseif ($period == "year") {
	    die("Doxyja vsego sly4ilos'");
		$date_end = time();
		$date_begin = $date_end-60*60*24*30*356;
		$title .= " за год, бля!";
		$pg="last/1";
	}

	include $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/header.php";

    function GetAlbum($id) {
    	if ($id) {
        $sql = "SELECT * FROM photo_albums WHERE id=".$id;
        $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
		$line = $db->sql_fetchrow($rst);
        if ($line['parent_id']==0) {
        	return stripslashes($line['title']);
        } else
        	return GetAlbum($line['parent_id'])." ".stripslashes($line['title']);
        } else return "no id";
    }


//	$date_begin -= 60*60*24*30;

?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr valign="top">
        <td style="padding: 15;">
        	<h1>Новое на сайте</h1>
        	<p>
        	<div class="menu">
       		<?
       			if ($period == "day") {
       				print "<span class=\"sel1\">за сутки</span>&nbsp;&nbsp;&nbsp;&nbsp;";
       			} else
       				print "<span class=\"sel0\"><a href=\"/last/day/\">за сутки</a></span>&nbsp;&nbsp;&nbsp;&nbsp;";

       			if ($period == "week") {
       				print "<span class=\"sel1\">за неделю</span>";
       			} else
       				print "<span class=\"sel0\"><a href=\"/last/week/\">за неделю</a></span>";
       		?>
        	</div>
        	</p>
		</td>
    </tr>
<?
	//include "hat.com.ua.php";
	//include "pvp.greens.by.php";
?>
    <tr valign="top">
    	<td>
    		<table cellspacing="0" cellpadding="15" border="0" width="100%">
    			<tr valign="top">
    			<?
    				$is_news = $is_forum = $is_blog = false;
    				$c1 = $c2 = $c3 = "";
    				$rst = $db->sql_query("SELECT * FROM blog
	                    LEFT JOIN phpbb_users ON id_forum_user=user_id
    					WHERE date>=$date_begin AND date<=$date_end AND active=1
    					ORDER BY o DESC");
    				if ($db->sql_affectedrows($rst)) {
    					//print "<td width=\"250\">";
    					$c1 .= "<h2>Блог</h2>";
    					while ($line = $db->sql_fetchrow($rst)) {
                            $c1 .= "<p><a href=\"/blog/".$line["id"]."/\">".stripslashes($line['title'])."</a></p><p>";
                            $c1 .= cut_long_string(stripslashes($line['doc']));
                            //$c1 .= "<a href=\"/blog/".$line["id"]."/\"><img src=\"/blog/img/".$line['photo_small']."\" hspace=\"5\" style=\"border: none\"/></a>";
                            $c1 .= "</p><br />";
                            //print "<div class=\"smalldate\">".$line['username']." ".make_human_date($line['date']) ."</div>";
    					}
    					$c1 .= "<p>&nbsp;</p><br />";
    					$is_blog=true;
    				}

    				$rst = $db->sql_query("SELECT * FROM news
	                    LEFT JOIN phpbb_users ON id_forum_user=user_id
    					WHERE date>=$date_begin AND date<=$date_end
    					ORDER BY date DESC");
    				if ($db->sql_affectedrows($rst)) {

				$c1 .= "<div id=\"news\" style=\"background-color: #f0f0ff; max-width: 500px; margin-left: -15; padding: 15;\">";
    					$c1 .= "<h3>Новости</h3>";
    					$c1 .= "<ul style=\"list-style-type: square;\">";
    					while ($line = $db->sql_fetchrow($rst)) {
                            $c1 .= "<li><p title=\"".$line['username']." ".make_human_date($line['date'])."\">".stripslashes($line['txt'])."</p></li>";
    					}
    					$c1 .= "</ul></div></div><br /><br /><br />";
    					//$c1 .= "<script>$('#news').corner();</script>";
    					$is_news=true;
    				}

	            	$sql = "SELECT forum_name, p.forum_id, t.topic_id, topic_title, post_time, user_id, username, user_avatar, user_avatar_type, post_text, p.post_id
	            		FROM phpbb_posts AS p
	                	LEFT JOIN phpbb_users AS u ON p.poster_id=u.user_id
	                	LEFT JOIN phpbb_topics AS t ON p.topic_id=t.topic_id
	                	LEFT JOIN phpbb_forums AS f ON p.forum_id=f.forum_id
	                    WHERE 0=0
	                    AND post_time>=$date_begin AND post_time<=$date_end
	                    ORDER BY post_time DESC";
	                $rst = $db->sql_query($sql) or die(mysql_error());
    				if ($db->sql_affectedrows($rst)) {
						$can_see = false;
    					$topics=array();
		                while ($line = $db->sql_fetchrow($rst)) {
		                	//$rst1=$db->sql_query("SELECT * FROM phpbb_forums WHERE forum_id = ".$line['forum_id']);
		                	//$line1=$db->sql_fetchrow($rst1);
							$is_auth0 = array();
							$is_auth0 = auth(AUTH_VIEW, $line['forum_id'], $user->data/*, $line1*/);
							if ($is_auth0['auth_view']) {
								$can_see = true;
								if (!isset($topics[$line['topic_id']])) {
									$topics[$line['topic_id']] = array(
									"title"=>"<a href=\"/4room/viewtopic.php?t=".$line['topic_id']."\">".$line['topic_title']."</a> &larr; <span class=\"small\" style=\"background-color: #f0f0f0; padding: 2px 4; margin: -2px -4;\"><a class=\"hid\" href=\"/4room/viewforum.php?f=".$line['forum_id']."\">".$line['forum_name']."</a></span>",
									"topic_id"=>$line['topic_id'],
									"messages"=>array());
								}
				       			$url = "/4room/profile.php?mode=viewprofile&u=".$line['user_id'];
			                	$author = "<a class=\"hid\" href=\"$url\"><b>" . $line['username'] . "</b></a>";
						    	$author_pic = "";
				            	if ($line['user_avatar_type']==1) {
							    	$path = "4room/images/avatars";
							    	$fname = $line['user_avatar'];
						    		$author_pic = "<div style=\"width: 30;\"><a href=\"$url\"><img style=\"border: none; vertical-align: top\" align=\"right\" src=\"".make_small_avatar($path,$fname,30,30)."\"  /></a></div>";
				            	} elseif ($line['user_avatar_type']==3) {
				       				list($path,$fname) = explode("/",$line['user_avatar']);
							    	$path = "4room/images/avatars/gallery/".$path;
						    		$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" align=\"right\" src=\"".make_small_avatar($path,$fname,30,30)."\" /></a>";
				       			} else {
				       				$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" align=\"right\" src=\"/img/tmp/30x30nophoto.gif\" /></a>";
				       			}
						    	$f_m = "<li><table cellspacing=\"0\" cellpadding=\"5\" border=\"0\">";
					            $f_m .= "<tr valign=\"top\">";
					            $f_m .= "<td width=\"30\"><p>$author_pic</p></td>";
					            $f_m .= "<td width=\"99%\"><p><div class=\"smalldate0\">";
					            $f_m .= $line['auth_view'] ? $lock_icon."&nbsp;&nbsp;" : "";
					            $f_m .= "$author ".make_human_date($line['post_time'])."</div>";
					            $f_m .= "<p style=\"padding-top: 5;\"><a class=\"hid\" href=\"/4room/viewtopic.php?p=".$line['post_id']."#".$line['post_id']."\">".cut_long_string($line['post_text'])."</a></p>";
			                    $f_m .= "</td></tr></table></li>";

			                    //array_push($topics[$line['topic_id']]["messages"],$f_m);
			                    array_push($topics[$line['topic_id']]["messages"],
			                    	array(
			                    		"view"=>$line['auth_view'],
			                    		"pic" => $author_pic,
			                    		"author" => $author,
			                    		"date" => make_human_date($line['post_time']),
			                    		"txt" => "<a class=\"hid\" href=\"/4room/viewtopic.php?p=".$line['post_id']."#".$line['post_id']."\">".cut_long_string($line['post_text'])."</a>"
			                    	)
			                    );
			                    $topics[$line['topic_id']]["view"]=$line['auth_view'];
			           		}
		                }
		                $is_forum = true;
    				}

    				if ($can_see) {
    					//if (!$is_news&&!$is_blog) print "<td width=\"250\">";
    					$c1 .= "<h3>На форуме</h3>";
    					foreach ($topics as $t) {
    						$c1 .= "<br /><div".($t["view"]?" style=\"background-color: beige\"":"")."><p>".$t["title"]."<ul id=\"list_topic_".$t["topic_id"]."\">";
    						$i=2;
    						foreach ($t["messages"] as $f)
    							if ($i--) {
							    	$c1 .= "<li><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
						            $c1 .= "<tr valign=\"top\">";
						            $c1 .= "<td width=\"30\"><p>".$f["pic"]."</p></td>";
					            	$c1 .= "<td width=\"99%\" style=\"padding-left: 15px;\"><p><div class=\"smalldate0\">";
					            	//$c1 .= $f["view"] ? $lock_icon."&nbsp;&nbsp;" : "";
					            	$c1 .= $f['author']." ".$f['date']."</div>";
					            	$c1 .= "<p style=\"padding-top: 5\">".$f['txt']."</p>";
						            //$c1 .= "<td width=\"99%\" style=\"padding-left: 15;\"><p>".$f["author"]." ".$f["txt"]."";
						            //$c1 .= " <span class=\"smalldate0\">".$f["date"]."</span></p>";
				                    $c1 .= "</td></tr></table></li>";
	    						}
	    						else {
	    							$rest = sizeof($t["messages"])-2;
	    							$c1 .= "<li id=\"get_topic_li_".$t["topic_id"]."\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	    							$c1 .= "<tr valign=\"top\"><td width=\"30\"><p>&nbsp;</p></td><td style=\"padding-left: 15;\">";
	    							$c1 .= "<p><div class=\"smalldate0\" style=\"line-height: 16px;\"><a id=\"get_topic_".$t["topic_id"]."\" style=\"cursor: hand;\" class=\"pseudo_link clickable\">+ еще $rest ".num_decline($rest,"сообщение","сообщения","сообщений")."</a>&nbsp;&nbsp;<span id=\"preloader_".$t["topic_id"]."\"></span></div></p>";
	    							$c1 .= "</td><tr></table></li>";
	    							$c1 .= "\n<script language=\"javascript\">";
									$c1 .= "\n$('#get_topic_".$t["topic_id"]."').click(function() {";
									$c1 .= "\n$('#preloader_".$t["topic_id"]."').html('<img src=\"/img/ajax-loader.gif\" alt=\"wait\" style=\"vertical-align: middle;\" />');";
									$c1 .= "\n$.post(\"/tmpl/get_topic.php\", { topic_id: ".$t["topic_id"].", date_begin: $date_begin },";
									$c1 .= "\nfunction(data){";
									$c1 .= "\n$('#get_topic_li_".$t["topic_id"]."').remove();";
									$c1 .= "\n$('#list_topic_".$t["topic_id"]."').append(data);";
									$c1 .= "\n$('#preloader_".$t["topic_id"]."').html('');";
									$c1 .= "\nreturn true; }); return true; }); </script>";
	    							break;
	    						}
    						$c1 .= "</ul><br /></div>";
    					}
    				}

    				$rst = $db->sql_query("SELECT * FROM comments
    					WHERE dat>=$date_begin AND dat<=$date_end
    					AND id_category IN (".MY_NEWS.",".MY_PHOTO.",".MY_PLAYERS.")
    					ORDER BY dat DESC");
    				if ($db->sql_affectedrows($rst)) {
    					$com = array();
    					while ($line = $db->sql_fetchrow($rst)) {
    						if (!$com[$line['id_item']])
    							$com[$line['id_item']] = array();
    						array_push($com[$line['id_item']], $line);
    					}

    					$c2 .= "<h3>Комментарии</h3>";
    					$c2 .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
    					foreach ($com as $k=>$v) {
							switch ($v[0]["id_category"]) {
							    case MY_NEWS:
									$c2 .= "<tr valign=\"top\">";
							    	$rst1 = $db->sql_query("SELECT * FROM blog WHERE id=".$k);
							    	$line1 = $db->sql_fetchrow($rst1 );
							    	$path = "blog/img";
							    	$fname = $line1['Photo'];
							    	$url = "/blog/".$line1['id']."/#com";
							    	$c2 .= "<td width=\"50\" align=\"center\">";
							    	$c2 .= "<br /><p><a href=\"$url\"><img style=\"border: none\" src=\"".make_small_avatar($path,$fname)."\" /></a>";
		    						$c2 .= "</td><td style=\"padding-left: 15;\"><br />";
		    						foreach ($v as $c) {
										$c2 .= "<p><div class=\"smalldate0\"><b>".GetUserProfile($c['id_forum_user'],$c['author'])."</b> ".make_human_date($c['dat'])."</div>";
										$c2 .= "<p style=\"padding-top: 5;\"><a class=\"hid\" href=\"/blog/".$line1['id']."/#com\">".cut_long_string($c['txt'])."</a></p>";
		    						}
		    						$c2 .= "</td></tr>";
							        break;
							    case MY_PHOTO:
		    						$c2 .= "<tr valign=\"top\">";
							    	$rst1 = $db->sql_query("SELECT * FROM photo WHERE id=".$k);
							    	$line1 = $db->sql_fetchrow($rst1);
								    $url = "/photo/".GetPath($db, $line1['id_album'])."/-".$line1['id']."";
							    	$fname = $line1['fname'];
		    						$picasa = false;
		    						if ($db->sql_affectedrows($db->sql_query("SELECT picasa_user FROM photo_albums WHERE id=".$line1['id_album']." AND picasa_user<>''")))
		    							$picasa = true;
							    	if (!$picasa) {
								    	$path = "photo/albums/".GetPath($db, $line1['id_album'])."/small";
								    	$c2 .= "<td width=\"50\" align=\"center\">";
								    	$c2 .= "<br /><p>";
										$c2 .= "<a href=\"$url#o0\"><img style=\"border: 1px solid #aabbcc; padding: 3;\" src=\"".make_small_avatar($path,$fname)."\" /></a>";
									} else {
								    	$c2 .= "<td width=\"50\" align=\"center\">";
								    	$c2 .= "<br /><p>";
										$c2 .= "<a href=\"$url#o0\"><img style=\"border: 1px solid #aabbcc; padding: 3;\" src=\"".str_replace("/s144/","/s72/",$line1['tn_fname'])."\" /></a>";
									}
		    						$c2 .= "</td><td style=\"padding-left: 15;\"><br />";
		    						foreach ($v as $c) {
										$c2 .= "<p><div class=\"smalldate0\"><b>".GetUserProfile($c['id_forum_user'],$c['author'])."</b> ".make_human_date($c['dat'])."</div>";
										$c2 .= "<div style=\"overflow:hidden;\"><p style=\"padding-top: 5;\"><a class=\"hid\" href=\"$url#com\">".cut_long_string($c['txt'])."</a></p></div>";
		    						}
		    						$c2 .= "</td></tr>";
							        break;
							    case MY_PLAYERS:
		    						$c2 .= "<tr valign=\"top\">";
							    	$rst1 = $db->sql_query("SELECT * FROM players WHERE id=".$k);
							    	$line1 = $db->sql_fetchrow($rst1);
							    	$path = "teams/photo";
							    	$fname = $line1['Photo']?$line1['Photo']:"nophoto_small.gif";
							    	$url = "/players/".($line1['p_char_id']?$line1['p_char_id']:$line1['id'])."/";
							    	$c2 .= "<td width=\"50\" align=\"center\">";
							    	$c2 .= "<br /><p>";
							    	$c2 .= "<a href=\"$url\"><img style=\"border: none\" src=\"".make_small_avatar($path,$fname)."\" /></a>";
							    	$c2 .= "<div class=\"small\" style=\"text-align: center; padding-top: 5;\"><span class=\"sex".Sex($line1['id'])."\"><a href=\"$url\">".str_replace(" ","<br />",NameSurname($line1['id']))."</span></a></div></p>";
		    						$c2 .= "</td><td style=\"padding-left: 15;\"><br />";
									//$c2 .= "<p>".NameSurname($line1['id'],true)."</p>";
		    						foreach ($v as $c) {
										$c2 .= "<p><div class=\"smalldate0\"><b>".GetUserProfile($c['id_forum_user'],$c['author'])."</b> ".make_human_date($c['dat'])."</div>";
										$c2 .= "<p style=\"padding-top: 5\"><a class=\"hid\" href=\"$url#com\">".cut_long_string($c['txt'])."</a></p>";
		    						}
		    						$c2 .= "</td></tr>";
							        break;
							}
    					}
		    			$c2 .= "</table>";
    				}

    				$rst = $db->sql_query("SELECT p.*,a.titleshort,picasa_user FROM photo p
					LEFT JOIN photo_albums a ON p.id_album=a.id
    					WHERE p.dateadd>=$date_begin AND p.dateadd<=$date_end
    					ORDER BY p.dateadd DESC");
    				if ($db->sql_affectedrows($rst)) {
    				//if (false) {
    					$dateadd = '';
    					$albums = array();
    					$photos = array();
    					$photos_on_page=7;
    					$num_photos = $db->sql_affectedrows($rst);
    					while ($line=$db->sql_fetchrow($rst)) {

					    	$url = GetPath($db, $line['id_album']);
					    	$picasa = ($line['picasa_user']!='');

					    	if (!$picasa) {
								$ph = "<ins class=\"thumbnail\" style=\"width: 172px;\"><div class=\"r\"><a href=\"/photo/$url/-".$line['id']."#o0\">";
    							$ph .= "<img src=\"/photo/albums/$url/small/".$line['fname']."\" style=\"border: none;\" /></a></div></ins>";
					    		array_push($photos,$ph);
    						} else {
						    	$ph = "<div class=\"picasa0\"><div class=\"picasa\">";
				    			$ph .= "<a href=\"/photo/$url/-".$line['id']."\">";
				    			$ph .= "<img width=\"".$line["tn_width"]."\" height=\"".$line["tn_height"]."\" src=\"".$line['tn_fname']."\" />";
				    			$ph .= "</a>";
				    			$ph .= "</div>";
				    			$ph .= "</div>";
					    		array_push($photos,$ph);
    						}

    						if (!isset($albums[$line['id_album']])) {
						    $albums[$line['id_album']] = array(
    								"url"=>"<a href=\"/photo/$url/\">".($line['titleshort']?stripslashes($line['titleshort']):GetAlbum($line['id_album']))."</a>",
    								"num"=>0);
    						}
    						$albums[$line['id_album']]["num"]++;
    					}
    					$c3 .= "<h3>$num_photos ".num_decline($num_photos,"новая фотография","новые фотографии","новых фотографий")."</h3>";
    					$c3 .= "<p>в&nbsp;";
    					if (sizeof($albums)==1) {
    						$al = array_pop($albums);
    						$c3 .= "альбоме ".$al["url"];
    					} else {
    						$c3 .= "альбомах:";
    						foreach ($albums as $k=>$v) $c3 .= "<br />" . $v["url"]." (+".$v["num"].")";
    					}
    					//$c3 .= "</ul>";
    					$c3 .= "<p><div class=\"thumbnails\">";
    					$cnt = min(sizeof($photos),$photos_on_page);
						for ($i=0; $i<$cnt; $i++) {
							list($usec, $sec) = explode(' ', microtime());
							srand((float) $sec + ((float) $usec * 100000));
							$k = rand(0, sizeof($photos)-1);
							$j=0;
							foreach ($photos as $key=>$value) {
								if ($j==$k) {
									$key1 = $key;
									break;
								}
								$j++;
							}
							$c3 .= $photos[$key];
							unset($photos[$key1]);
						}
    					$c3 .= "</div><br style=\"clear: both;\" /><br />";
    				}



    				$r = $db->sql_query("SELECT * FROM video
    					WHERE dateadded>=$date_begin AND dateadded<=$date_end
    					ORDER BY dateadded DESC");
    				$num_videos = $db->sql_affectedrows($r);
    				if ($num_videos) {
    					$c3 .= "<h3>$num_videos ".num_decline($num_videos,"новое видео","новых видео","новых видео")."</h3>";
    					while ($l = $db->sql_fetchrow($r)) {
		    				$video_url = "<a href=\"/video/".$l["id"]."/\">".stripslashes($l["title"])."</a>";
    						$video_desc = $l["description"] ? "<div class=\"small\">".stripslashes($l["description"])."</div>":"";
    						$video_code = stripslashes($l["youtubecode"]);
    						$c3 .= "<p>$video_url$video_desc<p>$video_code</p>";
    					}
    					$c3 .= "<br /><br /><br />";
    				}




					$sql = "SELECT c.city, c.char_id, p.dat, p.id_place AS pid, p.place AS place1, p.comment AS comment1, pp.place AS place2, pp.comment AS comment2
						FROM practice AS p
						LEFT JOIN practice_places AS pp ON p.id_place=pp.id
						LEFT JOIN cities AS c ON p.id_city=c.id
						WHERE p.dat>".(time()-2*60*60)/*mktime(0,0,1,date("m"),date("d"),date("Y"))*/."
						ORDER BY c.id, dat ASC";
					$rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
					if ($db->sql_affectedrows($rst)) {
					//if (false) {
						$c4 = "<a id=\"pick-up\"></a><div id=\"pickup\"><h3>Открытые игры</h3>";
                       	$city = "";
                           while ($line = $db->sql_fetchrow($rst)) {
                           	if ($city != $line['city']) {
                           		if ($city) $c4 .= "<br />";
                           		$city = $line['city'];
                           		$c4 .= "<p><a href=\"/practice/".$line['char_id']."/\">$city</a>";
                           		//print "<p>$city</p>";
                           	}
                          	$c4 .= "<div style=\"padding-left: 15;\">";
                           	$c4 .= "<p>";
                           	$d = $line['dat'];
                            if ( date("dmY") == date("dmY",$d))
                            	$c4 .= "<span style=\"color:red\"><strong>Сегодня</strong></span>";
                            elseif (date("dmY") == date("dmY",$d-60*60*24))
                            	$c4 .= "<strong>Завтра</strong>";
                            else
                            	$c4 .= "<strong>".$weekday[date("w",$d)]."</strong>";
                            $c4.= "<br />".sprintf("%2d",date("d",$d))." ".$month[date("m",$d)].", ".date("H:i",$d);
                            if ($line['pid']) {
                            	$place = $line['place2'];
                                $comment = $line['comment2'];
                            } else {
                            	$place = $line['place1'];
                                $comment = $line['comment1'];
                            }
                            $c4 .= "<br />" . stripslashes($place) . "<br />" . stripslashes($comment) ."";
                            $c4 .= "</div>";
                        }
                        $c4 .= "</div>";
                   }

				 //include "hat.com.ua.php";
        		 //include "175g.ru.php";
				//$c3 = "<p><a href=\"/links/?go=http%3A%2F%2Fwww.worldgames2009.tw%2Fwg2009%2Feng%2Fsports.php%3Fsn%3D31\"><img src=\"/upload/world-games-flying-disc.gif\" style=\"border: none;\" alt=\"Алтимат на World Games 2009\" title=\"Алтимат на World Games 2009\" /><br />Алтимат на World Games 2009</a></p><br /><br />" . $c3;

				if ($c2) $c2 .= "<br /><br />";
				if ($c3) $c3 .= $c4; else $c2 .= $c4;
    				$c_num = ($c1?1:0)+($c2?1:0)+($c3?1:0);
    				if ($c_num>0)
    					$w = round(100/$c_num);
    				if ($c1)
    					print "<td width=\"".($w+5)."%\" style=\"background-color: #fff\">$c1</td>";
    				if ($c2)
    					print "<td width=\"".($w+5)."%\">$c2</td>";
    				if ($c3)
    					print "<td width=\"".($w-10)."%\">$c3</td>";
    			?>
			    <? /*<td><span style="font-size: 40px; color: silver">;-)</span><p><img src="/upload/ipod.jpg" alt=";-)" /></p></td>*/ ?>
    			</tr>
    		</table>
    	</td>
    </tr>
</table>

<?
	include "../tmpl/footer.php";
?>
