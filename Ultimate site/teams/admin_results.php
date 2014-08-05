<?
	$teams = array();
	$r = $db->sql_query("SELECT * FROM teams");
	if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) $teams[$l['id']]=$l;


	$players = array();
	$r = $db->sql_query("SELECT username, id_sex, p.id as pid FROM players p LEFT JOIN phpbb_users u ON p.id=u.user_id WHERE id_team=$id_team");
	if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) $players[]=$l;


	function check_team_results($id_team) {
		global $userdata;
		global $db;
		$r = $db->sql_query("SELECT year_started_playing FROM teams WHERE id=$id_team");
		if ($db->sql_affectedrows($r)) {
			$l = $db->sql_fetchrow($r);
			$year_started_playing = $l['year_started_playing'];
		} else $year_started_playing = 2001;
		$tourn = array();
		$r = $db->sql_query("SELECT * FROM tourn WHERE teamsO<>0 AND dat_begin<".date("Ymd")." AND teams_can_set_results=1 AND dat_begin>'".$year_started_playing."0101' ORDER BY dat_begin DESC");
		if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) $tourn[$l['id']]=$l;

		$ptcp_cnt = 0;
		$r = $db->sql_query("SELECT * FROM scores WHERE id_team=$id_team");
		if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) {
			if (0==$l['ptcp'])
				unset($tourn[$l['id_tourn']]);
			else
				$ptcp_cnt++;
		}

		if ($ptcp_cnt)
			return true;
		elseif (user_has_rights_to_set_tourn_results($id_team, $userdata['user_id']))
			return true;
		else
			return false;
	}

	function get_team_results($id_team) {
		global $teams;
		global $user_has_rights_to_set_tourn_results;
global $db;

		$output = "";

		$dont_ptcp = array();
		$tourn = array();
		$r = $db->sql_query("SELECT * FROM tourn WHERE teamsO<>0 AND dat_begin<".date("Ymd")." AND teams_can_set_results=1 AND dat_begin>=".$teams[$id_team]['year_started_playing']."0101 ORDER BY dat_begin DESC");
		if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) $tourn[$l['id']] = 0;

		$scores = array();
		$r = $db->sql_query("SELECT * FROM scores WHERE id_team=$id_team");
		if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) {
			$id_tourn = $l['id_tourn'];
			if (!$l['ptcp'])
				unset($tourn[$id_tourn]);
			else
				$tourn[$id_tourn]=1;
		}

		$results = array();

		$output .= "<input type=\"hidden\" name=\"id_team\" id=\"id_team\" value=\"$id_team\" />";

		foreach ($tourn as $id_tourn=>$got_results) {
			if ($got_results)
				$results[] = "<li id=\"list_".$id_tourn."\"".($user_has_rights_to_set_tourn_results?" class=\"tourn_entry\"":"").">".get_tourn_result($id_team, $id_tourn, $user_has_rights_to_set_tourn_results)."</li>";
				//$results[] = get_tourn_result($id_team, $id_tourn, $user_has_rights_to_set_tourn_results);
			elseif ($user_has_rights_to_set_tourn_results) {
				$results[] = "<li id=\"list_".$id_tourn."\" class=\"tourn_entry\" style=\"display: none;\"></li>";
				$results[] = "<li id=\"list_".$id_tourn."_edit\">".get_tourn_edit_form($id_team, $id_tourn)."</li>";
			}
		}

		if ($user_has_rights_to_set_tourn_results/* && $output*/) {
			$output .= "\n<script language=\"javascript\" src=\"/js/admin-team-results.js\"></script>\n";
		}


		if (sizeof($results)) {
			//krsort($results);
			//$out = "<ul style=\"list-style-type: square;\">";
			$out = "<ul id=\"tourn_results\">";
			foreach($results as $r) {
				$out .= $r;
				//$out .= "<p>$year</p>";
				//$out .= "<ul style=\"list-style-type: square;\">";
				//foreach ($r as $r0) $out .= $r0;
				//$out .= "</ul></p>";
			}
			$out .= "</ul>";
		}

		return $output.$out;
	}



	function get_tourn_edit_form($id_team, $id_tourn, $cancel_button=false) {
		global $userdata;
		global $teams;
		global $players;

		$r = $db->sql_query("SELECT * FROM tourn WHERE id=$id_tourn");
		if ($db->sql_affectedrows($r)) $t = $db->sql_fetchrow($r);

		$output = "<p><div id=\"tourn_$id_tourn\" style=\"padding: 15px; background-color: #f0f0ff;\">";

		if ($cancel_button) {
			$output .= "<div style=\"font-size: 11px; float: right;\">";
			$output .= "<a class=\"pseudo_link clickable\" onclick=\"tourn_cancel_edit($id_tourn);return false;\">отмена</a>";
			$output .= "</div>";
		}

		$output .= "<p>".$userdata['username'].", как команда ".$teams[$id_team]['team_name']." выступила на&nbsp;турнире?";
		$output .= "<p><h3><a target=\"_blank\" href=\"/tourn/".$t['char_id']."/\"><img src=\"/img/new-window-icon.gif\" style=\"border: none;\" width=\"11\" height=\"11\" alt=\"Открыть в новом окне\" /></a>&nbsp;<b>".$t['short_name']."</b>";
		$output .= "&nbsp;<span class=\"small\"><a style=\"cursor: hand;\" class=\"pseudo_link clickable\" onclick=\"tourn_close($id_tourn); return false;\">не участвовали</a>&nbsp;[x]&nbsp;&nbsp;<span id=\"preloader_$id_tourn\"></span></h3>";

		$output .= "<input type=\"hidden\" name=\"mvp0_$id_tourn\" id=\"mvp0_$id_tourn\" value=\"\" />";
		$output .= "<input type=\"hidden\" name=\"mvp1_$id_tourn\" id=\"mvp1_$id_tourn\" value=\"\" />";
		$output .= "<p><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\"><tr valign=\"top\">";

		$r = $db->sql_query("SELECT * FROM scores WHERE id_tourn=$id_tourn AND ptcp=1");
		$sc = array();
		if ($db->sql_affectedrows($r)) while ($l=$db->sql_fetchrow($r)) {
			$scores = explode(";",$l['scores']);
			foreach ($scores as $s) {
				$div = substr($s,0,1); // дивизион
				if (substr($s,2,4)=="sotg") {
					// дух игры
					$sc[$div]["sotg"] = $l['id_team'];
				} else if (substr($s,2,3)=="mvp") {
					// mvp
					$sc[$div]["mvp"] = substr($s,5);
				} else {
					// место
					$place = substr($s,2);
					$sc[$div]["$place"]=$l['id_team'];
				}
			}
		}

		if ($t["teamsO"]) {
			$output .= "<td width=\"200\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
			if ($t["teamsW"]) $output .= "<tr><td colspan=\"2\">Открытый дивизион<p></td></tr>";
			for ($i=1;$i<=$t['teamsO'];$i++) {
				$output .= "<tr>";
				$output .= "<td align=\"right\"><label for=\"0-$i\">$i&nbsp;место</label></td><td style=\"padding-left: 15;\">";
				if (isset($sc[0]["$i"]))
					if ($sc[0]["$i"]==$id_team)
						$output .= "<input id=\"0-$i\" type=\"checkbox\" value=\"0-$i\" checked />";
					else
						$output .= "<label>".$teams[$sc[0]["$i"]]["team_name"]."</label>";
				else
					$output .= "<input id=\"0-$i\" type=\"checkbox\" value=\"0-$i\" />";
				$output .= "</td></tr>";
			}

			$output .= "<tr valign=\"top\"><td></td><td style=\"padding-left: 15;\"><br /><input id=\"Osotg$id_tourn\" name=\"Osotg$id_tourn\" type=\"checkbox\" value=\"0-sotg\"".(($sc[0]["sotg"]==$id_team)?" checked":"")." />&nbsp;<label for=\"Osotg$id_tourn\">Дух Игры</label></td></tr>";
			$output .= "<tr valign=\"top\"><td align=\"right\"><br /><label>MVP</label></td><td style=\"padding-left: 15;\">";
			$output .= "<br /><select onchange=\"$('#mvp0_$id_tourn').val(this.options[this.selectedIndex].value);\">";
			$output .= "<option value=\"\"></option>";
			foreach ($players as $p)
				$output .= "<option value=\"".$p['pid']."\"".(($sc[0]["mvp"]==$p['pid'])?" selected":"").">".$p['username']."</option>";
			$output .= "</select>";
			$output .= "</td></tr>";

			$output .= "</table></td>";
		}
		if ($t["teamsW"] /*&& ($teams[$id_team]['id_division']==2)*/) {
			$output .= "<td style=\"padding-left: 20;\" width=\"200\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
			$output .= "<tr><td colspan=\"2\">Женский дивизион<p></td></tr>";
			for ($i=1;$i<=$t['teamsW'];$i++) {
				$output .= "<tr>";
				$output .= "<td align=\"right\"><label for=\"1-$i\">$i&nbsp;место</label></td><td style=\"padding-left: 15;\">";
				if (isset($sc[1]["$i"]))
					if ($sc[1]["$i"]==$id_team)
						$output .= "<input id=\"1-$i\" type=\"checkbox\" value=\"1-$i\" checked />";
					else
						$output .= "<label>".$teams[$sc[1]["$i"]]["team_name"]."</label>";
				else
					$output .= "<input id=\"1-$i\" type=\"checkbox\" value=\"1-$i\" />";
				$output .= "</td></tr>";
			}

			$output .= "<tr valign=\"top\"><td></td><td style=\"padding-left: 15;\"><br /><input id=\"Wsotg$id_tourn\" name=\"Wsotg$id_tourn\" type=\"checkbox\" value=\"1-sotg\"".(($sc[1]["sotg"]==$id_team)?" checked":"")." />&nbsp;<label for=\"Wsotg$id_tourn\">Дух Игры</label></td></tr>";
			$output .= "<tr valign=\"top\"><td align=\"right\"><br /><label>MVP</label></td><td style=\"padding-left: 15;\">";
			$output .= "<br /><select onchange=\"$('#mvp1_$id_tourn').val(this.options[this.selectedIndex].value);\">";
			$output .= "<option value=\"\"></option>";
			foreach ($players as $p)
				if (0==$p['id_sex']) $output .= "<option value=\"".$p['pid']."\"".(($sc[1]["mvp"]==$p['pid'])?" selected":"").">".$p['username']."</option>";
			$output .= "</select>";
			$output .= "</td></tr>";

			$output .= "</table></td>";
		}

		$output .= "<td style=\"padding-left: 20px;\">";
		$output .= "Что запомнилось:";
		$output .= "<p><textarea id=\"comment_$id_tourn\" name=\"comment_$id_tourn\" rows=\"10\" style=\"width: 100%;\">";
		$r = $db->sql_query("SELECT * FROM comments WHERE
	   		id_category=".MY_TEAM_TOURN." AND
	   		id_item=$id_tourn AND
	   		id_sub_item=$id_team AND
	   		id_forum_user=".$userdata['user_id']
	   	);
		if ($db->sql_affectedrows($r)) {
			$l = $db->sql_fetchrow($r);
			$output .= stripslashes($l['txt']);
		}
		$output .= "</textarea>";
		$output .= "</td>";

		$output .= "</tr>";

		$output .= "</table>";

		$output .= "<p><button onclick=\"tourn_set_results($id_tourn); return false;\">Клянусь, так и было!</button>";

		$output .= "</div></p>";

		return $output;
	}


	function get_tourn_result($id_team, $id_tourn, $user_has_rights_to_set_tourn_results) {
		global $db;
		$r = $db->sql_query("SELECT *
			FROM tourn t
			LEFT JOIN scores s ON t.id=s.id_tourn
			WHERE t.id=$id_tourn AND s.id_team=$id_team");
		if ($db->sql_affectedrows($r)) $t = $db->sql_fetchrow($r);

		if (!$t['scores']) return '';

		$year = substr($t["dat_begin"],0,4);
		//$out = "<li id=\"list_$id_tourn\"".($user_has_rights_to_set_tourn_results?" class=\"tourn_entry\"":"").">";
		//$out = "<div>";
		$out = "";

		if ($user_has_rights_to_set_tourn_results) {
			$out .= "<span class=\"action-edit\">";
			$out .= "<span id=\"preloader_$id_tourn\"></span>";
			$out .= "&nbsp;&nbsp;<a class=\"pseudo_link clickable\" onclick=\"tourn_edit_results($id_tourn);return false;\">редактировать</a>";
			$out .= "&nbsp;&nbsp;&nbsp;<a class=\"pseudo_link clickable\" onclick=\"tourn_comment($id_tourn);return false;\">комментировать</a></span>";
		}
		$out .= "<a href=\"/tourn/".$t["char_id"]."/\">".$t["short_name"]."</a>";

		$sc = array();
		$scores = explode(";",$t['scores']);
		foreach ($scores as $s) {
			$div = substr($s,0,1); // дивизион
			if (substr($s,2,4)=="sotg") {
				// дух игры
				$sc[$div]["sotg"] = 1;
			} else if (substr($s,2,3)=="mvp") {
				// mvp
				$sc[$div]["mvp"] = substr($s,5);
			} else {
				// место
				$place = substr($s,2);
				if (!isset($sc[$div]["place"])) $sc[$div]["place"] = array();
				$sc[$div]["place"][] = $place;
			}
		}

		//$tourn[$id_tourn]["comments"][$l['id_team']] = array($l['comment'],$l['id_forum_user']);

		$res = array();
		foreach ($sc as $div=>$t0) {
			if (sizeof($t0["place"])) {
			sort($t0["place"]);
			foreach ($t0["place"] as $s) {
				$place = "<div class=\"team_place";
				if (1==$s) {
					$place .= " team_place_1";
				} elseif (2==$s) {
					$place .= " team_place_2";
				} elseif (3==$s) {
					$place .= " team_place_3";
				}
				//$place .= "\">$s /".($div?"<span class=\"divisionW\">".$t["teamsW"]."</span>":$t["teamsO"])."</div>";
				$place .= "\"><span>$s</span>&nbsp;&nbsp;/".($div?$t["teamsW"]:$t["teamsO"])."</div>";
				array_push($res, $place);
			}
			}
			if (1==$t0["sotg"]) array_push($res, "<div class=\"team_place team_sotg\">Дух Игры</div>");
			if ($t0["mvp"]) array_push($res, "MVP: ".NameSurname($t0["mvp"], false));
		}
		$out .= " ".implode(" ", $res);

		$comments = "";
		$r = $db->sql_query("SELECT * FROM comments WHERE
	   		id_category=".MY_TEAM_TOURN." AND
	   		id_item=$id_tourn AND
	   		id_sub_item=$id_team
	   		ORDER BY dat"
	   	);print mysql_error();
	   	$limit = 200;
		if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) {
			$id_comment = $l["id"];
			if ($comments) $comments .= "<br />";
			$txt = htmlspecialchars($l["txt"])." ";
			if ( strlen($txt) > $limit ) {
				$space_position = strpos($txt, " ", $limit);
				$visible = substr($txt, 0, $space_position);
				$invisible = trim(substr($txt, $space_position+1))?" <a id=\"show_comment_".$id_comment."\" class=\"pseudo_link clickable\" onclick=\"show_the_rest($id_comment);return false;\">&darr;&darr;&darr;</a><span id=\"rest_of_".$id_comment."\" style=\"display: none;\">".substr($txt, $space_position+1)."</span>":"";
				$txt = $visible.$invisible;
			}
			$comments .= GetUserProfile($l['id_forum_user'],$l['author'],false).": ".preg_replace("/\n/m","<br />",$txt);
		}
		if ($comments) {
			$out .= "<div class=\"small gray\">".stripslashes($comments)."</div>";
		}

		return $out;
	}



	function get_tourn_comments_form($id_team, $id_tourn, $cancel_button=false) {
		global $userdata;
		global $teams;
		global $players;

		$r = $db->sql_query("SELECT * FROM tourn WHERE id=$id_tourn");
		if ($db->sql_affectedrows($r)) $t = $db->sql_fetchrow($r);

		$output = "<p><div style=\"padding: 15px; background-color: #f0f0ff;\">";

		if ($cancel_button) {
			$output .= "<div style=\"font-size: 11px; float: right;\">";
			$output .= "<a class=\"pseudo_link clickable\" onclick=\"tourn_cancel_edit($id_tourn);return false;\">отмена</a>";
			$output .= "</div>";
		}

		$output .= $userdata['username'].", как тебе <b>".$t['short_name']."</b>? Как сыграли, что запомнилось?";
		$output .= "<p><textarea id=\"comment_$id_tourn\" name=\"comment_$id_tourn\" rows=\"10\" style=\"width: 100%;\" onKeyPress='if (event.keyCode==10 || (event.ctrlKey && event.keyCode==13)) button_".$id_tourn.".click()'>";
		$r = $db->sql_query("SELECT * FROM comments WHERE
	   		id_category=".MY_TEAM_TOURN." AND
	   		id_item=$id_tourn AND
	   		id_sub_item=$id_team AND
	   		id_forum_user=".$userdata['user_id']
	   	);
		if ($db->sql_affectedrows($r)) {
			$l = $db->sql_fetchrow($r);
			$output .= stripslashes($l['txt']);
		}
		$output .= "</textarea>";

		$output .= "<p><button id=\"button_$id_tourn\" onclick=\"tourn_set_results($id_tourn); return false;\">ОК (Ctrl+Enter)</button>";

		$output .= "</div></p>";

		return $output;
	}
?>
