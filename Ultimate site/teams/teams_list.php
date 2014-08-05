			                		<p>
			                			<noindex>
				                		<table cellspacing="0" cellpadding="0" border="0">
				                			<tr><td colspan="2"><p><br /></p><div class="vmenu" style="padding-right: 0;"><div class="sel0"><a href="/teams/">Все команды</a>:</div></div></td></tr>
				                			<tr><td width="70">
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
			                                		print "<tr valign=\"top\"><td><p>";
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
				                                        	//$name = $line1['Name']."&nbsp;".($line1['Surname']?$line1['Surname']:$line1['patronymic']);
				                                        	$name = NameSurname($line1['id']);
				                                        	if ($line1['cap']) $name = "$name&nbsp;(к)";
				                                            if ($line1[id]==$pid)
				                                            	print "<div class=\"sel1\">$name</div>";
				                                            else
				                                            	print "<div class=\"sel0\"><span class=\"sex".$line1['id_sex']."\"><a href=\"/players/".($line1['p_char_id']?$line1['p_char_id']:$line1['id'])."/\">$name</a></span></div>";
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
