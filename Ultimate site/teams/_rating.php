<?
	$title = "Рейтинг команд";
	$meta_description = "Рейтинг белорусских команд по алтимат фрисби (ultimate frisbee)" . $all_teams;
	$meta_keywords = "рейтинг,команда".$all_teams;
	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/header.php";
?>


				<table cellspacing="0" cellpadding="15" border="0">
					<tr valign="top"><td>
            			<h1>Рейтинг</h1>
					    <p></p><table cellpadding="0" cellspacing="0" border="0">
					    	<tr valign="top">
					    		<td width="250">
			                    	<p></p><h4>Открытые команды</h4>
			                    	<ol>
			                        <?php
			                            $sql = "SELECT t.*, t.id AS tid, c.city, d.division
			                                FROM players AS p
			                                LEFT JOIN teams AS t ON p.id_team=t.id
			                                LEFT JOIN cities AS c ON t.id_city=c.id
			                                LEFT JOIN divisions AS d ON t.id_division=d.id
			                                WHERE p.active=1 AND t.team_active=1 AND t.team_alive=1 AND t.team_inrating=1
			                                GROUP BY team_name
			                                ORDER BY d.division ASC, t.team_rating DESC";
			                            $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
			                            $division = "O";
			                            while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
			                            	if ($line['division']) {
				                                if ($line['division']!=$division) {
				                                    print "</ol></td><td width=\"250\"><p></p><h4>Женские команды</h4><ol>";
				                                    $division = $line['division'];
				                                }
				                                $team_id = $line['tid'];
				                                print "<li>";
				                                //print "<h5><a href=\"/teams/".$line['char_id']."/\" class=\"".( $line['division']=="O" ? "openteam" : "womenteam")."\">".$line['team_name']."</a> ".$rating[$team_id]["diff"]." </h5><div class=\"small\">".$line['city']."</div></li>";
				                                print "<h3><span class=\"division".$line['division']."\"><a href=\"/teams/".$line['char_id']."/\">".$line['team_name']."</a></span> ".$rating[$team_id]["diff"]."</h3><div class=\"small\">".$line['city']."</div><br /></li>";
				                    		}
			                            }
			                        ?>
			                        </ol>
			                    </td>
			                    <td width="300">
			                        <p>
			                        <div class="subtitle">
			                        Текущий зальный рейтинг белорусских команд построен по&nbsp;результатам следующих турниров:
			                        <ul>
				                        <? /* <li><a href="/tourn/minskweekend07/">Минский weekend 2007</a></li>
				                        <li><a href="/tourn/belin07/">Закрытие сезона (зал) 2007</a></li> */ ?>
				                        <li><a href="/tourn/brest0812/">Турнир в Бресте</a></li>
				                        <li><a href="/tourn/minsk09/">Минск 2009</a></li>
				                        <li><a href="/tourn/belin09/">Закрытие зального сезона 2008&ndash;2009</a></li>
				            		</ul>
			                        </div>
			                    </td>
			                </tr>
		                </table>
		                <p><br /><br /></p>
    		    		<? include "rating_history.php"; ?>
    				</td></tr>
    			</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/footer.php";
?>
