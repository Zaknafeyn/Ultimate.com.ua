<?
include_once $path_site . "/tmpl/config.php";
include_once $path_site . "/tmpl/common_functions.php";

// надо бы в config:
$picasa_favicon = "<img src=\"/upload/picasaweb-favicon.ico\" style=\"vertical-align: middle;\" height=\"16\" width=\"16\" />";
$max_team_foto = 21;

function secure_email($email) {
	return preg_replace ( "/@/", "@<span style=\"display: none;\">%удали_меня%</span>", $email );
}

function my_create_date($d1, $d2, $y = 1) {
	global $month;
	$day1 = trim ( sprintf ( "%2d", substr ( $d1, 6, 2 ) ) );
	$mon1 = substr ( $d1, 4, 2 );
	$y1 = substr ( $d1, 0, 4 );
	$day2 = trim ( sprintf ( "%2d", substr ( $d2, 6, 2 ) ) );
	$mon2 = substr ( $d2, 4, 2 );
	$y2 = substr ( $d2, 0, 4 );
	if ($mon1 == $mon2) {
		return ($day1 == $day2 ? $day1 : $day1 . "-" . $day2) . " " . $month [$mon1] . ($y ? " " . $y1 : "");
	} else {
		if ($y1 == $y2) {
			return $day1 . " " . $month [$mon1] . "-" . $day2 . " " . $month [$mon2] . ($y ? " " . $y1 : "");
		} else {
			return $day1 . " " . $month [$mon1] . " $y1 - " . $day2 . " " . $month [$mon2] . " $y2";
		}
	}
}

function make_human_date($d, $show_time = 1) {
	global $month;
	if (date ( "dmY" ) == date ( "dmY", $d ))
		$dd = "сегодня";
	elseif (date ( "dmY" ) == date ( "dmY", $d + 60 * 60 * 24 ))
		$dd = "вчера";
	else
		$dd = sprintf ( "%2d", date ( "d", $d ) ) . " " . $month [date ( "m", $d )];
	if ($d + 60 * 60 * 24 * 100 < time ())
		$dd .= " " . date ( "Y", $d );
	if ($show_time)
		$dd .= " в&nbsp;" . date ( "H:i", $d );
	return $dd;
}

function mycut($s) {
	$search = array ("/&/", "/</", "/>/" );
	$replace = array ("&amp;", "&lt;", "&gt;" );
	$s = preg_replace ( $search, $replace, $s );
	return $s;
}

function save_comment($cat, $id_item, $author, $email, $comment, $ip, $i1, $i2, $ar) {
	global $user;
	
	$can_post = true;
	
	if ($user->data['user_id'] != ANONYMOUS) {
		$id_forum_user = $user->data ['user_id'];
		$author = $user->data ['username'];
		$email = "";
	} else {
		$id_forum_user = 0;
		$author = mysql_escape_string ( $author );
		$email = mysql_escape_string ( $email );
		if (($i1 + $i2) != $ar)
			$can_post = false;
	}
	
	if ($can_post) {
		
		$comment = trim ( preg_replace ( "/<[\/\!]*?[^<>]*?>/si", "", $comment ) );
		$comment = preg_replace ( array ("/\[(\/?(b|i|u|a))\]/i", "/((ht|f)+tp:\/\/[^\s]*)(\s|$|\?)/" ), array ("<\\1>", " <a href=\"\\1\">\\1</a> " ), $comment );
		
		if ($comment) {
			
			$sql = "INSERT INTO comments
			    	(id_category,id_item,id_forum_user,author,email,dat,txt,ip)
			    	VALUES($cat,$id_item,$id_forum_user,'$author','$email'," . time () . ",'$comment','$ip')";
			$rst = $db->sql_query ( $sql ) or die ( "<p>sql=$sql<p>" . mysql_error () );
			
			return $comment;
		}
	}
	
	return false;
}

function get_comments($cat, $id_item, $title = '', $order = 'ASC') {
	global $db;
	$com = "<p><a id=\"com\"></a></p>";
	$com .= "<table id=\"comments_comments\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	$sql = "SELECT t1.id_forum_user, t1.author, t1.email, t1.dat, t1.txt, t3.username
	    	FROM comments t1
	    	LEFT JOIN phpbb_users t3 ON t1.id_forum_user=t3.user_id
	    	WHERE id_category=$cat AND id_item=$id_item
	    	ORDER BY dat $order";

	$rst = $db->sql_query ( $sql ) or die ( $sql . "<p>" . mysql_error () );
	if ($db->sql_affectedrows ( $rst )) {
		$com .= $title ? "<tr><td colspan=\"2\"><h4>$title</h4></td></tr>" : "";
		while ( $line = $db->sql_fetchrow ( $rst, MYSQL_ASSOC ) ) {
			$author = ($line ['author']) ? "<b>$line[author]</b>" : "";
			$author = "<b>" . GetUserProfile ( $line ['id_forum_user'], $line ['author'] ) . "</b>";
			$author_pic = GetUserAvatar ($db,  $line ['id_forum_user'] );
			$date = make_human_date ( $line ['dat'] );
			$txt = preg_replace ( "/$/m", "<br />", stripslashes ( $line ['txt'] ) );
			$com .= "<tr valign=\"top\">";
			$com .= "<td width=\"30\"><p>$author_pic</p><br /></td>";
			$com .= "<td width=\"100%\" style=\"padding-left: 15;\"><p><div class=\"smalldate0\">$author $date</div>";
			$com .= "<p style=\"padding-top: 5\">$txt</p><br /></td></tr>";
		}
	}
	$com .= "</table>";
	return $com;
}

function get_comments_form($cat, $itm) {
	global $user;
	$com = "";
	if ($user->data['user_id'] == ANONYMOUS) {
		$com .= "<p><div class=\"small\"><a id=\"addcom\" class=\"pseudo_link clickable\">Добавить комментарий</a></div></p>";
		$com .= "<div id=\"DivCommentsForm\" style=\"display: none;\">";
	}
	//$com .= "<form name=\"comment_form\" method=\"post\" action=\"$action\">";
	$com .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	$com .= "<tr>" . (($user->data['user_id'] == ANONYMOUS) ? "<td width=\"30\" valign=\"top\" style=\"padding-right: 15;\"><p>" . $user->data ['user_photo'] . "</p></td>" : "") . "<td>";
	if ($user->data['user_id'] == ANONYMOUS) {
		$com .= "<p><input id=\"comment_author\" name=\"comment_author\" value=\"Ваше имя\" style=\"width:250px;\" onfocus=\"if (this.value=='Ваше имя') this.value='';\"  onblur=\"if (this.value=='') this.value='Ваше имя';\" ></p>";
	}
	$com .= "<p><textarea rows=\"5\" id=\"comment_text\" name=\"comment_text\" style=\"width:450px;\" onfocus=\"if (this.value=='Ваш комментарий') this.value='';\"  onblur=\"if (this.value=='') this.value='Ваш комментарий';\" onKeyPress='if (event.keyCode==10 || (event.ctrlKey && event.keyCode==13)) comment_ok.click();'>Ваш комментарий</textarea></p>";
	$com .= "<p><button id=\"comment_ok\" type=\"submit\">OK (Ctrl+Enter)</button>&nbsp;&nbsp;<span id=\"comment_preloader\"></span>";
	$com .= "<input id=\"comment_cat\" name=\"comment_cat\" type=\"hidden\" value=\"$cat\" /><input id=\"comment_itm\" name=\"comment_itm\" type=\"hidden\" value=\"$itm\" /><input id=\"ip\" name=\"ip\" type=\"hidden\" value=\"" . getIP () . "\" />";
	$com .= "</p></td></tr></table>";
	//$com .= "</form>";
	if ($user->data['user_id'] == ANONYMOUS) {
		$com .= "</div>";
	}
	return $com;
}

function save_tags($id_category, $id_item, $tags) {
	$db->sql_query ( "DELETE FROM tags WHERE id_category=$id_category AND id_item=$id_item" );
	if (trim ( $tags )) {
		$tt = explode ( ",", $tags );
		foreach ( $tt as $t ) {
			$t = trim ( $t );
			$rst = $db->sql_query ( "SELECT * FROM tag WHERE tag='$t'" );
			if (! $db->sql_affectedrows ( $rst )) {
				$db->sql_query ( "INSERT INTO tag (tag) VALUES ('$t')" );
				$rst = $db->sql_query ( "SELECT * FROM tag WHERE tag='" . strtolower ( $t ) . "'" );
			}
			$line = $db->sql_fetchrow ( $rst );
			$id_tag = $line ['id'];
			if (! $db->sql_affectedrows ( $db->sql_query ( "SELECT * FROM tags WHERE id_category=$id_category AND id_item=$id_item AND id_tag=$id_tag" ) ))
				$db->sql_query ( "INSERT INTO tags (id_category, id_item, id_tag) VALUES ($id_category, $id_item, $id_tag)" );
		}
	}
}

function get_tags($id_category, $id_item, $url = 0) {
	/*
    	$rst = $db->sql_query("SELECT * FROM tags AS ts LEFT JOIN tag AS t ON ts.id_tag=t.id WHERE id_category=$id_category AND id_item=$id_item");
		$tags = "";
    	if ($db->sql_affectedrows($rst))
    		while ($line = $db->sql_fetchrow($rst)) {
    			if ($tags) $tags .= ", ";
    			if ($url)
    				$tags .= "<a class=\"hid\" href=\"/tag/?tag=".$line['tag']."\">".$line['tag']."</a>";
    			else
    				$tags .= $line['tag'];
    		}*/
	return "";
	//return $tags;
}

function GetPath($db, $id, $tbl = "photo_albums") {
	$sql = "SELECT * FROM $tbl WHERE id=$id";
	$rst = $db->sql_query ( $sql ) or die ( "<p>$sql<p>" . mysql_error () );
	$line = $db->sql_fetchrow ( $rst );
	if ($line ['parent_id'] == 0) {
		return $line ['char_id'];
	} else
		return GetPath ($db, $line ['parent_id'], $tbl ) . "/" . $line ['char_id'];
}

function GetIDbyPath($db, $char_id, $parent_id, $tbl = "photo_albums") {
	$a = explode ( "/", $char_id );
	$sql = "SELECT * FROM $tbl WHERE char_id='" . $a [0] . "' AND parent_id=$parent_id";
	$rst = $db->sql_query ( $sql );// or die ( "<p>$sql<p>" . mysql_error () );
	if ($db->sql_affectedrows ( $rst )) {
		$line = $db->sql_fetchrow ( $rst );
		if (sizeof ( $a ) == 1)
			return $line ['id'];
		else {
			array_shift ( $a );
			return GetIDbyPath ($db, implode ( "/", $a ), $line ['id'], $tbl );
		}
	
	} else
		return 0;
}

function GetFirstLeveID($db, $id, $tbl = "manual") {
	$sql = "SELECT * FROM $tbl WHERE id=$id";
	$rst = $db->sql_query ( $sql );// or die ( "<p>$sql<p>" . mysql_error () );
	$line = $db->sql_fetchrow ( $rst );	
	if (! $line ['parent_id'])
		return $line ['id'];
	else
		return GetFirstLeveID ($db, $line ['parent_id'] );
}

function imageresize($outfile, $infile, $neww, $newh, $quality) {
	if (preg_match ( '/\.(jpg|jpeg)$/i', $infile )) {
		$im = imagecreatefromjpeg ( $infile );
	} elseif (preg_match ( '/\.(gif)$/i', $infile )) {
		$im = imagecreatefromgif ( $infile );
	} elseif (preg_match ( '/\.(png)$/i', $infile )) {
		$im = imagecreatefrompng ( $infile );
	}
	$k1 = ($neww > imagesx ( $im )) ? 1 : ($neww / imagesx ( $im ));
	$k2 = $newh / imagesy ( $im );
	$k = $k1 > $k2 ? $k2 : $k1;
	
	$w0 = imagesx ( $im );
	$h0 = imagesy ( $im );
	
	$w = intval ( imagesx ( $im ) * $k );
	$h = intval ( imagesy ( $im ) * $k );
	
	$im1 = imagecreatetruecolor ( $w, $h );
	imagecopyresampled ( $im1, $im, 0, 0, 0, 0, $w, $h, imagesx ( $im ), imagesy ( $im ) );
	
	imagejpeg ( $im1, $outfile, $quality );
	imagedestroy ( $im );
	imagedestroy ( $im1 );
	return array ("w0" => $w0, "h0" => $h0, "w" => $w, "h" => $h );
}
     /*
function makeIcons_MergeCenter($src, $dst, $dstx, $dsty, $q = 75) {
	
	//$src = original image location
	//$dst = destination image location
	//$dstx = user defined width of image
	//$dsty = user defined height of image
	

	$allowedExtensions = 'jpg jpeg gif png';
	
	$name = explode ( ".", $src );
	$currentExtensions = $name [count ( $name ) - 1];
	$extensions = explode ( " ", $allowedExtensions );
	
	for($i = 0; count ( $extensions ) > $i; $i = $i + 1) {
		if (strtolower ( $extensions [$i] ) == strtolower ( $currentExtensions )) {
			$extensionOK = 1;
			$fileExtension = $extensions [$i];
			break;
		}
	}
	
	if ($extensionOK) {
		
		$size = getImageSize ( $src );
		$width = $size [0];
		$height = $size [1];
		
		if ($fileExtension == "jpg" or $fileExtension == 'jpeg') {
			$from = ImageCreateFromJpeg ( $src );
		} elseif ($fileExtension == "gif") {
			$from = ImageCreateFromGIF ( $src );
		} elseif ($fileExtension == 'png') {
			$from = imageCreateFromPNG ( $src );
		}
		
		if ($width >= $dstx and $height >= $dsty) {
			
			$proportion_X = $width / $dstx;
			$proportion_Y = $height / $dsty;
			
			if ($proportion_X > $proportion_Y) {
				$proportion = $proportion_Y;
			} else {
				$proportion = $proportion_X;
			}
			$target ['width'] = $dstx * $proportion;
			$target ['height'] = $dsty * $proportion;
			
			$original ['diagonal_center'] = round ( sqrt ( ($width * $width) + ($height * $height) ) / 2 );
			$target ['diagonal_center'] = round ( sqrt ( ($target ['width'] * $target ['width']) + ($target ['height'] * $target ['height']) ) / 2 );
			
			$crop = round ( $original ['diagonal_center'] - $target ['diagonal_center'] );
			
			if ($proportion_X < $proportion_Y) {
				$target ['x'] = 0;
				$target ['y'] = round ( (($height / 2) * $crop) / $target ['diagonal_center'] );
			} else {
				$target ['x'] = round ( (($width / 2) * $crop) / $target ['diagonal_center'] );
				$target ['y'] = 0;
			}
			
			$new = ImageCreateTrueColor ( $dstx, $dsty );
			
			imagecopyresampled ( $new, $from, 0, 0, $target ['x'], $target ['y'], $dstx, $dsty, $target ['width'], $target ['height'] );
		
		} else {
			$new = $from;
		}
		
		if ($fileExtension == "jpg" or $fileExtension == 'jpeg') {
			imagejpeg ( $new, $dst, $q );
		} elseif ($fileExtension == "gif") {
			//imagegif($new, $dst);
			imagejpeg ( $new, $dst );
		} elseif ($fileExtension == 'png') {
			imagepng ( $new, $dst );
		}
	
	}
}           */
/*

function GetUserAvatar($id, $w = 30, $h = 30) {
	global $db;
	if ($id > 0) {
		$sql1 = "SELECT id, p_char_id, Photo FROM players WHERE id=$id";
		$rst1 = $db->sql_query ( $sql1 ) or die ( mysql_error () );
		if ($db->sql_affectedrows ( $rst1 )) {
			$line1 = $db->sql_fetchrow ( $rst1 );
			$url = "/players/" . ($line1 ["p_char_id"] ? $line1 ["p_char_id"] : $line1 ['id']) . "/";
			$path = "teams/photo";
			$fname = $line1 ['Photo'] ? $line1 ['Photo'] : "nophoto.gif";
			$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" src=\"" . make_small_avatar ( $path, $fname, $w, $h ) . "\" /></a>";
		} else {
			$sql1 = "SELECT user_avatar, user_avatar_type FROM phpbb_users WHERE user_id=$id";
			$rst1 = $db->sql_query ( $sql1 ) or die ( mysql_error () );
			$line1 = $db->sql_fetchrow ( $rst1 );
			if ($line1 ['user_avatar_type'] == 1) {
				$path = "4room/images/avatars";
				$fname = $line1 ['user_avatar'];
			} elseif ($line1 ['user_avatar_type'] == 3) {
				list ( $path, $fname ) = explode ( "/", $line1 ['user_avatar'] );
				$path = "4room/images/avatars/gallery/" . $path;
			} else {
				$path = "/img/tmp/";
				$fname = "nophoto.gif";
			}
			$url = "/4room/profile.php?mode=viewprofile&u=" . $id;
			$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" src=\"" . make_small_avatar ( $path, $fname, $w, $h ) . "\" /></a>";
		}
	} else
		$author_pic = "<img style=\"border: none; vertical-align: top\" src=\"/img/tmp/" . $w . "x" . $h . "nophoto.gif\" />";
	return $author_pic;
}
*/
function GetUserProfile($id, $author = "", $url = true) {
	global $db;
	if ($id) {
		$r = $db->sql_query ( "SELECT username, p.id as pid, p_char_id, id_sex FROM phpbb_users u
				LEFT JOIN players p ON u.user_id=p.id
				WHERE u.user_id=$id" );
		if ($db->sql_affectedrows ( $r )) {
			$l = $db->sql_fetchrow ( $r );
			if ($l ['pid']) {
				$author = ($url ? "<span class=\"sex" . $l ["id_sex"] . "\"><a href=\"/players/" . ($l ["p_char_id"] ? $l ["p_char_id"] : $l ["pid"]) . "/\">" : "") . $l ['username'] . ($url ? "</a></span>" : "");
			} else {
				$author = ($url ? "<a class=\"hid\" href=\"/".$forum_folder_name."/profile.php?mode=viewprofile&u=$id\">" : "") . $l ['username'] . ($url ? "</a>" : "");
			}
		}
	}
	return "<span style=\"color: #444;\">$author</span>";
}

function Sex($id) {
	global $db;
	$sql = "SELECT * FROM players WHERE id=$id";
	$rst = $db->sql_query ( $sql ) or die ( "<p>$sql<p>" . mysql_error () );
	if ($db->sql_affectedrows ( $rst )) {
		$l = $db->sql_fetchrow ( $rst );
		return $l ['id_sex'];
	} else
		return 1;
}

function get_extra($db, $id_category, $parent_id) {
	$a = array ();
	if ($parent_id) {
		$sql = "SELECT * FROM extra WHERE id_category=$id_category AND parent_id=$parent_id";
		$rst = $db->sql_query ( $sql ) or die ( "<p>" . mysql_error () );
		if ($db->sql_affectedrows ( $rst ))
			while ( $line = $db->sql_fetchrow ( $rst ) )
				//if (trim($line['doc']))
				$a [$line ['char_id']] = array ("id" => $line ['id'], "char_id" => $line ['char_id'], "id_category" => $line ['id_category'], "parent_id" => $line ['parent_id'], "id_forum_user" => $line ['id_forum_user'], "title" => $line ['title'], "doc" => stripslashes ( $line ['doc'] ), "o" => $line ['o'] );
	}
	return $a;
}

function cut_long_string($s, $limit = 150) {
	$s = stripslashes ( $s );
	$s = strip_tags ( $s );
	$s = preg_replace ( "/\[.?(b|i|u|size|color|code)(=|:)[^\]]*\]/", "", $s );
	$s = preg_replace ( "/\[url.*\[\/url[^\]]*\]/", "[ссылка]", $s );
	$s = preg_replace ( "/\[img.*\[\/img[^\]]*\]/", "[картинка]", $s );
	$s = preg_replace ( "/\[youtube\][^\[]*\[\/youtube\]/", "[видео]", $s );
	$s = preg_replace ( "/\[quote(.|\n)*\/quote[^\]]*\]/U", "[цитата]", $s );
	$s = preg_replace ( "/^\s*$\n/m", "", $s );
	$s = preg_replace ( "/\n/m", "<br/>", $s ) . " ";
	if (strlen ( $s ) > $limit)
		$s = substr ( $s, 0, strpos ( $s, " ", $limit ) ) . " ...";
	return $s;

}

// существительные ;сле числительных
	function num_decline($num,$nominative,$genitive_singular,$genitive_plural)
	{
		if($num > 10 && (floor(($num%100)/10)) == 1)
		{
        	return $genitive_plural;
		} else
		{
        	switch($num % 10){
                case 1:
                	return $nominative;
                	break;
                case 2:
                case 3:
                case 4:
                	return $genitive_singular;
                	break;
                default:
                	return $genitive_plural;
         	}
        }
	}

    function digits_only($s) {
	return preg_replace("/[^0-9]/","",$s);
    }


  function OutLink($string)
  {
    $host = str_replace('www.', '', getEnv('HTTP_HOST'));
    $host = str_replace('.', '\.', $host);

    $string = preg_replace('/href="?(http:\/\/(?!(www\.|)'.$host.')([^">\s]*))/ie',"'href=\"/links/?go=' . urlencode('\$1') . '\"'", $string);

    return $string;
  }

  function ExtLink($string)
  {
    $host = str_replace('www.', '', getEnv('HTTP_HOST'));
    $host = str_replace('.', '\.', $host);

    $string = preg_replace('/href="?(http:\/\/(?!(www\.|)'.$host.')([^">\s]*))/ie',"'class=\"extlink\" href=\"/links/?go=' . urlencode('\$1') . '\"'", $string);

    return $string;
  }


    function user_has_rights_to_set_tourn_results($id_team, $id_user) {
		global $rights;
		global $user;
		if ($id_user) {
		    if ($rights['all_rights'] || $rights['team_'.$id_team.'_edit'] || ($user->data['id_team']==$id_team))
				return true;
		    else
		    	return false;
		}
		return false;
    }

    function user_has_rights_to_upload_team_foto($id_team, $id_user) {
		global $rights;
		global $user;
		if ($id_user) {
		    if ($rights['all_rights'] || $rights['team_'.$id_team.'_edit'] || ($user->data['id_team']==$id_team))
				return true;
		    else
		    	return false;
		}
		return false;
    }

    function user_has_rights_to_set_tourn_participation($id_user) {
		global $rights;
		global $user;
		global $db;
		$r = $db->sql_query("SELECT id_team FROM players WHERE id=".$id_user);
		if ($db->sql_affectedrows($r)) {
			$l = $db->sql_fetchrow($r);
			$id_team = $l['id_team'];
		}
		if ($id_user) {
		    if ($rights['all_rights'] || $rights['team_'.$id_team.'_edit'] || ($user->data['id_team']==$id_team))
				return true;
		    else
		    	return false;
		}
		return false;
    }


	function parse_doc($doc) {
		global $db;
		global $path_forum;

//		include_once $path_forum."/includes/bbcode.php";
		include_once $path_forum."/includes/constants.php";

		$repl = array();
		preg_match_all("/%forum-post-([0-9]{1,7})%/i",$doc,$matches);

		if (sizeof($matches)) {
				foreach ($matches[0] as $k=>$m) {
/*
					$sql = "SELECT u.user_id, p.*, pl.Photo, pl.p_char_id, pt.post_text, pt.bbcode_uid
							FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt, players pl
							WHERE pt.post_id = p.post_id
								AND u.user_id = p.poster_id
								AND u.user_id = pl.id
								AND p.post_id=".$matches[1][$k];
*/
					$sql = "SELECT u.user_id, p.*, pl.Photo, pl.p_char_id
							 FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, players pl
							WHERE u.user_id = p.poster_id
							  AND u.user_id = pl.id
							  AND p.post_id=".$matches[1][$k];
//echo $sql;
					$r = $db->sql_query($sql);
					if ($db->sql_affectedrows($r)) {
						$l = $db->sql_fetchrow($r);

						$message = stripslashes($l['post_text']);
						$bbcode_uid = $l['bbcode_uid'];

						$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
						$message = bbencode_second_pass($message, $bbcode_uid);
						$message = make_clickable($message);
						$message = str_replace("\n", "\n<br />\n", $message);
						$msg  = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"><tr valign=\"top\">";
						$msg .= "<td width=\"75\" style=\"padding-right: 30;\"><p>";
						$msg .= "<a href=\"/players/".($l['p_char_id']?$l['p_char_id']:$l['user_id'])."/\"><img src=\"".make_small_avatar("teams/photo",($l["Photo"]?$l["Photo"]:"nophoto.gif"),75,100)."\" style=\"border: none;\"></a></p></td>";
						$msg .= "<td><p><div class=\"smalldate0\" style=\"padding-bottom: 5; border-bottom: 1px solid #f0f0f0;\"><b>".NameSurname($l['user_id'],true)."</b>";
						$msg .= "&nbsp;&nbsp;".make_human_date($l['post_edit_time']?$l['post_edit_time']:$l['post_time'])."&nbsp;&nbsp;<a href=\"/".$forum_folder_name."/viewtopic.php?p=".$l['post_id']."#".$l['post_id']."\" title=\Перейти к сообщению на форуме\">&rarr;</a></div>";
						$msg .= "<p>$message</p></td></tr></table><br /><br />";

						array_push($repl, array(
							"search"=>$matches[0][$k],
							"replace"=>$msg
						));
					}
				}
		}


		preg_match_all("/%forum-topic-([0-9]{1,7})%/i",$doc,$matches);

		if (sizeof($matches)) {
				foreach ($matches[0] as $k=>$m) {
					$sql = "SELECT u.user_id, p.*, pl.Photo, pl.p_char_id, pt.post_text, pt.bbcode_uid
							FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt, players pl
							WHERE pt.post_id = p.post_id
								AND u.user_id = p.poster_id
								AND u.user_id = pl.id
								AND p.topic_id=".$matches[1][$k]."
								ORDER BY post_time";
					$r = $db->sql_query($sql);
					if ($db->sql_affectedrows($r)) {
						$msg  = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">";
						while ($l = $db->sql_fetchrow($r)) {

							$message = stripslashes($l['post_text']);
							$bbcode_uid = $l['bbcode_uid'];

							$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
							$message = bbencode_second_pass($message, $bbcode_uid);
							$message = make_clickable($message);
							$message = str_replace("\n", "\n<br />\n", $message);

							$msg .= "<tr valign=\"top\"><td width=\"75\" style=\"padding-right: 30;\"><p>";
							$msg .= "<a href=\"/players/".($l['p_char_id']?$l['p_char_id']:$l['user_id'])."/\">".GetUserAvatar($l['user_id'],75,100)."</a></p><br /><br /></td>";
							$msg .= "<td><p><div class=\"small\" style=\"padding-bottom: 5; border-bottom: 1px solid #f0f0f0;\">".NameSurname($l['user_id'],true)."";
							$msg .= "&nbsp;&nbsp;<span class=\"smalldate0\">".make_human_date($l['post_edit_time']?$l['post_edit_time']:$l['post_time'])."</span>&nbsp;&nbsp;<a href=\"/".$forum_folder_name."/viewtopic.php?p=".$l['post_id']."#".$l['post_id']."\" title=\"Перейти к сообщению на форуме\">&rarr;</a></div>";
							$msg .= "<p>$message</p><br /><br /></td></tr>";
						}
						$msg .= "</table><br /><br />";

						array_push($repl, array(
							"search"=>$matches[0][$k],
							"replace"=>$msg
						));
					}
				}
		}

		foreach($repl as $r) {
			$doc = str_replace($r["search"], $r["replace"], $doc);
		}

		return $doc;
	}


	function get_user_id_by_lj($db, $lj) {
		$r = $db->sql_query("SELECT id FROM players WHERE c_lj='$lj'");
		if ($db->sql_affectedrows($r)) {
			if ($db->sql_affectedrows($r)>1) { /* у нескольких игроков указан одинаковый жж */ }
			$l = $db->sql_fetchrow($r);
			return $l['id'];
		} else
			return 0;
	};

	function getIP() {
		if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
		return $_SERVER['REMOTE_ADDR'];
	}

function get_picasa_album($user, $album_name)
{
	//ini_set('include_path',ini_get('include_path').'.:'.$_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/');
	//$library_path = $_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/';
	//include_once ($_SERVER['DOCUMENT_ROOT'].'/photo/LightweightPicasaAPIv3/Picasa.php');

  // если есть кеш, берем данные из кеша
  if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user.'/'.$album_name) )
  {
      $album_data = unserialize(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user.'/'.$album_name));
  }
  else
  {
      // получаем данные через API picasa
      $pic = new Picasa();
      // получаем данные для альбома, в последнем параметре указываем размеры необходимых изображений. Можно также указать размеры: 72, 144, 200, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600
      // http://googlesystem.blogspot.com/2006/12/embed-photos-from-picasa-web-albums.html
      $album = $pic->getAlbumById($user, $album_name,null,null,null,null,'144,720');

      // получаем данные о изображениях в альбоме
      $images = $album->getImages();
      foreach ($images as $image)
      {
          $thumbnails = $image->getThumbnails();
          $album_data['images'][] = array('url'=>(string)$thumbnails[1]->getUrl(),
                                          'width'=>(string)$thumbnails[1]->getWidth(),
                                          'height'=>(string)$thumbnails[1]->getHeight(),
                                          'title'=>(string)$image->getDescription(),
                                          'tn_url'=>(string)$thumbnails[0]->getUrl(),
                                          'tn_width'=>(string)$thumbnails[0]->getWidth(),
                                          'tn_height'=>(string)$thumbnails[0]->getHeight(),
                                    );

      }
      // иконка альбома, размеры стандартные 160 на 160
      $album_data['url'] = (string)$album->getIcon();
      $album_data['width'] = '160';
      $album_data['height'] = '160';
      $album_data['title'] = (string)$album->getTitle();

      // сохраняем данные в кеш
      if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user))
            mkdir($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user,0777);
      file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user.'/'.$album_name,serialize($album_data));
  }
  return $album_data;
}



?>