<?
	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/init.php";

	$id_tourn = digits_only($_POST['id_tourn']);
	$id_user = digits_only($_POST['id_user']);
	$ptcp = digits_only($_POST['ptcp']);

	include_once $_SERVER['DOCUMENT_ROOT']."/site/teams/admin_player.php";

	if (user_has_rights_to_set_tourn_participation($id_user))
		if ($id_tourn) {
			$db->sql_query("INSERT INTO players_tourn SET id_player=$id_user, id_tourn=$id_tourn, ptcp=$ptcp");
			echo iconv("WINDOWS-1251","UTF-8",get_player_tournaments($id_user));
		}

	echo '';
?>
