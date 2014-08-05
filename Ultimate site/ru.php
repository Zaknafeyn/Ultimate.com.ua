<?
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	switch ($_REQUEST["g"]) {
	    case "manual":
	    	header("location:/man/index.php?id=".$_REQUEST["id"]);
	        break;
	    case "sale":
	    	header("location:/sale/index.php?id=".$_REQUEST["id"]);
	        break;
	    case "tourn":
	    	header("location:/tourn/index.php?id=".$_REQUEST["id"]);
	        break;
	    case "video":
	    	header("location:/video/index.php?id=".$_REQUEST["id"]."&page=".$_REQUEST["page"]);
	        break;
	    case "players":
	    	if (isset($_REQUEST["tid"])) {	    		if ($_REQUEST["tid"] != digits_only($_REQUEST["tid"])) {
			    	header("location:/teams/index.php?tid=".$_REQUEST["tid"]."&e=".$_REQUEST["a"]);
				} else {					$r = $db->sql_query("SELECT char_id FROM teams WHERE id=".digits_only($_REQUEST["tid"]));
					if ($db->sql_affectedrows($r)) {						$l = $db->sql_fetchrow($r);			    		header("location:/teams/".$l['char_id']."/".$_REQUEST["a"]);
					} else {			    		header("location:/teams/");
					}				}
			} elseif (isset($_REQUEST["pid"])) {		    	header("location:/teams/index.php?pid=".$_REQUEST["pid"]);
			} elseif (isset($_REQUEST["pid"])) {		    	header("location:/teams/");
			}
	        break;
	    case "photo":
	    	if (isset($_REQUEST["img"])) {				include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";
				$r = $db->sql_query("SELECT p.id AS pid FROM photo p LEFT JOIN photo_albums a ON p.id_album=a.id WHERE p.fname='".$_REQUEST["img"]."' AND album='".$_REQUEST["album"]."'");
				if ($db->sql_affectedrows($r)) {					$l = $db->sql_fetchrow($r);
					header("location:/photo/index.php?album=".$_REQUEST["album"]."&img=".$l['pid']."&view=image");
				} else
					header("location:/photo/index.php?album=".$_REQUEST["album"]."&view=album");
	  		} elseif (isset($_REQUEST["album"])) {
		    	header("location:/photo/index.php?album=".$_REQUEST["album"]."&view=album");
			} else {
		    	header("location:/photo/");
			}
	        break;
	    default:
	    	header("location:/");
	        break;
	}
?>