<?
    if (isset($_REQUEST['tid'])) {
	    $tid = $_REQUEST['tid'];
	    $rst = $db->sql_query("SELECT * FROM teams WHERE char_id='$tid'");
	    if ($db->sql_affectedrows($rst)) {
	        $line = $db->sql_fetchrow($rst);
	        $tid = $line['id'];
		    $sql = "SELECT * FROM teams AS t
		        LEFT JOIN divisions AS d ON t.id_division=d.id
		        WHERE t.id=$tid";
		    $rst = $db->sql_query($sql) or die ("<p>$sql".mysql_error());
		    $line = $db->sql_fetchrow($rst);
		    $char_id = $line['char_id'];
		    $team_name = $line['team_name'];
		    $team_logo = $line['team_logo'];
		    $division = $line['division'];
		$team_twitter = $line['twitter'];
		    $title = $team_name;
			$meta_description = $title . ", команда по фрисби алтимат (ultimate frisbee)";
			$meta_keywords = $title;
			$team_alive = $line['team_alive'];
	    } else
	    	$tid = 0;
    }

	$id_team = $tid;
	$id_user = $userdata['user_id'];
	$user_has_rights_to_set_tourn_results = user_has_rights_to_set_tourn_results($id_team, $id_user);
	$user_has_rights_to_upload_team_foto = user_has_rights_to_upload_team_foto($id_team,$id_user);

	if ($user_has_rights_to_upload_team_foto) {
		$extra_css[]="datePicker.css";
	}

	$nav1 = "";
	if ($tid) {
	    $sql ="SELECT
	        p.id, p_char_id, p.active, Name, Surname, patronymic, Nick, Number, Photo, Photo_small, id_sex, char_id, u.username
	        FROM players AS p
	        LEFT JOIN phpbb_users AS u ON p.id=u.user_id
	        LEFT JOIN teams AS t ON p.id_team=t.id
	        WHERE id_team=$tid AND p.active=1
	        GROUP BY p.id ORDER BY p.id";
	    $rst = $db->sql_query($sql) or die($sql);
	    $i = 0;

   		$p = "<div class=\"thumbnails\">";
		while ($line = $db->sql_fetchrow($rst)) {
			$m_id = $line['id'];
			$m_p_char_id = $line['p_char_id'];
			$m_nick = "<div class=\"small\">".$line['username']."</div>";
			$m_number = ($line['Number']!="") ? "<h4 style=\"color: silver;\">#".stripslashes(mycut($line['Number']))."</h4>" : "";
			$m_name = stripslashes(mycut($line['Name']));
			$m_name .= ($line['Surname']) ? "<br />".stripslashes(mycut($line['Surname'])) : ($line['patronymic']?"<br />".stripslashes(mycut($line['patronymic'])):"");
			if (!$m_name) $m_name = $line['username'];
			$m_photo_small = $line['Photo'] ? "<img src=\"".make_small_avatar("teams/photo",$line["Photo"],75,100)."\" style=\"border: none\">" : "<img src=\"/teams/photo/nophoto_small.gif\" width=\"75\" height=\"100\" style=\"border: none\">";
			$comments  = ($line['comments']) ? "<a href=\"/players/".($m_p_char_id?$m_p_char_id:$m_id)."/#com\">Комментариев (".$line['comments'].")</a>" : "<a href=\"/players/$m_id/#addcom\">Есть что сказать<br />об этом игроке?</a>";


			$p .= "<ins class=\"thumbnail\">";
			$p .= "<div class=\"p\" style=\"width: 190px;\">";
			$p .= "<div style=\"float: left; padding-right: 15;\">";
			$p .= "<a href=\"/players/".($m_p_char_id?$m_p_char_id:$m_id)."/\">$m_photo_small</a>";
			$p .= "</div>";
			$p .= "<p style=\"padding-top: 0;\"><span class=\"sex".$line["id_sex"]."\"><a href=\"/players/".($m_p_char_id?$m_p_char_id:$m_id)."/\">$m_name</a></span>$m_nick$m_number</p>";
			$p .= "</div>";
			$p .= "</ins>";
		}
		$p .= "</div>";



		$extra = get_extra($db, MY_TEAMS, $tid);

		$ex = array();
		if (trim($extra['team']['doc']))
			$ex['team']=$extra['team'];
		if ($team_alive) {
			$ex['players']=array(
				"char_id"=>'players',
				"title"=>'Игроки',
				"doc"=>$p
			);
		}

		//if ($rights['all_rights']) {
		if (true) {
			include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/teams/admin_results.php";
			if (check_team_results($tid)) {
				$ex['results'] = array(
					"char_id"=>'results',
					"title"=>'Результаты турниров',
					"doc"=>get_team_results($tid)
				);
				if ("results"==$e) $title="Результаты турниров команды $team_name";
			}
		}

		//if ($rights['all_rights']) {
		if (true) {
			include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/teams/admin_foto.php";
			if (team_has_foto($tid)||$user_has_rights_to_upload_team_foto) {
				$ex['photo'] = array(
					"char_id"=>'photo',
					"title"=>'Фотографии',
					"doc"=>get_team_foto($tid)
				);
				if ("photo"==$e) $title="Фотографии команды $team_name";
			}
		}


		foreach ($extra as $k=>$e1)
			if ($k!='team')
				$ex[$k]=$e1;

		if ($e=="") {
			$e="team";
			if (!$ex['team']) $e='players';
			if ($team_twitter) {
				$r = $db->sql_query("SELECT descr FROM rssnews WHERE domain='http://twitter.com' AND title='$team_twitter'");
				if ($db->sql_affectedrows($r)) {
					$l = $db->sql_fetchrow($r);
					$twitter  = "<p><div class=\"smalldate0\" style=\"background: url('/img/bg-icon-twitter.gif') 0 0 no-repeat; padding-left: 20; line-height: 16px;\">";
					$twitter .= iconv("UTF-8","WINDOWS-1251",$l['descr']);
					$twitter .= "</div></p>";
				}
			}
		}

		$empty = 0;
		foreach ($ex as $k=>$e1) {
			if (trim($e1['doc'])) {
				if ($e==$k)
					$nav1 .= "<span class=\"sel1\">".preg_replace("/ /","&nbsp;",$e1['title'])."</span> &nbsp; &nbsp; ";
				else
					$nav1 .= "<span class=\"sel0\"><a href=\"/teams/$char_id/".($k!="team"?$k."/":"")."\">".preg_replace("/ /","&nbsp;",$e1['title'])."</a></span> &nbsp; &nbsp; ";
			} else {
				$empty++;
			}
		}

		if ((sizeof($ex)-$empty)>1)
          $nav1 = "<p><div class=\"menu\">".$nav1."</div></p>";
  		else $nav1="";
	}

	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/header.php";

?>
	<table cellspacing="0" cellpadding="15" border="0" width="100%">
	    <tr valign="top">
    	    <td>
				<h1><? if ($team_logo) print "<img src=\"/teams/img/$team_logo\" alt=\"$team_name\" style=\"vertical-align: middle;\" />&nbsp;&nbsp;"; ?><?=$title;?></h1>
				<?=$twitter?>
				<p>
	    		<?=$nav1;?>
        		<br /><br />
	    		<?=$ex[$e]["doc"];?>
			</td>
			<td width="170">
			    <? include "teams_list.php"; ?>
			</td>
		</tr>
	</table>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>
