<?php
//common functions used by forum and site engine
//include_once functions.php


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
			
			/*
				if ($fileExtension == "jpg" OR $fileExtension=='jpeg') {
					$from = ImageCreateFromJpeg($src);
				} elseif ($fileExtension == "gif") {
					$from = ImageCreateFromGIF($src);
				} elseif ($fileExtension == 'png') {
					$from = imageCreateFromPNG($src);
				}
				*/
			
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
}

function GetWithSiteRootDirectory($relativeDirectory)
{
	global $path_site; 
	return $path_site.$relativeDirectory;
}

function GetPathWithSiteRelativeDirectory($directoryInSite)
{
	global $site_folder_name;
	return $site_folder_name."/".$directoryInSite;
}

function GetPathWithForumRootDirectory($relativeDirectory)
{
	global $path_forum; 
	return $path_forum.$relativeDirectory;
}

function GetPathWithForumRelativeDirectory($directoryInSite)
{
	global $forum_folder_name;
	return $forum_folder_name."/".$directoryInSite;
}


function NameSurname ($id, $url = false, $hid = false) {
	global $db;
	if ($id) {
		$sql = "SELECT * FROM players
    			LEFT JOIN phpbb_users ON id=user_id
	    		WHERE id=$id";
		$rst = $db->sql_query ( $sql );
		if ($db->sql_affectedrows ()) {
			$line = $db->sql_fetchrow ( $rst );
			$namesurname = stripslashes ( $line ['Name'] ) . " " . ($line ['Surname'] ? stripslashes ( $line ['Surname'] ) : stripslashes ( $line ['patronymic'] ));
			if (trim ( $namesurname )) {
				if ($url) {
					return "<span class=\"sex" . $line ['id_sex'] . "\"><a" . ($hid ? " class=\"hid\"" : "") . " href=\"/players/" . ($line ['p_char_id'] ? $line ['p_char_id'] : $line ['id']) . "/\">$namesurname</a></span>";
				} else {
					return $namesurname;
				}
			} else
				return stripslashes ( $line ['username'] );
		} else {
			$sql = "SELECT * FROM phpbb_users WHERE user_id=$id";
			$rst = $db->sql_query ( $sql ) or die ( "<p>$sql<p>" . mysql_error () );
			if ($db->sql_affectedrows ()) {
				$line = $db->sql_fetchrow ( $rst );
				if ($url) {
					return "<a" . ($hid ? " class=\"hid\"" : "") . " href=\"/forum/profile.php?mode=viewprofile&u=$id\">" . stripslashes ( $line ['username'] ) . "</a>";
				} else {
					return stripslashes ( $line ['username'] );
				}
			}
		}
	} else
		die ();
}

function make_small_avatar($fpath, $fname, $w = 50, $h = 50) {
	$fname_new = $w . "x" . $h . "_" . $fname;
	if (! file_exists ( $_SERVER ['DOCUMENT_ROOT'] . "/img/tmp/" . $fname_new )) {
		makeIcons_MergeCenter ( $_SERVER ['DOCUMENT_ROOT'] . "/$fpath/$fname", $_SERVER ['DOCUMENT_ROOT'] . "/img/tmp/" . $fname_new, $w, $h, 85 );
	}
	return "/img/tmp/$fname_new";
}

function GetUserAvatar($id, $w = 30, $h = 30) {
	global $db;
	global $site_folder_name; 
	if ($id > 0) {
		
		$sql1 = "SELECT id, p_char_id, Photo FROM players WHERE id=$id";
		$rst1 = $db->sql_query ( $sql1 );// or die ( mysql_error () );
		
		if ($db->sql_affectedrows ()) {
			$line1 = $db->sql_fetchrow ( $rst1 );
			$url = "/players/" . ($line1 ["p_char_id"] ? $line1 ["p_char_id"] : $line1 ['id']) . "/";
			$path = GetPathWithSiteRelativeDirectory("teams/photo");
			$fname = $line1 ['Photo'] ? $line1 ['Photo'] : "nophoto.gif";
			$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" src=\"" . make_small_avatar ( $path, $fname, $w, $h ) . "\" /></a>";
		} else {
			$sql1 = "SELECT user_avatar, user_avatar_type FROM phpbb_users WHERE user_id=$id";
			$rst1 = $db->sql_query ( $sql1 ) or die ( mysql_error () );
			$line1 = $db->sql_fetchrow ( $rst1 );
			if ($line1 ['user_avatar_type'] == 1) {
				$path = GetPathWithForumRelativeDirectory("images/avatars");
				$fname = $line1 ['user_avatar'];
			} elseif ($line1 ['user_avatar_type'] == 3) {
				list ( $path, $fname ) = explode ( "/", $line1 ['user_avatar'] );
				$path = GetPathWithForumRelativeDirectory("images/avatars/gallery/") . $path;
			} else {
				$path = "/img/tmp/";
				$fname = "nophoto.gif";
			}
			$url = "/forum/profile.php?mode=viewprofile&u=" . $id;
			$author_pic = "<a href=\"$url\"><img style=\"border: none; vertical-align: top\" src=\"" . make_small_avatar ( $path, $fname, $w, $h ) . "\" /></a>";
		}
	} else
		$author_pic = "<img style=\"border: none; vertical-align: top\" src=\"/img/tmp/" . $w . "x" . $h . "nophoto.gif\" />";
	return $author_pic;
}

//-----
?>