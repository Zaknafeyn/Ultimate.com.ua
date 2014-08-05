<?
	if (isset($_POST['title'])) {
		include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";
		$id = digits_only($_POST['title_id']);
		$title = $_POST['title'];
		if ($title) {
			$db->sql_query("UPDATE photo SET title='".mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$title))."' WHERE id=$id");
			echo stripslashes($title);

			$url = $_SERVER["HTTP_REFERER"]."#o0";
			$txt = "<html><body>";
			$txt .= "<a href=\"$url\">$url</a>";
			if ($user->data['user_id'] != ANONYMOUS)
				$txt .= "<br /><a href=\"http://ultimate.com.ua/4room/profile.php?mode=viewprofile&u=".$user->data['user_id']."\">".$user->data['username']."</a>";
			$txt .= "<br />".$_SERVER["REMOTE_ADDR"];
			$txt .= "</body></html>";
			mail("frisbee@tut.by","FOTO ultimate.com.ua: ".stripslashes(iconv("UTF-8","WINDOWS-1251",$title)),$txt,"Content-type: text/html;\r\nFrom: ultimate.com.ua <admin@ultimate.com.ua>");

		}
	}
?>
