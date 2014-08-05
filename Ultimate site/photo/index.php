<?
    function GetPathTxt($id) {
    	global $db;
    	global $aid0;
        $r = $db->sql_query ("SELECT * FROM photo_albums WHERE id=$id") or die(mysql_error());
		$l = $db->sql_fetchrow($r);
        if ($l['parent_id']) {
        	return GetPathTxt($l['parent_id'])." &gt; ".($id==$aid0?stripslashes($l['title']):"<a href=\"/photo/".GetPath($db, $l['id'])."/\">".stripslashes($l['title'])."</a>");
        } else
        	return "<a href=\"/photo/\">Альбомы</a> &gt; ".($l['id']==$aid0 ? stripslashes($l['title']):"<a href=\"/photo/".GetPath($db, $l['id'])."/\">".stripslashes($l['title'])."</a>");
    }


    function GetPathFromTo($from,$to) {
    	global $db;
        $r = $db->sql_query ("SELECT * FROM photo_albums WHERE id=$to") or die(mysql_error());
		$l = $db->sql_fetchrow($r);
        if ($l['parent_id']==$from) {
        	return "<a href=\"/photo/".GetPath($db, $l['id'])."/\">".stripslashes($l['title'])."</a>";
        } else
        	return GetPathFromTo($from,$l['parent_id'])." &gt; <a href=\"/photo/".GetPath($db, $l['id'])."/\">".stripslashes($l['title'])."</a>";
    }



	function InitAlbums($aid) {
		global $db;
		global $al;
		$r = $db->sql_query("SELECT * FROM photo_albums WHERE parent_id=$aid");
		if ($db->sql_affectedrows($r))
			while ($l = $db->sql_fetchrow($r))
				InitAlbums($l['id']);
		if ($al[$aid]['cnt']) {
			$a = $al[$aid];
			$url = $a['url'];
			//$show = "<div class=\"athumb\">";
			$show = "<ins class=\"thumbnail\">";
			$show.= "<div class=\"a\">";
			$show.= $a["picasa"]."<a href=\"/photo/$url/\">".($a['titleshort']?stripslashes($a['titleshort']):$a['title'])."</a>";
			$show.= "<p><a href=\"/photo/$url/\"><img src=\"".($a['cover']?$a['cover']:"/img/blank.png")."\" style=\"border: none\" width=\"150\" height=\"150\" /></a></p>";
			//$show.= "<span class=\"athumbcom\">";
			$show.= "<p>";
			$show.= $a['cnt']." ".num_decline($a['cnt'],"фотография","фотографии","фотографий");
			$show.= $a['com'] ? "<br /><a href=\"/photo/$url/-comments\">".$a['com']." ".num_decline($a['com'],"комментарий","комментария","комментариев")."</a>":"";
			$show.= "</p>";
			//$show.= "</span>";
			$show.= "</div>";
			$show.= "</ins>";
			$al[$aid]['show']=$show;
		}
	}

	function ShowAlbums($aid,$limit=0) {
		global $al;
		InitAlbums($aid);
		if (!$limit) $limit = sizeof($al);
		print "<div class=\"thumbnails\">";
		foreach($al as $a)
			if ($a['show'] && $limit) {
				print $a['show'];
				$limit--;
			}
		print "</div>";
	}


	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	$com = array();
	$com_p = array();
	$sql = "SELECT id_album, id_item, COUNT(id_item)
		FROM comments c
		LEFT JOIN photo p ON c.id_item=p.id
		WHERE id_category=".MY_PHOTO."
		GROUP BY id_album, id_item
		";
	$r = $db->sql_query();
	//echo $sql;
	if ($db->sql_affectedrows($r))
		while ($l = $db->sql_fetchrow($r)) {
			$com_p[$l[1]]=$l[2];
			$com[$l[0]]+=$l[2];
		}

	$cover = array();
	$r = $db->sql_query("SELECT id_album, fname FROM photo WHERE cover=1");
	if ($db->sql_affectedrows($r))
	{
		while ($l = $db->sql_fetchrow($r))
		{
			$cover[$l[0]]="/photo/albums/".GetPath($db, $l["id_album"])."/small/".$l["f_name"];
		}
	}
	$sql = "SELECT id, cover FROM photo_albums WHERE cover<>''";
	$r = $db->sql_query($sql);

	if ($db->sql_affectedrows($r))
		while ($l = $db->sql_fetchrow($r))
			$cover[$l[0]]=$l["cover"];

	$al = array();
	$r = $db->sql_query("SELECT
		a.id AS aid, a.parent_id AS parent_id, a.title, titleshort, titlefull, descr, picasa_user, picasa_album, COUNT(p.id) AS cnt, MAX(p.dateadd) AS dateadd
		FROM photo p
		RIGHT JOIN photo_albums a ON p.id_album=a.id
		GROUP BY aid
		ORDER BY dateadd DESC
		");
	if ($db->sql_affectedrows($r))
		while ($l = $db->sql_fetchrow($r))
			if ($l['aid'])
				$al[$l['aid']]=array(
					"aid"=>$l['aid'],
					"parent_id"=>$l['parent_id'],
					"titlefull"=>$l['titlefull']?stripslashes($l['titlefull']):stripslashes($l['title']),
					"titleshort"=>stripslashes($l['titleshort']),
					"title"=>stripslashes($l['title']),
					"descr"=>($l['picasa_user'] ? "<p><div class=\"smalldate0\">".$picasa_favicon."&nbsp;&nbsp;<a href=\"http://picasaweb.google.ru/".$l['picasa_user']."/".$l['picasa_album']."\">http://picasaweb.google.ru/".$l['picasa_user']."/".$l['picasa_album']."</a></div>":"")."<p>".stripslashes($l['descr']),
					"picasa"=>($l['picasa_user'] ? $picasa_favicon."&nbsp;" : ""),
					"cnt"=>$l['cnt'],
					"com"=>$com[$l['aid']],
					"cover"=>$cover[$l['aid']],
					"url"=>GetPath($db, $l['aid']),
					"show"=>''
				);

	if (isset($_GET['view']))
		$view = $_GET['view'];
	else
		$view = "albums";

//	$extra_css = array('../photo/style.css');

	$loveurl="<a id=\"o0\" href=\"/photo/love/#o0\" title=\"Избранные фотографии\"><img src=\"/img/love.png\" style=\"border: none\" title=\"Избранные фотографии\" alt=\"Избранные фотографии\" /></a>";
	
	$path = $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/photo/";
	
	
    $pg = "photo/1";
	switch ($view) {
		case "albums":			
			include $path ."albums.php";
			break;
		case "album":
			include $path ."album.php";
			break;
		case "image":
			include "image.php";
			break;
		case "user":
			include "user.php";
			break;
		case "love":
		    $pg="photo";
			include "love.php";
			break;
		case "comments":
			include "comments.php";
			break;
		default:
	    	$pg = "photo";
			include "love.php";
			break;
	}

	die();
?>