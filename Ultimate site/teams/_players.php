<?
	$title = "Все игроки";
	if (isset($_REQUEST['aa'])) {
		$aa = $_REQUEST['aa'];
	if ($aa != "list")
		$aa = "faces";
	} else $aa = "faces";
	$meta_description = "Все игроки во фрисби (frisbee)";
	$meta_keywords = "игроки";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>

				<table cellspacing="0" cellpadding="15" border="0">
					<tr valign="top"><td>
            			<h1>Все игроки</h1>
            			<p>&nbsp;</p>
            			<div class="menu">
            			<?
            				if ($aa == "faces")
            					print "<span class=\"sel1\">Лица</span>&nbsp;&nbsp;&nbsp;&nbsp;";
            				else
            					print "<span class=\"sel0\"><a href=\"/players/\">Лица</a></span>&nbsp;&nbsp;&nbsp;&nbsp;";
            				if ($aa == "list")
            					print "<span class=\"sel1\">Простым списком</span>";
            				else
            					print "<span class=\"sel0\"><a href=\"/players/list/\">Простым списком</a></span>";
            			?>
            			</div>
            			<br /><p>
		                        <?php
		                        	$sql = "SELECT p.id as pid, p.*, d.division, t.team_name, t.char_id, c.city, c1.city AS city1, username
		                        		FROM players AS p
		                            	LEFT JOIN teams AS t ON p.id_team=t.id
		                                LEFT JOIN cities AS c ON t.id_city=c.id
		                                LEFT JOIN cities AS c1 ON p.id_city=c1.id
		                                LEFT JOIN divisions AS d ON t.id_division=d.id
		                                LEFT JOIN phpbb_users AS u ON p.id=u.user_id
		                                WHERE p.active=1 AND p.is_ukr=1 AND p.edited=1";
		                            $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
            						if ($aa == "list") {
                    					print "<table cellspacing=\"0\" cellpadding=\"5\" border=\"0\">";
			                            $players = array();
			                            while ($line = $db->sql_fetchrow($rst)) {
											$m_name = stripslashes(mycut($line['Surname']));
											$m_name .= ($line['Name']) ? " ".stripslashes(mycut($line['Name'])) : ($line['patronymic'] ? " ".stripslashes(mycut($line['patronymic'])):"");
											if (!$m_name) $m_name = $line['username'];
											$m_name = trim($m_name);
			                      			$players[$m_name] = $line;
			                            }
			                            ksort($players);
			                            $i=1;
			                            foreach ($players as $n=>$p) {
			                            	print "<tr valign=\"top\">";
			                            	print "<td align=\"right\"><p style=\"padding-top: 0;\">".$i++.".&nbsp;&nbsp;</p></td>";
			                            	print "<td><p style=\"padding-top: 0;\"><span class=\"sex".$p["id_sex"]."\"><a href=\"/players/".($p["p_char_id"]?$p["p_char_id"]:$p["pid"])."/\">$n</a></span></p></td>";
			                            	//print "<td><ul><li><a href=\"/teams/".$p["char_id"]."/\" class=\"".( $p['division']=="O" ? "openteam" : "womenteam")."\">".$p["team_name"]."</a></li></ul></td>";
			                            	print "<td><p style=\"padding-top: 0;\"><span class=\"division".$p['division']."\"><a href=\"/teams/".$p["char_id"]."/\">".$p["team_name"]."</a></span></p></td>";
			                            	print "<td><p style=\"padding-top: 0;\">".($p['id_team']?$p["city"]:$p["city1"])."</p></td>";
			                            	print "</tr>";
			                       		}
			                		} else {
			                			$parray = array();
					                    while ($line = $db->sql_fetchrow($rst))
					                        array_push($parray, $line);
										$col = 0;
										$size=sizeof($parray);
										$parr = array();
					                    for ($i=0; $i<$size; $i++) {
					                        list($usec, $sec) = explode(' ', microtime());
					                        srand((float) $sec + ((float) $usec * 100000));
					                        $k = rand(0, sizeof($parray)-1);
					                        $j=0;
					                        foreach ($parray as $key=>$value) {
					                            if ($j==$k) {
					                                $key1 = $key;
						                            $parr[($parray[$key1]["Photo"]?"0":"1").($parray[$key1]["id_team"]?"0":"1").sprintf("%06d",$i)]=$value;
					                                break;
					                            }
					                            $j++;
					                        }
				                        	unset($parray[$key1]);
					         			}
					         			ksort($parr);
					         			print "<div class=\"thumbnails\">";
					         			foreach ($parr as $p) {
					         				$url = "/players/".($p["p_char_id"]?$p["p_char_id"]:$p["pid"])."/";
					    					print "<ins class=\"thumbnail\">";
					        				print "<div class=\"p\">";
					        				print "<div style=\"float: left; padding-right: 15;\">";
					                        print "<a href=\"$url\"><img src=\"";
					                        print $p["Photo"] ? make_small_avatar("teams/photo",$p["Photo"],75,100) : "/teams/photo/nophoto_small.gif";
					                        print "\" style=\"border:none;\" /></a>";
					                        print "</div>";
											$m_name = stripslashes(mycut($p['Name']));
											$m_name .= ($p['Surname']) ? "<br />".stripslashes(mycut($p['Surname'])) : ($p['patronymic'] ? "<br />".stripslashes(mycut($p['patronymic'])):"");
											if (!$m_name) $m_name = $p['username'];
											print "<p style=\"padding-top: 0;\"><span class=\"sex".$p["id_sex"]."\"><a href=\"$url\">$m_name</a></span><br /><span class=\"small\">".$p["username"]."</span></p>";
											print $p["team_name"]? "<div class=\"division".$p["division"]."\"><p><a href=\"/teams/".$p["char_id"]."/\">".$p["team_name"]."</a></div>" : "";
											print "</div>";
											print "</ins>";
					         				/*
											if (!$col) print "<tr valign=\"top\">";

											print "<td align=\"right\" valign=\"top\" style=\"padding-right: 5\"><p>";
					                        print "<a href=\"/players/".$p["pid"]."/\"><img src=\"";
					                        print $p["Photo"] ? make_small_avatar("teams/photo",$p["Photo"],75,100) : "/teams/photo/nophoto_small.gif";
					                        print "\" style=\"border:none;\" /></a>";
					                        print "</p></td><td align=\"left\" valign=\"top\" style=\"padding-left: 5\">";
											print "<p><div class=\"small\"><span id=\"sex".$p["id_sex"]."\"><a href=\"/players/".$p["pid"]."/\"><b>".$p["Name"]."<br />".($p['Surname']?$p['Surname']:$p['patronymic'])."</b></a></span>".($p["Nick"]?"<br />".$p["Nick"]:"")."</div></p>";
											print $p["team_name"]? "<div id=\"division".$p["division"]."\"><p><a href=\"/teams/".$p["char_id"]."/\">".$p["team_name"]."</a></div>" : "";
											print "</p></td>";

											if ($col++ == 4) {
												print "</tr>";
												$col = 0;
											}
											*/
					                    }
			         				}
		                        ?>
    				</td></tr>
    			</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>
