<?

	ini_set("max_execution_time", "120");

	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/init.php";

	$t = array();
	$r = $db->sql_query("SELECT * FROM tourn WHERE players_can_set_results=1");
	while ($l = $db->sql_fetchrow($r)) {
		if ($l['dat_begin']>20100604) {
			print "<P>".$l['dat_begin'];
			print "<br />".$l['id'];
			//$db->sql_query("delete from players_tourn where id_tourn=".$l['id']);
		}
	}

?>