<?
	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/init.php";

	$id_team = digits_only($_POST['id_team']);
	$id_tourn = digits_only($_POST['id_tourn']);
	$action = $_POST['action'];

	include_once $_SERVER['DOCUMENT_ROOT']."/site/teams/admin_results.php";

	$ptcp = digits_only($_POST['ptcp']);
	$id_user =$userdata['user_id'];
	$user_has_rights_to_set_tourn_results = user_has_rights_to_set_tourn_results($id_team,$id_user);

	if ('get_edit_form'==$action && $user_has_rights_to_set_tourn_results) {
		echo "<li id=\"list_".$id_tourn."_edit\">".iconv("WINDOWS-1251","UTF-8",get_tourn_edit_form($id_team, $id_tourn, true))."</li>";
		exit;
	}

	if ('get_comments_form'==$action && $user_has_rights_to_set_tourn_results) {
		echo "<li id=\"list_".$id_tourn."_edit\">".iconv("WINDOWS-1251","UTF-8",get_tourn_comments_form($id_team, $id_tourn, true))."</li>";
		exit;
	}

	if ($id_team && $id_tourn && $user_has_rights_to_set_tourn_results)  {

		if (0 == $ptcp) {
			// команда не участвовала в турнире
			$db->sql_query("DELETE FROM scores WHERE id_team=$id_team AND id_tourn=$id_tourn");
			$db->sql_query("INSERT INTO scores SET
				id_team=$id_team,
				id_tourn=$id_tourn,
				ptcp=0,
				id_forum_user=".$userdata['user_id']
			);
			echo 1;
			exit;

		} else if (isset($_POST['scores'])) {

			$sc = array();
			foreach($_POST['scores'] as $s) {
				array_push($sc, preg_replace("/[^a-z0-9-]/","",$s));
			}
			$scores = implode(";",$sc);

			if (preg_match("/0|1-\d{1,2}/",$scores)) {
				if ($_POST['mvp0']) $scores.=";0-mvp".digits_only($_POST['mvp0']);
				if ($_POST['mvp1']) $scores.=";1-mvp".digits_only($_POST['mvp1']);

				$db->sql_query("DELETE FROM scores WHERE id_team=$id_team AND id_tourn=$id_tourn");
				$sql = "INSERT INTO scores SET
					id_team=$id_team,
					id_tourn=$id_tourn,
					scores='$scores',
					ptcp=1,
					id_forum_user=".$userdata['user_id'];
				$db->sql_query($sql);
			} else {
				echo '';
				exit;
			}
		}
		if (isset($_POST['comment'])) {
	    	$db->sql_query("DELETE FROM comments WHERE
	    		id_category=".MY_TEAM_TOURN." AND
	    		id_item=$id_tourn AND
	    		id_sub_item=$id_team AND
	    		id_forum_user=".$userdata['user_id']
	    	);
			$comment = iconv("UTF-8","WINDOWS-1251",$_POST['comment']);
		   	$comment = trim(preg_replace("/<[\/\!]*?[^<>]*?>/si","",$comment));
		    $comment = preg_replace (array("/\[(\/?(b|i|u|a))\]/i","/((ht|f)+tp:\/\/[^\s]*)(\s|$|\?)/"), array("<\\1>"," <a href=\"\\1\">\\1</a> "), $comment);
		    $comment = mysql_escape_string($comment);
		    if ($comment) {
		    	$db->sql_query("INSERT INTO comments SET
		    		id_category=".MY_TEAM_TOURN.",
		    		id_item=$id_tourn,
		    		id_sub_item=$id_team,
		    		id_forum_user=".$userdata['user_id'].",
		    		author='".$userdata['username']."',
		    		ip='".getIP()."',
		    		dat=".time().",
		    		txt='$comment'
		    	");
		    }
		}

		echo iconv("WINDOWS-1251","UTF-8",get_tourn_result($id_team, $id_tourn, $user_has_rights_to_set_tourn_results));
	}
	//mail ("frisbee@tut.by","t_res","id_team: $id_team, id_tourn: $id_tourn, ptcp: $ptcp, id_forum_user: ".$userdata['user_id']);

?>
