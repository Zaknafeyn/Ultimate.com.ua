<?
	if (isset($_POST['comment_text'])) {
		include_once $path_site."/tmpl/init.php";
		$cat = digits_only($_POST['comment_cat']);
		$itm = digits_only($_POST['comment_itm']);
		$author = $_POST['comment_author'];
		$txt = $_POST['comment_text'];
		$uid = $user->data['user_id'] != ANONYMOUS?$user->data['user_id']:0;
		if (($user->data['user_id'] == ANONYMOUS) && strpos($txt,"http")) {			$txt = "";		}
		if ($txt == iconv("WINDOWS-1251","UTF-8","Ваш комментарий")) {
			$txt = "";
			echo("<tr valign=\"top\"><td colspan=\"2\"><p>".iconv("WINDOWS-1251","UTF-8","Отличный комментарий!")."</p></td></tr>");
		}
		if ($author == iconv("WINDOWS-1251","UTF-8","Ваше имя")) {			$author = "";
		}
		if ($cat && $itm && $txt) {			$txt = save_comment($cat, $itm, iconv("UTF-8","WINDOWS-1251",$author),'', iconv("UTF-8","WINDOWS-1251",$txt), '', 0,0,0);
            $author = "<b>".iconv("WINDOWS-1251","UTF-8",stripslashes(GetUserProfile($uid, $author)))."</b>";
            $author_pic = GetUserAvatar($uid);
            $date = iconv("WINDOWS-1251","UTF-8",make_human_date(time()));
            $txt = preg_replace("/\\n/","<br />",$txt);
            $com  = "<tr valign=\"top\">";
            $com .= "<td width=\"30\"><p>$author_pic</p><br /></td>";
            $com .= "<td style=\"padding-left: 15;\"><p><div class=\"smalldate0\">$author $date</div>";
            $com .= "<p style=\"padding-top: 5\">".iconv("WINDOWS-1251","UTF-8",$txt)."</p><br /></td></tr>";
	        echo $com;
		}
	}
?>
