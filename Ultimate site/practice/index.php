<?
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

/*
	if (isset($_GET['city'])) $city = $_GET['city']; else $city="minsk";

	$sql = "SELECT * FROM cities WHERE char_id='$city'";
	$rst = $db->sql_query($sql);
	if (!$db->sql_affectedrows($rst)) {
		//$sql = "SELECT * FROM cities WHERE char_id='minsk'";
		exit(); // 404 нада
	}
	$line = $db->sql_fetchrow($rst);
	$city_id = $line['id'];
	$city = $line['city'];

*/
    $title = "Где поиграть во фрисби?";
    $pg = "practice";
	$meta_description = $title;
	$meta_keywords = $title;
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>


<table width="100%" cellspacing="0" cellpadding="15" border="0">
	<tr valign="top">
		<td colspan="2">
			<center>
			<h1><!--Текст для анкеты<br />--><a id="anketa" class="pseudo_link clickable" href="#anketa">Заполните анкету</a></h1>
			<script>$("#anketa").bind("click", function(){ $("#DivAnketa").toggle(); });</script>
			<br />
			<p>
			<div id="DivAnketa">
				<table border="0" cellspacing="10" cellpadding="0" width="100%">
					<tr valign="top">
						<td colspan="2">
							<h4>Обязательно заполните эти поля:</h4>
						</td>
					</tr>
					<tr valign="top">
						<td><p style="text-align: right;">Имя</p></div></td>
						<td><p><input id="name" name="name" type="text" size="37"></p></td>
					</tr>
					<tr valign="top">
						<td><p style="text-align: right;">Город</p></td>
						<td><p><input id="city" name="city" type="text" size="37" value="<?=$city?>"></p></td>
					</tr>
					<tr valign="top">
						<td><p style="text-align: right;">Контактная информация</p></td>
						<td><p><input id="contact" name="contact" type="text" size="37"></p></td>
					</tr>
					<tr valign="top">
						<td colspan="2">
							<br /><br />
							<h4>Можете заполнить еще и эти поля:</h4>
						</td>
					</tr>
					<tr valign="top">
						<td><p style="text-align: right;">Возраст</p></td>
						<td><p><input id="age" name="age" type="text" size="37"></p></td>
					</tr>
					<tr valign="top">
						<td><p style="text-align: right;">Род занятий</p></td>
						<td><p><input id="occupation" name="occupation" type="text" size="37"></p></td>
					</tr>
					<tr valign="top">
						<td valign=top><p style="text-align: right;">Еще что-нибудь&nbsp;</td>
						<td><textarea rows="5" id="comments" name="comments" style="width:250px;"></textarea></td>
					</tr>
					<tr valign="top">
						<td colspan="2" align="center">
							<br /><button id="anketa_ok">Отправить информацию</button><p><span id="anketa_preloader"></span></p>
						</td>
					</tr>
				</table>
			</div>
			</p>
			</center>
		</td>
	</tr>
	<tr valign="top">
		<td>
			<?
				$r = $db->sql_query("SELECT * FROM practice_schedule");
				if ($db->sql_affectedrows($r)) {
					$l = $db->sql_fetchrow($r);
					print "<p>".parse_doc(mysql_escape_string($l["schedule"]))."</p>";
				}
			?>
		</td>
	</tr>
</table>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/footer.php";
?>