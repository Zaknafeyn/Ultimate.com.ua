<?
	$max_upload_file_size = 50*1024;
	$max_upload_file_width = 310;
	$max_upload_file_height = 1000;


    if (isset($_REQUEST['pid'])) {
	  	if (isset($_POST['pid'])) {
	    	$pid = digits_only($_POST["pid"]);
	  	} elseif (isset($_GET['pid'])) {
	    	$p_char_id = preg_replace("/[^0-9a-z\-_\.]/","",strtolower($_GET['pid']));
	    	$r = $db->sql_query("SELECT id FROM players WHERE p_char_id='$p_char_id'");
	    	if ($db->sql_affectedrows($r)) {
	    		$l = $db->sql_fetchrow($r);
	    		$pid = $l['id'];
	    	} else {
	    		$pid = digits_only($p_char_id);
	    		if ($pid) {
			    	$r = $db->sql_query("SELECT p_char_id FROM players WHERE id=$pid");
			    	if ($db->sql_affectedrows($r)) {
			    		$l = $db->sql_fetchrow($r);
			    		if ($l['p_char_id']) {
				    		header("location:/players/".$l['p_char_id']."/".($_REQUEST['mode']=="edit"?"edit":""));
				    		exit;
						}
			    	}
	    		}
	    	}
	  	}
    	if ($pid=='') $pid=0;

    	$rst = $db->sql_query("SELECT Name, Surname, patronymic, cap, Nick, Team, t.id AS tid, char_id, team_name, division, username
    		FROM players AS p
    		LEFT JOIN phpbb_users AS u ON p.id=u.user_id
    		LEFT JOIN teams AS t ON p.id_team=t.id
    		LEFT JOIN divisions AS d ON t.id_division=d.id
    		WHERE p.id=$pid");
    	if ($db->sql_affectedrows($rst)) {
    		$line = $db->sql_fetchrow($rst);
    		$cap = $line['cap'];
	        $tid = $line['tid'];
		    $team_name = $line['team_name']?$line['team_name']:stripslashes(mycut($line['Team']));
		    $char_id = $line['char_id'];
		    $division = $line['division'];
		    //$title = $team_name;
			$fullname = stripslashes(mycut($line['Name'])).($line['Surname']?" ".stripslashes(mycut($line['Surname'])):($line['patronymic']?" ".stripslashes(mycut($line['patronymic'])):""));
			if (!$fullname) $fullname = ($line['Nick']?stripslashes(mycut($line['Nick'])):$line['username']);
			$meta_description = $fullname." ".($cap?"капитан":"игрок") . " команды " . $team_name . " ". stripslashes(mycut($line['Nick']));
			$meta_keywords = $fullname.",".($cap?"капитан,":"игрок,") . $team_name . "," . stripslashes(mycut($line['Nick']));
    		$title = $fullname;
    	}
    	$a = "%list";
    } else {
    	$pid = 0;
    }

    $edit_mode = false;
    if (isset($_REQUEST['mode']))
    	if ($_REQUEST['mode']=="edit")
       		if ($rights['all_rights'] || $rights['team_'.$line['tid'].'_edit'] || $userdata['user_id']==$pid)
    			$edit_mode = true;

	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/header.php";

    if ($pid) {

	        $updated = false;
	        if ($edit_mode && isset($_POST['name']) && $userdata['session_logged_in']) {
	            $sql = "SELECT * FROM players WHERE id=$pid";
	            $rst = $db->sql_query($sql) or die ("<p>$sql<p>".mysql_error());
	            if ($db->sql_affectedrows($rst)) {
                    $line = $db->sql_fetchrow($rst, MYSQL_ASSOC);
            		if ($rights['all_rights'] || $rights['team_'.$line['id_team'].'_edit'] || $userdata['user_id']==$pid) {
	                    $edit_mode = true;

	                    // загружаем фотку
	                    $photo_error = "";
	                    if (isset($_FILES["fname"]))
	                        if ( preg_match('/\.(jpg|jpeg|gif|png)$/i', $_FILES["fname"]["name"]) ) {
	                            if (preg_match('#image\/[x\-]*([a-z]+)#', $_FILES["fname"]["type"], $filetype)) {
	                                //if ($_FILES["fname"]["size"] <= $max_upload_file_size) {
	                                    list($width, $height) = @getimagesize($_FILES["fname"]["tmp_name"]);
                                        $img_type = $filetype[1];
                                        $photo_fname = uniqid(rand());
                                        $m_photo = $photo_fname.".".$img_type;
                                        $m_photo_small = $photo_fname."_small.".$img_type;
                                        $fname = $_FILES["fname"]["tmp_name"];
                                        if (move_uploaded_file($fname,"photo/$m_photo")) {
                                            @chmod("photo/$m_photo", 0644);
	                                    	if ($width>$max_upload_file_width || $height>$max_upload_file_height ) {
							                    imageresize($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$m_photo", $_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$m_photo", $max_upload_file_width, $max_upload_file_height, 85);
								                chmod("photo/$m_photo", 0644);
	                                  		}
						                    //imageresize($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$m_photo_small", $_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$m_photo", 75, 100, 85);
							                //chmod("photo/$m_photo_small", 0644);
                                            $sql11 = "UPDATE players SET Photo='$m_photo', Photo_small='$m_photo_small' WHERE id=$pid";
                                            $rst = $db->sql_query($sql11) or die("<p>$sql<p>".mysql_error());
                                            $photo_error='';
                                        } else {
                                            $photo_error = "Ошибка загрузки файла ".$_FILES["fname"]["name"]."!";
                                        }
	                                //} else {
	                                //    $photo_error = "Файл слишком большой!";
	                                //}
	                            }
	                        }

	                    if (isset($_POST['surname']))     { $surname = mysql_escape_string($_POST['surname']); } else { $surname = ''; }
	                    if (isset($_POST['name']))        { $name = mysql_escape_string($_POST['name']); } else { $name = ''; }
	                    if (isset($_POST['patronymic']))  { $patronymic = mysql_escape_string($_POST['patronymic']); } else { $patronymic = ''; }
	                    if (isset($_POST['nick']))        { $nick = mysql_escape_string($_POST['nick']); } else { $nick = ''; }
	                    if (isset($_POST['number']))      { $number = mysql_escape_string($_POST['number']); } else { $number = ''; }
	                    if (isset($_POST['city']))        { $city = mysql_escape_string($_POST['city']); } else { $city = ''; }
	                    if (isset($_POST['bd']))          { $bd = mysql_escape_string($_POST['bd']); } else { $bd = ''; }
	                    if (isset($_POST['started']))     { $started = mysql_escape_string($_POST['started']); } else { $started = ''; }
	                    if (isset($_POST['prof']))        { $prof = mysql_escape_string($_POST['prof']); } else { $prof = ''; }
	                    if (isset($_POST['email']))       { $email = mysql_escape_string($_POST['email']); } else { $email = ''; }


	                    if (isset($_POST['year_started_playing']))     { $year_started_playing = digits_only($_POST['year_started_playing']); } else { $started = date("Y"); }

	                    if (isset($_POST['icq']))         { $icq = mysql_escape_string($_POST['icq']); } else { $icq = ''; }

	                    if (isset($_POST['site']))        { $site = mysql_escape_string($_POST['site']); } else { $site = ''; }
	                    if (isset($_POST['skype']))       { $skype = mysql_escape_string($_POST['skype']); } else { $skype = ''; }

	                    if (isset($_POST['twitter']))     { $twitter = preg_replace("/[^0-9a-z_]/i","",$_POST['twitter']); } else { $twitter = ''; }

	                    if (isset($_POST['about']))       { $about = mysql_escape_string($_POST['about']); } else { $about = ''; }
	                    if (isset($_POST['id_team']))     { $id_team = digits_only($_POST['id_team']); } else { $id_team = 0; }
	                    if (isset($_POST['team']))     	  { $team = mysql_escape_string($_POST['team']); }
	                    if (isset($_POST['id_city']))     { $id_city = digits_only($_POST['id_city']); } else { $id_city = 0; }
	                    if (isset($_POST['city']))     	  { $city = mysql_escape_string($_POST['city']); }
	                    if (isset($_POST['country']))     { $country = mysql_escape_string($_POST['country']); } else { $country = ''; }

	                    if (isset($_POST['c_vkontakte']))	{ $c_vkontakte = preg_replace("/[^0-9]/i","",$_POST['c_vkontakte']); } else { $c_vkontakte = ''; }
	                    if (isset($_POST['c_lj']))			{ $c_lj = preg_replace("/[^0-9a-z_\-]/i","",$_POST['c_lj']); } else { $c_lj = ''; }
	                    if (isset($_POST['c_moikrug']))		{ $c_moikrug = preg_replace("/[^0-9a-z_\.]/i","",$_POST['c_moikrug']); } else { $c_moikrug = ''; }
	                    if (isset($_POST['c_odnokl']))		{ $c_odnokl = preg_replace("/[^0-9]/i","",$_POST['c_odnokl']); } else { $c_odnokl = ''; }
	                    if (isset($_POST['c_opa']))			{ $c_opa = preg_replace("/[^0-9a-z_\-\.]/i","",$_POST['c_opa']); } else { $c_opa = ''; }
	                    if (isset($_POST['c_facebook']))		{ $c_facebook = preg_replace("/[^0-9a-z_\-\.]/i","",$_POST['c_facebook']); } else { $c_facebook = ''; }

	                    if (isset($_POST['homeaddress']))   { $homeaddress = mysql_escape_string($_POST['homeaddress']); } else { $homeaddress = ''; }
	                    if (isset($_POST['workaddress']))   { $workaddress = mysql_escape_string($_POST['workaddress']); } else { $workaddress = ''; }
	                    if (isset($_POST['cellphone']))     { $cellphone = mysql_escape_string($_POST['cellphone']); } else { $cellphone = ''; }
	                    if (isset($_POST['homephone']))     { $homephone = mysql_escape_string($_POST['homephone']); } else { $homephone = ''; }
	                    if (isset($_POST['workphone']))     { $workphone = mysql_escape_string($_POST['workphone']); } else { $workphone = ''; }

	                    if (isset($_POST['height']))        { $height = mysql_escape_string($_POST['height']); } else { $height = ''; }
	                    if (isset($_POST['weight']))        { $weight = mysql_escape_string($_POST['weight']); } else { $weight = ''; }
	                    if (isset($_POST['tshirtsize']))    { $tshirtsize = mysql_escape_string($_POST['tshirtsize']); } else { $tshirtsize = ''; }

	                    if (isset($_POST['interests'])) { $interests = mysql_escape_string($_POST['interests']); } else { $interests = ''; }

	                    if (isset($_POST['cap'])) { $cap = 1; } else { $cap = 0; }

	                    if (isset($_POST['sex'])) { $sex = mysql_escape_string($_POST['sex']); } else { $sex = 1; }

	                    $sql = "SELECT * FROM tourn ORDER by dat_begin";
	                    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	                    $tourn = "";
	                    while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
	                        $tourn1 = "";
	                        $tourn1 = $_REQUEST["tourn".$line['id']];
	                        if ($tourn1!="") {
	                            if ($tourn!="") $tourn .= ";";
	                            $tourn .= $tourn1;
	                        }
	                    }

						// $player0 - данные до редактирования
						$r = $db->sql_query("SELECT * FROM players WHERE id=$pid");
						$player0 = $db->sql_fetchrow($r);

	                    //if (isset($_POST['id_team'])) {
	                    	if ($userdata['user_id']!=2) $db->sql_query("UPDATE players SET edited=1 WHERE id=$pid LIMIT 1");

	                        $sql = "UPDATE players SET
	                            Surname='$surname', Name='$name', patronymic='$patronymic', Nick='$nick', Number='$number',
	                            id_team=$id_team, Team='$team', City='$city', country='$country', BD='$bd', started='$started', prof='$prof',
	                            email='$email', icq='$icq', site='$site', skype='$skype', twitter='$twitter', about='$about', tournaments='$tourn',
	                            homeaddress='$homeaddress', workaddress='$workaddress', cellphone='$cellphone', homephone='$homephone', workphone='$workphone',
	                            height='$height', weight='$weight', tshirtsize='$tshirtsize',
	                            interests='$interests', cap=$cap, id_city=$id_city,
	                            c_vkontakte='$c_vkontakte', c_lj='$c_lj', c_moikrug='$c_moikrug', c_odnokl='$c_odnokl', c_opa='$c_opa', c_facebook='$c_facebook',
	                            id_sex=$sex, year_started_playing=$year_started_playing
	                            WHERE id=$pid";
	                        $rst = $db->sql_query($sql) or die ("<p>$sql<p>".mysql_error());
	                        $updated = true;

						// $player1 - данные после редактирования
						$r = $db->sql_query("SELECT * FROM players WHERE id=$pid");
						$player1 = $db->sql_fetchrow($r);

						// сравниваем что изменилось
						foreach($player1 as $k=>$v) {
							if ($player0[$k]!=$v) {
								$mail_text .= "<b>$k</b>: <span style=\"color: blue;\">$v</span> (".$player0[$k].")<br />";
							}
						}

							$url = "http://ultimate.com.ua/players/".($p_char_id?$p_char_id:$pid)."/";
							$mail_subj = "ua: ".NameSurname($db, $pid).($pid==$userdata['user_id']?"":" (".$userdata['user_id'].")");
							$mail_text = "<html><body><p><a href=\"$url\">$url</a></p><p>$mail_text</p></body></html>";
							//die($mail_text);
							mail("frisbee@tut.by",iconv("WINDOWS-1251","UTF-8",$mail_subj),$mail_text,"Content-type: text/html;\r\nFrom: ultimate.com.ua <admin@ultimate.com.ua>");
	                    //}
	                }
                }
	        }

    }
?>

	<style>
		.info_header	{ padding: 5; margin-left: -5; font-size: 12px; background-color: #F0F0F0; border-top: 1px solid #E0E0E0; }
		.info_par		{ padding-top: 5; font-size: 12px; color: #AAAAAA; }
		.info_val		{ padding-top: 5; font-size: 12px; font-weight: bold; }
	    .plname         { color: black; font-weight: bold; font-size: 16px }
	    .plnick         { font-size: 20px; ; font-family: Arial }
	    .plcat         	{ font-size: 12px; }
	    .plinfo         { font-size: 12px; font-weight: bold; }
	    .plteam         { color: #444444; font-weight: bold; font-size: 14px; }
	    .plabout        { color: #444444; font-size: 12px; padding-top: 15px; }
	    .pltourn        { color: #444444; font-size: 12px; }
	</style>

            		<?
            			if (!$edit_mode) {
            		?>
							<div>
								<div style="float: right; width: auto; height: 100%; padding: 0 0 30 5;">
									<noindex>
			                		<p>
				                		<table cellspacing="0" cellpadding="0" border="0">
				                			<tr><td>
	                		<?
                                $sql = "SELECT COUNT(p.id) AS cnt, t.id AS tid, t.char_id, t.team_name,
                                    c.city, d.division
                                    FROM players AS p
                                    LEFT JOIN teams AS t ON p.id_team=t.id
                                    LEFT JOIN cities AS c ON t.id_city=c.id
                                    LEFT JOIN divisions AS d ON t.id_division=d.id
                                    WHERE t.id<>0 AND t.team_contacts<>''
                                    GROUP BY tid ORDER BY c.id ASC, d.division ASC, team_rating  DESC";
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
	                                        $sql = "SELECT * FROM players AS p LEFT JOIN phpbb_users AS u ON p.id=u.user_id WHERE id_team=$tid AND active=1 ORDER BY Number";
	                                        $rst1 = $db->sql_query($sql);
	                                        while ($line1 = $db->sql_fetchrow($rst1)) {
	                                        	$name = stripslashes(mycut($line1['Name']));
	                                        	if ($line1['Surname'])
	                                        		$name .= "&nbsp;".stripslashes(mycut($line1['Surname']));
	                                       		elseif ($line1['patronymic'])
	                                        		$name .= "&nbsp;".stripslashes(mycut($line1['patronymic']));
	                                       		if (!$name) $name = ($line1['Nick']?stripslashes(mycut($line1['Nick'])):stripslashes($line1['username']));
	                                        	if ($line1['cap']) $name .= "&nbsp;(к)";
	                                            if ($line1['id']==$pid)
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
				                <tr><td colspan="2"><p><br /></p><div class="vmenu" style="padding-right: 0"><div class="sel0"><a href="/teams/">Все команды</a></div></div></td></tr>
                   			</table>
					</noindex>
                   			</div>

            				<div style="width: auto; overflow: hidden;">
                                            <?php
                                                    $sql ="SELECT
                                                        p.*, t.char_id,
                                                        count(c.id) as comments,
                                                        username
                                                        FROM players AS p
                                                        LEFT JOIN teams AS t ON p.id_team=t.id
                                                        LEFT JOIN comments AS c ON p.id=c.id_item AND id_category=".MY_PLAYERS."
                                                        LEFT JOIN phpbb_users AS u ON p.id=u.user_id
                                                        WHERE p.id=$pid
                                                        GROUP BY id_item";
                                                    $rst = $db->sql_query($sql) or die($sql."<p>".mysql_error());
                                                    if ($db->sql_affectedrows($rst)) {

                                                        $line = $db->sql_fetchrow($rst);

                                                        $m_id = $line['id'];
                                                        $m_p_char_id = stripslashes($line['p_char_id']);
                                                        $m_name = stripslashes(mycut($line['Name']));
                                                        $m_patronymic = $line['patronymic'] ? "&nbsp;".stripslashes(mycut($line['patronymic'])) : "";
                                                        $m_surname = $line['Surname'] ? "&nbsp;".stripslashes(mycut($line['Surname'])) : "";
                                                        $m_nick = stripslashes(mycut($line['Nick']));
                                                        $m_fullname = $m_name.($m_surname?$m_surname:$m_patronymic);
                                                        $forum_user = stripslashes("<a href=\"/f/profile.php?mode=viewprofile&u=".$line['id']."\">".$line['username']."</a>");
                                                        if (!$m_fullname) $m_fullname = ($m_nick ? $m_nick : $line['username']);
                                                        $m_number = $line['Number'] ? "#".stripslashes($line['Number'])."&nbsp;" : "";
                                                        $m_photo = $line['Photo'] ? "<img src=\"/teams/photo/".stripslashes($line['Photo'])."\" style=\"border: none\" alt=\"$m_fullname\" title=\"$m_fullname\" >" : "<img src=\"/teams/photo/nophoto.gif\" width=150 height=200 style=\"border: none\" alt=\"$m_fullname\" title=\"$m_fullname\" >";
                                                        $m_team = stripslashes(mycut($line['team_name']?$line['team_name']:$line['Team']));
                                                        $m_city = stripslashes(mycut($line['City']));
                                                        $m_country = stripslashes(mycut($line['country']));
                                                        $m_bd = stripslashes(mycut($line['BD']));
                                                        $m_started = stripslashes(mycut($line['started']));
                                                        $m_prof = stripslashes(mycut($line['prof']));
                                                        $m_email = $line['email'] ? secure_email(mycut(stripslashes($line['email']))) : "";
                                                        $m_icq = stripslashes(mycut($line['icq']));
                                                        $m_site = stripslashes(mycut($line['site']));
                                                        $m_site = preg_replace("/(http:\/\/[a-z0-9\.~\?\&\/_\-]*)/i","<a href=\\1>\\1</a>",stripslashes($m_site));
                                                        $m_skype = stripslashes(mycut($line['skype']));
                                                        $m_twitter = stripslashes(mycut($line['twitter']));
                                                        $m_about = stripslashes(mycut($line['about']));
                                                        $m_homeaddress = stripslashes(mycut($line['homeaddress']));
                                                        $m_workaddress = stripslashes(mycut($line['workaddress']));
                                                        $m_cellphone = stripslashes(mycut($line['cellphone']));
                                                        $m_homephone = stripslashes(mycut($line['homephone']));
                                                        $m_workphone = stripslashes(mycut($line['workphone']));
                                                        $m_height = stripslashes(mycut($line['height']));
                                                        $m_weight = stripslashes(mycut($line['weight']));
                                                        $m_tshirtsize = stripslashes(mycut($line['tshirtsize']));
                                                        $m_interests = stripslashes(mycut($line['interests']));
                                                        $m_tournaments = stripslashes($line['tournaments']);
                                                        $comments  = $line['comments'] ? "<a href=\"/teams/".stripslashes($line['char_id'])."/$m_id/#com\">Комментариев (".$line['comments'].")</a>" : "<a href=\"/teams/".stripslashes($line['char_id'])."/$m_id/#addcom\">Есть что сказать<br />об этом игроке?</a>";
                                                        $m_c_vkontakte = stripslashes($line['c_vkontakte']);
                                                        $m_c_lj = stripslashes($line['c_lj']);
                                                        $m_c_moikrug = stripslashes($line['c_moikrug']);
                                                        $m_c_odnokl = stripslashes($line['c_odnokl']);
                                                        $m_c_opa = stripslashes($line['c_opa']);
                                                        $m_c_facebook = stripslashes($line['c_facebook']);
                                                        $m_sex = stripslashes($line['id_sex']);
                                                        $m_is_ukr = stripslashes($line['is_ukr']);

                                            			print "<table cellspacing=\"0\" cellpadding=\"15\" border=\"0\">";
                                                        print "<tr><td colspan=\"2\">";
                                                        print "<h1>$m_fullname</h1>";
                                                        print "<p style=\"padding-top: 5;\"><div class=\"small\">";
                                                        if ($m_is_ukr) {
                                                        	print ($tid ? ($cap ? "Капитан" : "Игрок"). ($tid ? " команды <span class=\"division$division\"><a href=\"/teams/$char_id/players/\">$team_name</a></span>" : ($team_name?" команды $team_name":"")) : "<span class=\"division$division\">".$team_name."</span>");
                                                        } else {
                                                        	if ($team_name)
                                                        		print ($cap ? "Капитан" : "Игрок")." команды <strong>$team_name</strong>";
                                                        	if ($m_city||$m_country) print "<br />".($m_city&&$m_country?$m_city.", ".$m_country:$m_city.$m_country);
                                                        }
                                                        print "</div>";
                                                        print "</td></tr>";

                                                        print "<tr valign=\"top\">";
                                                        print "<td>$m_photo";

                                                        $r = $db->sql_query("SELECT * FROM photo_marks m
                                                        	LEFT JOIN photo p ON m.photo_id=p.id
                                                        	LEFT JOIN photo_albums a ON p.id_album=a.id
                                                        	WHERE m.user_id=$pid");
                                                        if ($db->sql_affectedrows($r)) {
                                                        	// а выведу-ка еще пару-тройку рандомных фоток
                                                        	$up = array();
															while ($l = $db->sql_fetchrow($r))
																array_push($up, $l);
                                                        	print "<br /><br /><p><div class=\"small\"><a href=\"/players/".($p_char_id?$p_char_id:$pid)."/photo/\">На&nbsp;фотографиях</a>:</div></p>";
                                                        	$size = sizeof($up);
										                    for ($i=0; $i<min(3,$size); $i++) {
										                        list($usec, $sec) = explode(' ', microtime());
										                        srand((float) $sec + ((float) $usec * 100000));
										                        $k = rand(0, sizeof($up)-1);
										                        $j=0;
										                        foreach ($up as $key=>$value) {
										                            if ($j==$k) {
										                                $key1 = $key;
																    	$path = "photo/albums/".GetPath($db, $value['id_album'])."/small";
																    	$fname = $value['fname'];
																    	$url = "/photo/".GetPath($db, $value['id_album'])."/-".$value['photo_id']."#o0";
																    	print "<a href=\"$url\"><img style=\"border: none\" src=\"".make_small_avatar($path,$fname)."\" /></a>&nbsp;";
										                                break;
										                            }
										                            $j++;
										                        }
									                        	unset($up[$key1]);
										         			}
                                                        	print "</p>";
                                                        	print ($size>3) ? "<div class=\"small\"><a href=\"/players/".($p_char_id?$p_char_id:$pid)."/photo/\">Всего ".$size."&nbsp;".num_decline($size,"фотография","фотографии","фотографий")."</a></div></p>":"";
                                                        	//print "</div>";
                                                        }


                                                        // номер и ник надо где-то выводить


									                    if ( ($userdata['session_logged_in'] && $m_id == $userdata['user_id']) || $rights['all_rights'] || $rights['team_'.$line['id_team'].'_edit'] )
                                                        	print "<p><div class=\"small\">[ <a href=\"/players/".($m_p_char_id?$m_p_char_id:$m_id)."/edit\">Редактировать</a> ]</div></p>";
                                                        print "</td>";
                                                        print "<td width=\"380\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">";
														print "<tr valign=\"top\"><td style=\"padding-left: 10\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">";
                                                    	if (($m_city&&$m_is_ukr) || $m_bd || $m_interests || $m_prof || $m_started) {
                                                    		print "<tr><td colspan=\"2\"><div class=\"info_header\">Общая информация</div></td></tr>";
	                                                        print ($m_city&&$m_is_ukr) ? "<tr><td><div class=\"info_par\">Родной&nbsp;город</div></td><td><div class=\"info_val\">$m_city</div></td></tr>" : "";
	                                                        print ($m_bd) ? "<tr><td><div class=\"info_par\">День&nbsp;рождения</div></td><td><div class=\"info_val\">$m_bd</div></td></tr>" : "";
	                                                        print ($m_started)  ? "<tr valign=\"top\"><td><div class=\"info_par\">Играю&nbsp;с</div></td><td><div class=\"info_val\">$m_started</div></td></tr>" : "";
	                                                        print ($m_prof)     ? "<tr valign=\"top\"><td><div class=\"info_par\">Род&nbsp;занятий</div></td><td><div class=\"info_val\">$m_prof</div></td></tr>" : "";
	                                                        print ($m_interests) ? "<tr valign=\"top\"><td><div class=\"info_par\">Интересы</div></td><td><div class=\"info_val\">$m_interests</div></td></tr>" : "";
	                                                        print "<tr><td><p><br /></p></td></tr>";
                                                        }
		                                                //if ($m_homeaddress||$m_workaddress||$m_homephone||$m_cellphone||$m_workphone||$m_email||$m_icq||$m_skype||$m_site) {
                                                    		print "<tr><td colspan=\"2\"><div class=\"info_header\">Контактная информация</div></td></tr>";
	                                                    	print "<tr><td width=\"120\"><div class=\"info_par\">На&nbsp;форуме</div></td><td><div class=\"info_val\">$forum_user</span></td></tr>";
	                                                        print ($m_email)    ? "<tr valign=\"top\"><td><div class=\"info_par\">e-mail</div></td><td><div class=\"info_val\">$m_email</div></td></tr>" : "";
	                                                        print ($m_icq)      ? "<tr><td><div class=\"info_par\">ICQ</div></td><td><div class=\"info_val\">$m_icq</div></td></tr>" : "";
	                                                        print ($m_site)     ? "<tr><td><div class=\"info_par\">Сайт</div></td><td><div class=\"info_val\">$m_site</div></td></tr>" : "";
	                                                        print ($m_skype)    ? "<tr><td><div class=\"info_par\">Скайп</div></td><td><div class=\"info_val\">$m_skype</div></td></tr>" : "";
	                                                        print ($m_cellphone)? "<tr><td><div class=\"info_par\">Мобильный&nbsp;телефон</div></td><td><div class=\"info_val\">$m_cellphone</div></td></tr>" : "";
	                                                        print ($m_homephone)? "<tr><td><div class=\"info_par\">Домашний&nbsp;телефон</div></td><td><div class=\"info_val\">$m_homephone</div></td></tr>" : "";
	                                                        print ($m_workphone)? "<tr><td><div class=\"info_par\">Рабочий&nbsp;телефон</div></td><td><div class=\"info_val\">$m_workphone</div></td></tr>" : "";
	                                                        print ($m_homeaddress)? "<tr><td><div class=\"info_par\">Домашний&nbsp;адрес</div></td><td><div class=\"info_val\">$m_homeaddress</div></td></tr>" : "";
	                                                        print ($m_workaddress)? "<tr><td><div class=\"info_par\">Рабочий&nbsp;адрес</div></td><td><div class=\"info_val\">$m_workaddress</div></td></tr>" : "";
	                                                        if ($m_c_vkontakte || $m_c_lj || $m_c_moikrug || $m_c_odnokl || $m_c_opa) {
	                                                        	print "<tr><td colspan=\"2\">";
	                                                        	print ($m_c_vkontakte) ? "<a href=\"http://vkontakte.ru/id".$m_c_vkontakte."\"><span class=\"info_par\"><img src=\"/img/c_vkontakte.gif\" height=\"22\" width=\"23\" vspace=\"10\" style=\"border: none;\" alt=\"Я ВКонтакте.ру\" /></a></span>&nbsp;&nbsp;&nbsp;" : "";
	                                                        	print ($m_c_facebook) ? "<a href=\"http://facebook.com/".$m_c_facebook."\"><span class=\"info_par\"><img src=\"/img/c_facebook.png\" height=\"22\" width=\"22\" vspace=\"10\" style=\"border: none;\" alt=\"facebook\" /></a></span>&nbsp;&nbsp;&nbsp;" : "";
	                                                        	print ($m_c_lj) ? "<a href=\"http://".$m_c_lj.".livejournal.com\"><span class=\"info_par\"><img src=\"/img/c_lj.gif\" height=\"22\" width=\"22\" vspace=\"10\" style=\"border: none;\" alt=\"Мой Живой Журнал\" /></a></span>&nbsp;&nbsp;&nbsp;" : "";
	                                                        	print ($m_twitter) ? "<a href=\"http://twitter.com/".$m_twitter."\"><span class=\"info_par\"><img src=\"/img/twitter.png\" height=\"21\" width=\"30\" vspace=\"10\" style=\"border: none;\" alt=\"Мой Твиттер\" /></a></span>&nbsp;&nbsp;&nbsp;" : "";
	                                                        	print ($m_c_moikrug) ? "<a href=\"http://".$m_c_moikrug.".moikrug.ru\"><span class=\"info_par\"><img src=\"/img/c_moikrug.gif\" height=\"22\" width=\"23\" vspace=\"10\" style=\"border: none;\" alt=\"МойКруг.ру\" /></a></span>&nbsp;&nbsp;&nbsp;" : "";
	                                                        	print ($m_c_odnokl) ? "<a href=\"http://odnoklassniki.ru/user/".$m_c_odnokl."\"><span class=\"info_par\"><img src=\"/img/c_odnokl.gif\" height=\"22\" width=\"23\" vspace=\"10\" style=\"border: none;\" alt=\"Мой профиль на Одноклассниках\" /></a></span>&nbsp;&nbsp;&nbsp;" : "";
	                                                        	print ($m_c_opa) ? "<a href=\"http://".$m_c_opa.".opa.by\"><span class=\"info_par\"><img src=\"/img/c-opa.png\" height=\"16\" width=\"16\" vspace=\"10\" style=\"border: none;\" alt=\"Мой профиль на opa.by\" /></a></span>" : "";
	                                                        	print "</td></tr>";
	                                                        }
	                                                        print "<tr><td><p><br /></p></td></tr>";
                                                    	//}
		                                                if ($m_height||$m_weight||$m_tshirtsize) {
                                                    		print "<tr><td colspan=\"2\"><div class=\"info_header\">Антропометрические параметры</div></td></tr>";
	                                                        print ($m_height)   ? "<tr><td><div class=\"info_par\">Рост</div></td><td><div class=\"info_val\">$m_height</div></td></tr>" : "";
	                                                        print ($m_weight)   ? "<tr><td><div class=\"info_par\">Вес</div></td><td><div class=\"info_val\">$m_weight</div></td></tr>" : "";
	                                                        print ($m_tshirtsize)? "<tr><td><div class=\"info_par\">Размер&nbsp;футболки</div></td><td><div class=\"info_val\">$m_tshirtsize</div></td></tr>" : "";
	                                                        print "<tr><td><p><br /></p></td></tr>";
	                                                  	}
                                                        if ($m_tournaments) {
													        $sql = "SELECT *, YEAR(dat_begin) AS y, MONTH(dat_begin) AS m, DAYOFMONTH(dat_begin) AS d
													        	FROM tourn
													        	WHERE id IN (".str_replace(";",",",$m_tournaments).")
													        	ORDER BY y DESC, m ASC, d ASC";
		                                                    $rst = $db->sql_query($sql);
		                                                    $tourn_count = $db->sql_affectedrows($rst);
                                                        	print "<tr><td colspan=\"2\"><div class=\"info_header\">Участвовал".($m_sex?"":"а")." как минимум в&nbsp;$tourn_count ".num_decline($tourn_count,"турнире","турнирах","турнирах")."</div></td></tr>";
		                                                    $t = array();
		                                                    while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
		                                                    	$y = substr($line['dat_begin'],0,4);
		                                                    	if (!isset($t[$y])) $t[$y] = array();
		                                                    	array_push($t[$y], "<a href=\"/tourn/".$line['char_id']."/\">".stripslashes($line['short_name'])."</a>");
		                                               		}
                                                        	print "<tr><td colspan=\"2\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td>";
		                                               		foreach($t as $y=>$t0) {
		                                               			print "<tr valign=\"top\"><td width=\"20\"><p><div class=\"small\"><b>$y</b>&nbsp;<span style=\"color: silver;\"><sup>".sizeof($t0)."</span></sup></div></p></td>";
		                                               			print "<td><p><div class=\"small\" style=\"padding-left: 15\">";
		                                               			$i=0;
		                                               			foreach ($t0 as $t00)
		                                               				print ($i++?", ":"").$t00;
		                                             			print "</td></tr>";
		                                               		}
                                                            print "</table></td></tr>";
                                                        }
                                                        print "</table></td></tr></table></td></tr>";
			                                            print "</table>";
                                            			print "<table cellspacing=\"0\" cellpadding=\"15\" border=\"0\">";
                                                        print "<tr valign=\"top\"><td><p><br /></p>".get_comments(MY_PLAYERS, $pid, "О ".($m_sex?"нем":"ней")." говорят:")."</td></tr>";
                                                        print "<tr valign=\"top\"><td>".get_comments_form(MY_PLAYERS, $pid)."</td></tr>";
			                                            print "</table>";
                                                    }

                                            ?>
                      		</div></div>
        			<? } else { ?>
        					<br />
                            			<table cellspacing="0" cellpadding="15" border="0">
				    						<?
			                                    $sql = "SELECT * FROM tourn WHERE dat_begin<'".date("Ymd")."' ORDER BY dat_begin";
			                                    $rst = $db->sql_query($sql) or die($sql);
			                                    while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
			                                        $tourns[$line['id']]=$line['short_name']." ".substr($line['dat_begin'],0,4);
			                                    }
			                                    $sql ="SELECT
			                                        p.*, t.char_id, t.team_name, username
			                                        FROM players AS p
			                                        LEFT JOIN teams AS t ON p.id_team=t.id
			                                        LEFT JOIN phpbb_users AS u ON p.id=u.user_id
			                                        WHERE p.id=$pid";
			                                    $rst = $db->sql_query($sql) or die($sql);
			                                    $line = $db->sql_fetchrow($rst, MYSQL_ASSOC);
			                                    $m_id = $line['id'];
			                                    $m_p_char_id = $line['p_char_id'];
                                       			    $m_name = stripslashes(mycut($line['Name']));
                                            		    $m_patronymic = stripslashes(mycut($line['patronymic']));
                                            		    $m_surname = stripslashes(mycut($line['Surname']));
			                                    $m_nick = stripslashes(mycut($line['Nick']));
                                            		    $m_fullname = $m_name.($m_surname?" ".$m_surname:" ".$m_patronymic);
                                            		    if (!$m_fullname) $m_fullname = ($m_nick ? $m_nick : $line['username']);
			                                    $m_number = stripslashes(mycut($line['Number']));
			                                    $m_photo = stripslashes($line['Photo']);
			                                    $m_id_team = stripslashes($line['id_team']);
			                                    $m_team = stripslashes($line['Team']);
			                                    $m_team_name = stripslashes($line['team_name']);
			                                    $m_char_id = stripslashes($line['char_id']);
			                                    $m_city = stripslashes(mycut($line['City']));
			                                    $m_country = stripslashes(mycut($line['country']));
			                                    $m_bd = stripslashes(mycut($line['BD']));
			                                    $m_started = stripslashes(mycut($line['started']));
			                                    $m_prof = stripslashes(mycut($line['prof']));
			                                    $m_email = stripslashes(mycut($line['email']));
			                                    $m_icq = stripslashes(mycut($line['icq']));
			                                    $m_site = stripslashes(mycut($line['site']));
			                                    $m_skype = stripslashes(mycut($line['skype']));
			                                    $m_twitter = stripslashes(mycut($line['twitter']));
			                                    $m_about = stripslashes(mycut($line['about']));
			                                    $m_homeaddress = stripslashes(mycut($line['homeaddress']));
			                                    $m_workaddress = stripslashes(mycut($line['workaddress']));
			                                    $m_cellphone = stripslashes(mycut($line['cellphone']));
			                                    $m_homephone = stripslashes(mycut($line['homephone']));
			                                    $m_workphone = stripslashes(mycut($line['workphone']));
			                                    $m_height = stripslashes(mycut($line['height']));
			                                    $m_weight = stripslashes(mycut($line['weight']));
			                                    $m_tshirtsize = stripslashes(mycut($line['tshirtsize']));
			                                    $m_interests = stripslashes(mycut($line['interests']));
			                                    $m_tournaments = stripslashes($line['tournaments']);
			                                    $m_cap = stripslashes($line['cap']);
			                                    $m_id_city = stripslashes($line['id_city']);
		                                        $m_c_vkontakte = stripslashes(mycut($line['c_vkontakte']));
		                                        $m_c_lj = stripslashes(mycut($line['c_lj']));
		                                        $m_c_moikrug = stripslashes(mycut($line['c_moikrug']));
		                                        $m_c_odnokl = stripslashes(mycut($line['c_odnokl']));
		                                        $m_c_opa = stripslashes(mycut($line['c_opa']));
		                                        $m_c_facebook = stripslashes(mycut($line['c_facebook']));
		                                        $m_sex = stripslashes($line['id_sex']);
		                                        $m_first_tourn = stripslashes($line['first_tourn']);
		                                        $m_is_ukr = stripslashes($line['is_ukr']);
		                                        $m_year_started_playing = digits_only($line['year_started_playing']);

	                            				print "<tr valign=\"top\">";
		                            			print "<td colspan=\"2\">";
						    					print "<h1>$m_fullname</h1>";
						    					print "<p style=\"padding-top: 5;\"><div class=\"small\"><a href=\"/players/$pid/\">".($pid ? ($m_cap ? "Капитан" : "Игрок"). ($m_team_name ? "</a> команды <span class=\"division$division\"><a href=\"/teams/$m_char_id/players/\">$m_team_name</a></span>" : "</a>") : "<span class=\"division$division\">".$m_team_name."</span>")."</div>";
						    					print "<br /><div class=\"small\"><a id=\"player_link\" class=\"pseudo_link clickable\">изменить ссылку</a>";
						    					print "<div id=\"divPlayerLink\" style=\"background-color: beige; padding: 15; margin: -25 -15 -15 -15; display: none;\"><p>";
						    					print "<h2>http://ultimate.com.ua/players/<input id=\"p_char_id\" name=\"p_char_id\" style=\"font-size: 1.1em; width: 150px;\" value=\"$m_p_char_id\" />/&nbsp;&nbsp;&nbsp;&nbsp;<button id=\"PlayerLinkButton\">ОК</button>&nbsp;&nbsp;<span id=\"playerlink_preloader\"></span></h2>";
						    					print "<div class=\"smalldate0\">Разрешенные символы: 0-9, a-z, дефис (-), точка (.), подчеркивание (_)</div></div><br /><br />";
						    					?>
						    					<script language="javascript">
						    						$("#player_link").click(function () { $("#divPlayerLink").toggle(); });
													$('#PlayerLinkButton').click(function() {
														$('#playerlink_preloader').html('<img src="/img/ajax-loader.gif" alt="wait" />');
														$.post("/teams/update_player_link.php", { p_char_id: $('#p_char_id').val(), pid: $('#pid').val() },
															function(data){
																$('#p_char_id').val(data);
																$('#playerlink_preloader').html('');
														   		return false;
															});
												   		return false;
													});
												</script>
						    					<?
						    					print "</td></tr>";
	                            				print "<tr valign=\"top\">";
	                            				print "<td><form name=\"editform\" method=\"post\" action=\"/players/".($p_char_id?$p_char_id:$pid)."/edit\" enctype=\"multipart/form-data\">";
			                                    print "<input type=\"hidden\" name=\"pid\" id=\"pid\" value=\"$m_id\">";
	                            				print "<table cellspacing=\"0\" cellpadding=\"0\"><tr valign=\"top\">";

			                                    if ($photo_error) {
			                                        print "<td colspan=\"2\"><span style=\"background-color: red; color: white; font-weight: bold; padding: 10; margin: 0 -10 5 -10;\">$photo_error</span><br /><br /><br /></td></tr><tr valign=\"top\">";
			                                    } elseif ($updated) {
			                                        print "<td colspan=\"2\"><span style=\"background-color: forestgreen; color: white; font-weight: bold; padding: 10; margin: 0 -10 5 -10;\">Данные сохранены</span><br /><br /><br /></td></tr><tr valign=\"top\">";
			                                    }

			                                    print "<td><span style=\"color: #999;\">";

			                                    if ($rights['all_rights'] || $rights['team_'.$m_id_team.'_edit'] || $m_cap)
			                                        print "<input type=\"checkbox\" id=\"cap\" name=\"cap\" style=\"border: none; width: 20px;\"".($m_cap ? " checked":"")." /> <label for=\"cap\">капитан</label><p></p>";
			                                    //else
			                                    //	print "<input type=\"hidden\" name=\"cap\">";
			                                    print ($m_photo) ? "<img src=\"/teams/photo/$m_photo\" />" : "<img src=\"/teams/photo/nophoto.gif\" width=\"150\" height=\"200\" />";
			                                    print "<input type=\"hidden\" name=\"photo\" value=\"$m_photo\">";
			                                    print "<p>Загрузить фото с диска:";
			                                    print "<br /><input type=\"file\" name=\"fname\" style=\"width:250px;\">";

			                                    print "<br /><br />";

			                                    print "<p>Фамилия:<br /><input name=\"surname\" style=\"width: 350px;\" value=\"$m_surname\">";
			                                    print "<p>Имя:<br /><input name=\"name\" style=\"width: 350px;\" value=\"$m_name\"><br />";
			                                    print "<p>Отчество:<br /><input name=\"patronymic\" style=\"width: 350px;\" value=\"$m_patronymic\"><br />";
			                                    print "<p>Ник:<br /><input name=\"nick\" style=\"width: 350px;\" value=\"$m_nick\"><br />";

			                                    print "<p>";
			                                  	print "<input name=\"sex\" id=\"r1\" type=\"radio\" value=\"1\"".(($m_sex==1)?" checked":"")." /><label for=\"r1\"> я <span class=\"sex1\">мальчик</span></label><br />";
			                                  	print "<input name=\"sex\" id=\"r2\" type=\"radio\" value=\"0\"".(($m_sex==0)?" checked":"")." /><label for=\"r2\"> хотя нет, я <span class=\"sex0\">девочка</span></label>";
			                                  	print "<br /><br />";


			                                    if ($m_is_ukr) {
				                                    $teams = "<option value=\"0\"".($m_id_team?"":" selected=\"selected\"").">-нет команды-</option>";
				                                    $rst = $db->sql_query("SELECT * FROM teams");
				                                    while ($line = $db->sql_fetchrow($rst)) {
				                                        if ($rights['all_rights']||$rights['team_'.$line['id'].'_edit']||$m_id_team==$line['id']) {
				                                            $teams .= "<option value=\"".$line['id']."\"";
				                                            if ($line['id']==$m_id_team) $teams .= " selected=\"selected\"";
				                                            $teams .= ">".$line['team_name']."</option>";
				                                        }
				                                    }
				                                    print ($teams) ? "<p>Команда:<br /><select name=\"id_team\" style=\"width: 350px;\">$teams</select><br />" : "<input type=\"hidden\" name=\"id_team\" value=\"$m_id_team\" />";
				                        			print "<p>Команда, если выше нет команды:<br /><input name=\"team\" style=\"width: 350px;\" value=\"$m_team\"><br />";
			                                    	print "<p>Номер&nbsp;на&nbsp;поле:<br /><input name=\"number\" style=\"width: 350px;\" value=\"$m_number\"><br />";
				                                    $city = "";
				                                    $rst = $db->sql_query("SELECT * FROM cities");
				                                    while ($line = $db->sql_fetchrow($rst)) {
			                                            $city .= "<option value=\"".$line['id']."\"";
			                                            if ($line['id']==$m_id_city) $city .= " selected=\"selected\"";
			                                            $city .= ">".$line['city']."</option>";
				                                    }
				                                    print "<p>В основном, играю в городе:<br /><select name=\"id_city\" style=\"width: 350px;\">$city</select><br />";
				                        		} else {
				                        			print "<p>Команда:<br /><input name=\"team\" style=\"width: 350px;\" value=\"$m_team\"><br />";
			                                    	print "<p>Номер&nbsp;на&nbsp;поле:<br /><input name=\"number\" style=\"width: 350px;\" value=\"$m_number\"><br />";
				                                    print "<p>Город:<br /><input name=\"city\" style=\"width: 350px;\" value=\"$m_city\"><br />";
				                                    print "<p>Страна:<br /><input name=\"country\" style=\"width: 350px;\" value=\"$m_country\"><br />";
				                        		}

			                                    print "<p>Играю&nbsp;с:<br /><input name=\"started\" style=\"width: 350px;\" value=\"$m_started\"><br />";

			                                    print "<p>Играю&nbsp;с <select name=\"year_started_playing\" style=\"width: 100px;\">";
			                                    for ($year=date("Y");$year>1967;$year--) {
			                                    	print "<option value=\"$year\"";
			                                        if ($year==$m_year_started_playing) print " selected=\"selected\"";
			                                    	print ">$year</option>";
			                            		}
			                                    print "</select> года";
			                                    if ($m_is_ukr) print "<br />(справа показываются турниры начиная с этого года)";

			                                  	print "<br /><br /><br />";

			                                    print "<p>День&nbsp;рождения:<br /><input name=\"bd\" style=\"width: 350px;\" value=\"$m_bd\"><br />";
			                                    print "<p>Род&nbsp;занятий:<br /><input name=\"prof\" style=\"width: 350px;\" value=\"$m_prof\"><br />";
			                                    print "<p>Интересы:<br /><input name=\"interests\" style=\"width: 350px;\" value=\"$m_interests\"><br />";

			                                  	print "<br /><br />";

			                                    print "<p>Родной&nbsp;город:<br /><input name=\"city\" style=\"width: 350px;\" value=\"$m_city\"><br />";
			                                    print "<p>Домашний адрес:<br /><input name=\"homeaddress\" style=\"width: 350px;\" value=\"$m_homeaddress\"><br />";
			                                    print "<p>Рабочий адрес:<br /><input name=\"workaddress\" style=\"width: 350px;\" value=\"$m_workaddress\"><br />";
			                                    print "<p>Домашний телефон:<br /><input name=\"homephone\" style=\"width: 350px;\" value=\"$m_homephone\"><br />";
			                                    print "<p>Сотовый телефон:<br /><input name=\"cellphone\" style=\"width: 350px;\" value=\"$m_cellphone\"><br />";
			                                    print "<p>Рабочий телефон:<br /><input name=\"workphone\" style=\"width: 350px;\" value=\"$m_workphone\"><br />";
			                                    print "<p>e-mail:<br /><input name=\"email\" style=\"width: 350px;\" value=\"$m_email\"><br />";
			                                    print "<p>ICQ:<br /><input name=\"icq\" style=\"width: 350px;\" value=\"$m_icq\"><br />";
			                                    print "<p>Сайт:<br /><input name=\"site\" style=\"width: 350px;\" value=\"$m_site\"><br />";
			                                    print "<p>Skype:<br /><input name=\"skype\" style=\"width: 350px;\" value=\"$m_skype\"><br />";

			                                    print "<br /><br /><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
			                                    print "<tr valign=\"top\"><td align=\"right\"><p><img src=\"/img/c_facebook.png\" style=\"vertical-align: middle;\" /> http://facebook.com/</td><td><p><input onid=\"c_facebook\" name=\"c_facebook\" style=\"width:100px;\" value=\"$m_c_facebook\" />/</td></tr>";
			                                    //print "<tr valign=\"top\"><td colspan=\"2\"><span class=\"smalldate0\">бла бла</span></td></tr>";
			                                    print "<tr valign=\"top\"><td align=\"right\"><p><img src=\"/img/twitter.png\" style=\"vertical-align: middle;\" /> http://twitter.com/</td><td><p><input name=\"twitter\" style=\"width:100px;\" value=\"$m_twitter\" /></td></tr>";
			                                    print "<tr valign=\"top\"><td align=\"right\"><p><img src=\"/img/c_vkontakte.gif\" style=\"vertical-align: middle;\" /> http://vkontakte.ru/id</td><td><p><input name=\"c_vkontakte\" style=\"width:100px;\" value=\"$m_c_vkontakte\" /></td></tr>";
			                                    print "<tr valign=\"top\"><td align=\"right\"><p><img src=\"/img/c_lj.gif\" style=\"vertical-align: middle;\" /> http://</td><td><p><input name=\"c_lj\" style=\"width:100px;\" value=\"$m_c_lj\" />.livejournal.com</td></tr>";
			                                    print "<tr valign=\"top\"><td align=\"right\"><p><img src=\"/img/c_moikrug.gif\" style=\"vertical-align: middle;\" /> http://</td><td><p><input name=\"c_moikrug\" style=\"width:100px;\" value=\"$m_c_moikrug\">.moikrug.ru</td></tr>";
			                                    print "<tr valign=\"top\"><td align=\"right\"><p><img src=\"/img/c_odnokl.gif\" style=\"vertical-align: middle;\" /> http://odnoklassniki.ru/user/</td><td><p><input name=\"c_odnokl\" style=\"width:100px;\" value=\"$m_c_odnokl\"></td></tr>";
			                                    //print "<tr valign=\"top\"><td align=\"right\"><p><img src=\"/img/c_opa.gif\" style=\"vertical-align: middle;\" /> http://</td><td><p><input name=\"c_opa\" style=\"width:100px;\" value=\"$m_c_opa\">.opa.by</td></tr>";
			                                    print "</table>";

			                                  	print "<br /><br />";

			                                    print "<p>Рост:<br /><input name=\"height\" style=\"width: 350px;\" value=\"$m_height\"><br />";
			                                    print "<p>Вес:<br /><input name=\"weight\" style=\"width: 350px;\" value=\"$m_weight\"><br />";
			                                    print "<p>Размер футболки:<br /><input name=\"tshirtsize\" style=\"width: 350px;\" value=\"$m_tshirtsize\"><br />";
			                                    print "</span>";

			                                    print "<br /><br /><p><center><input type=\"submit\" value=\"OK\" style=\"font-size: 2em;\" /></center></p>";

			                                    print "</td>";
			                                ?>

						                        <? if ($m_is_ukr) { ?>
						                        <td style="padding-left: 15">
						                        	<p>Участие в турнирах:</p>
						                        	<?
					                                    if ($m_tournaments)
					                                    	$my_tourns = explode(";",$m_tournaments);
						                            	$sql = "SELECT *, YEAR(dat_begin) AS y, MONTH(dat_begin) AS m FROM tourn WHERE dat_begin<'".date("Ymd")."' AND dat_begin>'".$m_year_started_playing."0101' AND players_can_set_results=1 ORDER BY y DESC, m ASC";
				                                        $rst = $db->sql_query($sql);
				                                        $y = "";
				                                        while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
				                                        	if ($y != $line['y']) {
				                                        		$y = $line['y'];
				                                        		print "<br /><p><h3>$y</h3><p>";
				                                        	}
				                                            print "<input type=\"checkbox\" id=\"tourn$line[id]\" name=\"tourn$line[id]\" style=\"border: none; width: 20px;\" value=\"$line[id]\"";
				                                            if ($m_tournaments) {
				                                                foreach ($my_tourns as $tournament) {
				                                                    if ($tournament == $line['id']) {
				                                                        print " checked";
				                                                    }
				                                                }
				                                            }
				                                            print ">&nbsp;<label for=\"tourn$line[id]\"><span class=\"pltourn\">".preg_replace("/ /","&nbsp;",stripslashes($line['short_name']))." <a target=\"_blank\" href=\"/tourn/".$line['char_id']."/\"><img src=\"/img/new-window-icon.gif\" style=\"border: none;\" width=\"11\" height=\"11\" alt=\"Открыть в новом окне\" /></a></span></label><br />";
				                                        }
				                                    ?>
						                        </td>
						                        <? } ?>
			                                </tr></table></td></tr>
									</table>
    						</form>
        			<? } ?>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/footer.php";
?>
