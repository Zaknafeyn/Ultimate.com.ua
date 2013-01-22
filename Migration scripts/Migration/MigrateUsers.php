<?php
  
  //clear users
  //echo "dont forget uncomment where necessary";
  $q = "delete from ".$dbnameDest.".".$dbPrefixDest."users where user_id > ".$userIdAdditional;
  mysql_query($q, $ConnDest);
  
  //import users
  $q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."users";// where user_posts > 0";
  
  //$q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."users where user_id in (1062)";// where user_posts > 0";
 
  $result = mysql_query($q, $ConnSource);

  while($row = mysql_fetch_array($result))
  {	  
  	if (strtolower($row['username']) == 'anonymous')
  	{
  		continue; 
  	}
  	  
  	$bbode_sig_uid_original = $row['user_sig_bbcode_uid'];
  	$bbcode_sig_uid_new = substr(md5($bbode_sig_uid_original), 0, 8);
  	
  	$signature_original = $row ['user_sig'];
  	$signature_new = addslashes(str_replace($bbode_sig_uid_original, $bbcode_sig_uid_new, $signature_original));
  	
  	$user_rank = $row['user_rank'];
  	if ($user_rank == null)
  	{
  		$user_rank = "'',";
  	}
  	else
  	{
  		$user_rank = $user_rank.",";
  	}
  	
  	$user_emailtime = $row['user_emailtime'];
  	if ($user_emailtime == null)
  	{
  		$user_emailtime = 0;
  	}
  	
  	$mail_hash = 0;
  	$user_email = $row['user_email'];
  	//if ($user_email =! null && user_email != '')
  	//{
  	//	$mail_hash = crc32(strtolower($user_email)) . strlen($user_email);
  	//}
  	
	$user_viewmail = $row['user_viewemail'];
	if ($user_viewmail == null)
	{
		$user_viewmail = 0;
	}
	
    $InsertStatement = "INSERT INTO ".$dbnameDest.".".$dbPrefixDest."users
            (`user_id`,
             `user_type`,
             `group_id`,
             `user_permissions`,
             `user_perm_from`,
             `user_ip`,
             `user_regdate`,
             `username`,
             `username_clean`,
             `user_password`,
             `user_passchg`,
             `user_pass_convert`,
             `user_email`,
             `user_email_hash`,
             `user_birthday`,
             `user_lastvisit`,
             `user_lastmark`,
             `user_lastpost_time`,
             `user_lastpage`,
             `user_last_confirm_key`,
             `user_last_search`,
             `user_warnings`,
             `user_last_warning`,
             `user_login_attempts`,
             `user_inactive_reason`,
             `user_inactive_time`,
             `user_posts`,
             `user_lang`,
             `user_timezone`,
             `user_dst`,
             `user_dateformat`,
             `user_style`,
             `user_rank`,
             `user_colour`,
             `user_new_privmsg`,
             `user_unread_privmsg`,
             `user_last_privmsg`,
             `user_message_rules`,
             `user_full_folder`,
             `user_emailtime`,
             `user_topic_show_days`,
             `user_topic_sortby_type`,
             `user_topic_sortby_dir`,
             `user_post_show_days`,
             `user_post_sortby_type`,
             `user_post_sortby_dir`,
             `user_notify`,
             `user_notify_pm`,
             `user_notify_type`,
             `user_allow_pm`,
             `user_allow_viewonline`,
             `user_allow_viewemail`,
             `user_allow_massemail`,
             `user_options`,
             `user_avatar`,
             `user_avatar_type`,
             `user_avatar_width`,
             `user_avatar_height`,
             `user_sig`,
             `user_sig_bbcode_uid`,
             `user_sig_bbcode_bitfield`,
             `user_from`,
             `user_icq`,
             `user_aim`,
             `user_yim`,
             `user_msnm`,
             `user_jabber`,
             `user_website`,
             `user_occ`,
             `user_interests`,
             `user_actkey`,
             `user_newpasswd`,
             `user_form_salt`,
             `user_new`,
             `user_reminded`,
             `user_reminded_time`)
VALUES (".		
		($row['user_id'] + $userIdAdditional).",".
		'0,'.  //$row['user_level'].",".	//user_type = user_level
        '2,'. //default group - registered users
        "'',". //  user_permissions
        "0,". // as user_perm_from
        "'',".  // as user_ip
        "'".addslashes($row['user_regdate'])."',".
        "'".addslashes($row['username'])."',".
        "'".addslashes($row['username'])."',".  // as username_clean
        "'".$row['user_password']."',".
        "0,".
        "0,".
        "'".addslashes($user_email)."',".
        $mail_hash.",".
        "'',". // as user_birthday
        $row['user_lastvisit'].",".
        "0,". // as user_lastmark
        "0,".  // as user_lastpost_time
        "'',".  // as user_lastpage
        "'',".   // as user_last_confirm_key
        "0,".   // as user_last_search
        "0,".  // as user_warnings
        '0,'.   // as user_last_warning
        $row['user_login_tries'].",".
        '0,'.  // as user_inactive_reason
        '0,'.  // as user_inactive_time
        $row['user_posts'].",".
        "'en',".  // as user_lang
        $row['user_timezone'].",".
        '0,'.   // as user_dst
        "'D M d, Y g:i a',".
        "1,".  // as user_style
        $user_rank.
        "'9E8DA7',".  // as user_colour
        $row['user_new_privmsg'].",".
        $row['user_unread_privmsg'].",".
        $row['user_last_privmsg'].",".
        "0,".  // as user_message_rules
        '-3,'.   // as user_full_folder
        $user_emailtime.",".
        '0,'.  // as user_topic_show_days
        "'t',".  // as user_topic_sortby_type
        "'d',".  // as user_topic_sortby_dir
        '0,'.  // as user_post_show_days
        "'t',".  // as user_post_sortby_type
        "'a',".  // as user_post_sortby_dir
        $row['user_notify'].",".
        $row['user_notify_pm'].",".
        '0,'.  // as user_notify_type
        $row['user_allow_pm'].",".
        $row['user_allow_viewonline'].",".
        $user_viewmail.",".
        '0,'.   // as user_allow_massemail
        '230271,'.   // as user_options
        "'".$row['user_avatar']."',".
        $row['user_avatar_type'].",".
        '0,'.   // as user_avatar_width
        '0,'. //user_avatar_height
        "'".addslashes($signature_new)."',". //"'".$row['user_sig']."',".
        "'".$bbcode_sig_uid_new."',". //'user_sig_bbcode_uid',
        "'',".  // as user_sig_bbcode_bitfield
        "'".addslashes($row['user_from'])."',".
        "'".addslashes($row['user_icq'])."',".
        "'".addslashes($row['user_aim'])."',".
        "'".addslashes($row['user_yim'])."',".
        "'".addslashes($row['user_msnm'])."',".
        "'',". // as user_jabber
        "'".addslashes($row['user_website'])."',".
        "'".addslashes($row['user_occ'])."',".
        "'".addslashes($row['user_interests'])."',".
        "'".$row['user_actkey']."',".
        "'".$row['user_newpasswd']."',".
        "'".md5(time().rand())."',".  // as user_form_salt
        '0,'.  // as user_new
        '0,'.  // as user_reminded
        "0)"; // as user_reminded_time
  
  
	 mysql_query($InsertStatement, $ConnDest);
  
	// delete non-active users
	//$q = "delete from ".$dbnameDest.".".$dbPrefixDest."users where user_posts = 0";
	//mysql_query($q, $ConnSource);
  
     //echo "<br>".$InsertStatement."<br>";
  }

?>
