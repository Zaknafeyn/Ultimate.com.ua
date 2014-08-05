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
		    $title = $team_name;
			$meta_description = $title . ", команда по фрисби алтимат (ultimate frisbee)";
			$meta_keywords = $title;
			$team_alive = $line['team_alive'];
	    } else
	    	$tid = 0;
    }

	$nav1 = "";
	if ($tid) {
	    $sql ="SELECT
	        p.id, p_char_id, p.active, Name, Surname, patronymic, Nick, Number, Photo, Photo_small, id_sex, char_id, u.username,
	        COUNT(c.id) AS comments
	        FROM players AS p
	        LEFT JOIN phpbb_users AS u ON p.id=u.user_id
	        LEFT JOIN teams AS t ON p.id_team=t.id
	        LEFT JOIN comments AS c ON p.id=c.id_item AND c.id_category=".MY_PLAYERS."
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
			$m_photo_small = $line['Photo'] ? "<img src=\"".make_small_avatar("teams/photo",$line["Photo"],75,100)."\" style=\"border: none;\">" : "<img src=\"/teams/photo/nophoto_small.gif\" width=\"75\" height=\"100\" style=\"border: none\">";
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

		include_once $_SERVER['DOCUMENT_ROOT']."/site/teams/admin_results.php";
		if (check_team_results($tid)) {
			$ex['results'] = array(
				"char_id"=>'results',
				"title"=>'Результаты турниров',
				"doc"=>get_team_results($tid)
			);
			if ("results"==$e) $title="Результаты турниров команды $team_name";
		}

		}

		foreach ($extra as $k=>$e1)
			if ($k!='team')
				$ex[$k]=$e1;

		if ($e=="") {
			$e="team";
			if (!$ex['team']) $e='players';
		}

		$empty = 0;
		foreach ($ex as $k=>$e1) {
			if (trim($e1['doc'])) {
				if ($e==$k)
					$nav1 .= "<span class=\"sel1\">".$e1['title']."</span>&nbsp;&nbsp;&nbsp;&nbsp;";
				else
					$nav1 .= "<span class=\"sel0\"><a href=\"/teams/$char_id/".($k!="team"?$k."/":"")."\">".$e1['title']."</a></span>&nbsp;&nbsp;&nbsp;&nbsp;";
			} else {
				$empty++;
			}
		}

		if ((sizeof($ex)-$empty)>1)
          $nav1 = "<p><div class=\"menu\">".$nav1."</div></p>";
  		else $nav1="";
	}

	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/header.php";

?>
	<table cellspacing="0" cellpadding="15" border="0" width="100%">
	    <tr valign="top">
    	    <td>
							<span class="division$division"><h1><? if ($team_logo) print "<img src=\"/teams/img/$team_logo\" alt=\"$team_name\" style=\"vertical-align: middle;\" />&nbsp;&nbsp;"; ?><?=$title;?></h1></span><p>
	    					<?=$nav1;?>
	    					<br />
	    					<p>
						<div style="overflow: hidden;">
	    					<?=$ex[$e]["doc"];?>
						</div>
	    					</p>
					</div>

			</td>
			<td width="200">
			                		<p>
			                			<noindex>
				                		<table cellspacing="0" cellpadding="0" border="0">
				                			<tr><td colspan="2"><p><br /></p><div class="vmenu" style="padding-right: 0;"><div class="sel0"><a href="/teams/">Все команды</a>:</div></div></td></tr>
				                			<tr><td>
				                		<?
			                                $sql = "SELECT COUNT(p.id) AS cnt, t.id AS tid, t.char_id, t.team_name,
			                                    c.city, d.division
			                                    FROM players AS p
			                                    LEFT JOIN teams AS t ON p.id_team=t.id
			                                    LEFT JOIN cities AS c ON t.id_city=c.id
			                                    LEFT JOIN divisions AS d ON t.id_division=d.id
			                                    WHERE t.id<>0 AND team_alive=1 AND team_contacts<>''
			                                    GROUP BY tid ORDER BY c.id ASC, d.division ASC, team_rating DESC";
			                                $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
			                                while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
			                                	if ($city != $line['city']) {
			                                		$city = $line['city'];
			                                		print "</td></tr>";
			                                		print "<tr valign=\"top\"><td width=\"100\"><p>";
			                                		print "<div class=\"vmenu\" style=\"padding-right: 0\"><div class=\"sel0\">$city</div></div>";
			                                		print "</td><td><p><div class=\"vmenu\">";
			                                	}
			                                    $team_id = $line['tid'];
			                                    if ($team_id == $tid) {
				                                    if ($a == "%list" && $pid) {
				                                    	print "<div class=\"sel0\"><span class=\"division".$line['division']."\"><a href=\"/teams/".$line['char_id']."/\">".$line['team_name']."</a></span></div>";
				                                        print "<div class=\"small\" style=\"padding-left: 20px;\">";
				                                        $sql = "SELECT * FROM players WHERE id_team=$tid AND active=1 ORDER BY cap DESC, Number ASC";
				                                        $rst1 = $db->sql_query($sql);
				                                        while ($line1 = $db->sql_fetchrow($rst1)) {
				                                        	$name = $line1['Name']."&nbsp;".($line1['Surname']?$line1['Surname']:$line1['patronymic']);
				                                        	if ($line1['cap']) $name = "$name (к)";
				                                            if ($line1[id]==$pid)
				                                            	print "<div class=\"sel1\">$name</div>";
				                                            else
				                                            	print "<div class=\"sel0\"><span class=\"sex".$line1['id_sex']."\"><a href=\"/players/".$line1['id']."/\">$name</a></span></div>";
				                                        }
				                                        print "</div>";
				                                 	} elseif ($a == "%list" && !$pid) {
					                                   	print "<div class=\"sel1\"><b>".$line['team_name']."</b></div>";
				                                 	} else {
				                                    	print "<div class=\"sel1\"><b><a href=\"/teams/".$line['char_id']."/\">".$line['team_name']."</a></b></div>";
				                               		}
			                                    } else {
			                                    	print "<div class=\"sel0\"><span class=\"division".$line['division']."\"><a href=\"/teams/".$line['char_id']."/\">".$line['team_name']."</a></span></div>";
			                                    }
			                                }
			                   			?>
			                   				</td></tr>
			                   			</table>
			                   			</noindex>
			</td>
		</tr>
	</table>
<?
	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/footer.php";
?>
