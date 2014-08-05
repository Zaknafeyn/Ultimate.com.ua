<?
	$banned = array(
		"list"=>1,
		"ro_ot"=>1
	);

	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/init.php";
	$p_char_id = strtolower($_POST['p_char_id']);
	$pid = digits_only($_POST['pid']);
	$r = $db->sql_query("SELECT id_team FROM players WHERE id=$pid");
	if ($db->sql_affectedrows($r)) {
		$l = $db->sql_fetchrow($r);
		$id_team = $l['id_team'];
	}
	mail("frisbee@tut.by",$userdata['username'].": /players/$p_char_id","<html><body>".$userdata['username']."<br /><a href=\"http://ultimate.dp.ua/players/$p_char_id/\">http://ultimate.com.ua/players/$p_char_id/</a></body></html>","Content-type: text/html;\r\nFrom: ultimate.com.ua <admin@ultimate.com.ua>");
	if ($rights['all_rights'] || $rights['team_'.$id_team.'_edit'] || $userdata['user_id']==$pid) {
		// если хватает прав
		if (!$banned[$p_char_id]) {
			// если не в списке запрещенных ссылок
			if ($p_char_id == preg_replace("/[^0-9a-z\-_\.]/","",$p_char_id)) {
				// и если в ссылке разрешенные символы
				if (preg_replace("/[\-_\.]/","",$p_char_id)) {
					// и если это не только спец симоволы
					if (preg_replace("/[0-9]/","",$p_char_id)) {
						// и если это не только цифры
						if (strlen($p_char_id)>1) {
							// и если хотя бы 2 символа
							$r = $db->sql_query("SELECT * FROM players WHERE p_char_id='$p_char_id' AND id<>$pid");
							if (!$db->sql_affectedrows($r)) {
								// и если больше нет пользователей с таким char_id
								$db->sql_query("UPDATE players SET p_char_id='$p_char_id' WHERE id=$pid");
								echo $p_char_id;
								exit;
							}
						}
					}
				}
			}
		}
	}
	$r = $db->sql_query("SELECT p_char_id FROM players WHERE id=$pid");
	$l = $db->sql_fetchrow($r);
	echo $l['p_char_id'];
?>