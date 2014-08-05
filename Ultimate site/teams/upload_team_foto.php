<?
	$max_upload_file_width = 700;
	$max_upload_file_height = 500;

	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/init.php";

	$id_team = digits_only($_POST['id_team']);
	$id_user =$userdata['user_id'];

	$user_has_rights_to_upload_team_foto = user_has_rights_to_upload_team_foto($id_team,$id_user);

	if ($db->sql_affectedrows($db->sql_query("SELECT id FROM teams_foto WHERE id_team=$id_team"))<$max_team_foto)

		if ($id_team && $user_has_rights_to_upload_team_foto)
	                    if (isset($_FILES["foto_name"])) {
	                        if ( preg_match('/\.(jpg|jpeg)$/i', $_FILES["foto_name"]["name"]) ) {
	                            if (preg_match('#image\/[x\-]*([a-z]+)#', $_FILES["foto_name"]["type"], $filetype)) {
	                                    list($width, $height) = @getimagesize($_FILES["foto_name"]["tmp_name"]);
                                        $fext = $filetype[1];
                                        $fname = $id_team."_".uniqid(rand()).".".$fext;
                                        $th_fname = "th_".$fname;
                                        $tmp_file = $_FILES["foto_name"]["tmp_name"];
                                        if (move_uploaded_file($tmp_file,$_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$fname")) {
                                            @chmod($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$fname", 0644);
	                                    	if ($width>$max_upload_file_width || $height>$max_upload_file_height ) {
							                    $i = imageresize($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$fname", $_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$fname", $max_upload_file_width, $max_upload_file_height, 85);
								                @chmod($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/$fname", 0644);
	                                  		}
                   							makeIcons_MergeCenter($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/".$fname, $_SERVER['DOCUMENT_ROOT']."/site/teams/photo/".$th_fname, 75, 75, 85);
							                @chmod($_SERVER['DOCUMENT_ROOT']."/site/teams/photo/".$th_fname, 0644);
	                                  		if (true) {
		                                  		$descr = mysql_escape_string(htmlspecialchars($_POST['foto_descr']));
		                                  		$dat_txt = $_POST['foto_date'];
		                                  		$dat = mktime(date("H"),date("i"),date("s"),substr($dat_txt,3,2),substr($dat_txt,0,2),substr($dat_txt,6,4));
    	                                        $db->sql_query("INSERT INTO teams_foto SET
        	                                    	id_team=$id_team,
            	                                	foto='$fname',
                	                            	descr='$descr',
                    	                        	user_id=".$userdata['user_id'].",
                    	                        	dat=$dat,
                        	                    	dat_post=".time());
												$mail_subj = $userdata['username']." uploaded team foto";
												$mail_text = "<html><body><p>".$userdata['username']." uploaded team foto<br /><a href=\"http://ultimate.com.ua/teams/photo/".$fname."\">$fname</a></body></html>";
												//die($mail_text);
												mail("frisbee@tut.by",iconv("WINDOWS-1251","UTF-8",$mail_subj),$mail_text,"Content-type: text/html;\r\nFrom: ultimate.com.ua <admin@ultimate.com.ua>");
											}
                                        } else die('move failed');
	                            } else die('type failed');
	                         } else die('ext failed');
                        } else die('not set');


	$r = $db->sql_query("SELECT char_id FROM teams WHERE id=$id_team");
	$l = $db->sql_fetchrow($r);
	header("location:/teams/".$l['char_id']."/photo/");

?>
