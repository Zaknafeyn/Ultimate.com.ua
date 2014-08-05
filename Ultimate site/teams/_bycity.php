<?
	$title = "Команды";
	$meta_description = "Украинские команды по алтимат фризби (ultimate frisbee)" . $all_teams;
	$meta_keywords = "команда".$all_teams;
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>

               	<table cellspacing="0" cellpadding="15" border="0" width="100%">
               		<tr valign="top">
               			<td>
               				<h1>Карта алтимата в&nbsp;Украине</h1>
               				<p>
               				<?
               					$r = $db->sql_query("SELECT t.char_id, team_name, city, division
               						FROM teams t
               						LEFT JOIN cities c ON id_city=c.id
               						LEFT JOIN divisions d ON id_division=d.id
               						WHERE active=1 ORDER BY id_city");
               					$teams = array();
               					if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) {
               						$city = stripslashes($l['city']);
               						if (!isset($teams[$city])) $teams[$city] = array();
               						array_push($teams[$city],"<span class=\"division".$l['division']."\"><a href=\"/teams/".$l['char_id']."/\">".$l['team_name']."</a></span>");
               					}
               					foreach($teams as $city=>$t) {
               						if (sizeof($t)) {
               							print "<b>$city</b>: ".implode(", ",$t)."<br />";
               						}
               					}

               				?>
               				</p>
               				<br />
               				<p>
               				<div class="small">Метки на карте кликабельны</div>
               				<p>
               					<iframe width="850" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.ru/maps/ms?ie=UTF8&amp;hl=ru&amp;msa=0&amp;msid=102685815672191836682.00046e921edba1797c02f&amp;ll=48.531157,31.157227&amp;spn=8.732671,18.676758&amp;z=6&amp;output=embed"></iframe><br /><small>Просмотреть <a href="http://maps.google.ru/maps/ms?ie=UTF8&amp;hl=ru&amp;msa=0&amp;msid=102685815672191836682.00046e921edba1797c02f&amp;ll=48.531157,31.157227&amp;spn=8.732671,18.676758&amp;z=6&amp;source=embed">алтимат в&nbsp;Украине</a> на&nbsp;карте большего размера</small>
               				</p>
               				<br />
               				<p>
               				<h5>Обозначения на&nbsp;карте</h5>
               				<table cellspacing="0" cellpadding="0" border="0" width="100%">
               					<tr valign="top">
               						<td style="padding-right: 15;" width="25%">
               							<table cellspacing="0" cellpadding="0" border="0" width="100%">
               								<tr valign="top">
               									<td style="padding-right: 15;"><p><img src="/MY/Mapicons/11.png" /></p></td>
               									<td>
               										<p><div class="small">
               											<b>Продвинутый алтимат-город.</b> В&nbsp;этих городах есть 2 или более команды УФЛД. В&nbsp;городе проходят турниры федерации.
               										<div class="small"></p>
               									</td>
               								</tr>
               							</table>
               						</td>
               						<td style="padding-right: 15;" width="25%">
               							<table cellspacing="0" cellpadding="0" border="0" width="100%">
               								<tr valign="top">
               									<td style="padding-right: 15;"><p><img src="/MY/Mapicons/2.png" /></p></td>
               									<td>
               										<p><div class="small">
               											<b>В&nbsp;городе есть команда</b>, которая зарегистрирована Украинской Федерацией Летающих Дисков.
               										</div></p>
               									</td>
               								</tr>
               							</table>
               						</td>
               						<td style="padding-right: 15;" width="25%">
               							<table cellspacing="0" cellpadding="0" border="0" width="100%">
               								<tr valign="top">
               									<td style="padding-right: 15;"><p><img src="/MY/Mapicons/3.png" /></p></td>
               									<td>
               										<p><div class="small">
               											<b>В&nbsp;городе есть группа людей, которые играют в&nbsp;алтимат.</b> Кликай на&nbsp;иконку, чтобы узнать, как присоединиться к&nbsp;ним.
               										</div></p>
               									</td>
               								</tr>
               							</table>
               						</td>
               						<td style="padding-right: 15;" width="25%">
               							<table cellspacing="0" cellpadding="0" border="0" width="100%">
               								<tr valign="top">
               									<td style="padding-right: 15;"><p><img src="/MY/Mapicons/4.png" /></p></td>
               									<td>
               										<p><div class="small">
               											<b>В&nbsp;городе есть люди, которые интересуются фризби.</b> С&nbsp;ними можно контактировать с&nbsp;целью организации новой команды.
               										</div></p>
               									</td>
               								</tr>
               							</table>
               						</td>
               					</tr>
               				</table>
               			</td>
                  	</tr>
               	</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>
