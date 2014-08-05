<?
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	$id_foto = digits_only($_POST['id']);
	$id_user =$userdata['user_id'];

	$r = $db->sql_query("SELECT * FROM teams_foto WHERE id=$id_foto");
	if ($db->sql_affectedrows($r)) {
		$l = $db->sql_fetchrow($r);
		$user_has_rights_to_upload_team_foto = user_has_rights_to_upload_team_foto($l['id_team'],$id_user);

		if ($user_has_rights_to_upload_team_foto) {
			unlink($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/".$l['foto']);
			unlink($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/th_".$l['foto']);
			$db->sql_query("DELETE FROM teams_foto WHERE id=$id_foto");
			echo '';
		} else {
			echo 'Не хватает прав, читер';
		}
	} else {
		echo 'Что это за фотография?';
	}

?>
