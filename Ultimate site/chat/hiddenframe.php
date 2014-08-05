<?php
	include ("c_config.php");

	if (getenv("REQUEST_METHOD")!='GET'){exit;}

	$mid = $_GET['mid'];
	sp_check_users_ip_name($mid);

	$id_user=sp_room($mid);
	$name = get_user_param($id_user,"name");
	$refresh_time = get_user_param($id_user,"refresh");
	$bg = get_user_param($id_user,"bg");
	$top2bot = (get_user_param($id_user,"top2bot")=="0") ? false : true;

	$last_message_id = $_REQUEST['lmid'];
    if (!isset($_REQUEST['lut']))
    	$last_user_list_update_time = 0;
    else
		$last_user_list_update_time = $_REQUEST['lut'];

	$sql = "SELECT * FROM my4_messages ORDER BY -mtime LIMIT 500";
	$rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
	$co = $db->sql_affectedrows($rst);

	$bIsNewMessages = false;
	$i=0;

?><script language="JavaScript">parent.new_msgs = new Array();</script><?php

  $line = $db->sql_fetchrow($rst, MYSQL_ASSOC);
  $new_last_message_id = $line['id_message'];

	do {
	  $current_id = $line['id_message'];
    $private = ($line['private']==0) ? false: true;
    $who = $line['who'];
    $whom = $line['whom'];
	  if ($current_id != $last_message_id) {
	    if ( ($bg) && (preg_match("/<z.?>$name<\/z.?>/i",$whom)) )
      	$who = preg_replace("/(<b><u>[^\s]*?<\/u><\/b>)/i", "<font size=+2>\\1</font>", $who);
		  $msg = $who.$whom.$line['message'];
      if ($private) {
        if (preg_match("/<zz>$name<\/zz>/i",$whom) || preg_match("/<b><u>$name<\/u><\/b>/i",$who)) { ?>
          ?><script language="JavaScript">parent.new_msgs[parent.new_msgs.length]="<?php print "<font size=-1 color=yellow>$str_say_whisper&nbsp;</font>".$msg; ?>";</script><?php
		      $bIsNewMessages = true;
		      $i++;
        }
	    } else {
	        ?><script language="JavaScript">parent.new_msgs[parent.new_msgs.length]="<?php print $msg; ?>";</script><?php
		      $bIsNewMessages = true;
		      $i++;
	    }
	  }
	} while (($current_id!=$last_message_id)&&($line=$db->sql_fetchrow($rst, MYSQL_ASSOC))&&($i<$lines_on_main_frame));

if ($bIsNewMessages) {
	?><script language="JavaScript">parent.bodyframe.f(<?php print $top2bot; ?>);</script>
<?php
}

	$last_message_id = $new_last_message_id;

?><script language="JavaScript">parent.bottomframe.bottomform.lmid.value="<?php echo $last_message_id; ?>";</script><?php

	$sql = "SELECT MAX(lut) FROM my4_session";
	$rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
	$line = $db->sql_fetchrow($rst, MYSQL_NUM);

if ( $last_user_list_update_time != $line[0]) {
	$last_user_list_update_time = $line[0];
	?><script language="JavaScript">
    	parent.bottomframe.bottomform.lut.value="<?php echo $last_user_list_update_time; ?>";
    	parent.ppl = new Array();
    </script><?php
	$sql = "SELECT * FROM my4_session";
	$rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());

    ?><script language="JavaScript">parent.ppl[parent.ppl.length]='<a>Сейчас в чате (<?php echo $db->sql_affectedrows($rst); ?>):</a><br><table cellspacing=2 cellpadding=0>';</script><?php

    while ($line=$db->sql_fetchrow($rst, MYSQL_ASSOC)) {
	    $id_user1 = $line['id_user'];
	    $sex1 = get_user_param($id_user1, "sex");
	    if ($sex1=="") $sex1="u";
	    $name1 = get_user_param($id_user1,"name");
	    if ($avatar_file = get_user_param($id_user1,"avatar")) {
	        $user_image = "<img src=\"avatars/$avatar_file\" style=\"border:none;\" align=absmiddle>";
	    } else {
	        $user_image = "<img src=\"images/i_$sex1.gif\" style=\"border:none\" align=absmiddle>";
	    }
        $status1 = $line['status'];

    	$user_line = "<tr><td align=center><a href=\"info.php?mid=$mid&uid=$id_user1&user=&all=\" target=\"userinfoframe\">$user_image</a></td>";
        $user_line.= "<td valign=center>&nbsp;<a class=appl href=javascript:parent.sn3(\'$name1\',0)><font style=\"font-size: 13px; \" color=";
	    if ($sex1 == "m"){ $user_line.= $man_color; }
	    if ($sex1 == "w"){ $user_line.= $woman_color; }
	    if ($sex1 == "u"){ $user_line.= $u_color; }
	    $user_line.= ">$name1</font></a> $status1</td></tr>";

    	?><script language="JavaScript">parent.ppl[parent.ppl.length]='<?php echo $user_line; ?>';</script><?php
    }
	?><script language="JavaScript">
		parent.ppl[parent.ppl.length]='</table>';
    	parent.peopleframe.f();
    </script>

<? } ?>

    <script language="JavaScript">
	parent.connect_fails=0;
    parent.menuframe.connection_status.src=parent.img_ok.src;
</script>