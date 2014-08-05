<?php
	include ("c_config.php");
	$mid = $_REQUEST['mid'];
	if (!$mid) exit;
	$id_user = sp_room($mid);
	if ($id_user == -1) exit;
  $name = get_user_param($id_user,"name");
  $status = get_user_param($id_user,"rights");
  if ($status=="guest") exit;

	$act='';
  $words = $_REQUEST['words'];
  if ($words) {
		if (isset($_REQUEST['clearall'])) {
	      $act = process_command($id_user,$words,$who,$reason);
    } else {

	    $reason = sp_c_m_s(trim($_REQUEST['reason']));
	    if (isset($_REQUEST['offban'])) {
	        $sql = "SELECT * FROM my4_users WHERE ban_time<>0";
	    } else {
	        $sql = "SELECT * FROM my4_session";
	    }
	    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	    $who="";
	    while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
	      $who1="";
	      $who1 = $_REQUEST["who".$line['id_user']];
	      if ($who1) {
	        if ($who) $who .= ",";
	        $who .= $who1;
	      }
	    }
	    if ($who!='')
	      $act = process_command($id_user,$words,$who,$reason);
    }
  }
?>
<html><head><title><?php echo $name . " :: œ‡ÌÂÎ¸ ‡‰ÏËÌËÒÚ‡ˆËË :: ◊‡Ú :: ULTIMATE ‘–»«¡» Õ¿ ” –¿»Õ≈ ( »≈¬)"; ?></title></head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
</head>
<style>
p {font-size: 12px;}
a{
	color: black;
  font-size: 12px;
	font-weight: bold;
  font-family: tahoma;
}
.uname { font-size: 16px; font-weight: bold;}
</style>
<body>
	<?php if ($act!='') print "<p>$act<p>"; ?>
	<p><font color=silver>current time: <?php print date("H:i:s"); ?><p>
	<a href="admin.php?mid=<?php print $mid; ?>&words="><img src=images/refresh.gif style="border:none; vertical-align:middle;"> <?php print $str_admin_refresh; ?></a>
  <table><tr>
  	<td valign=top>
	    <form action=admin.php method=post>
	    	<p>œÓÎ¸ÁÓ‚‡ÚÂÎË on-line:
	      <table><tr>
	        <td valign=top>
	          <table cellspacing=1 cellpadding=3 style="border: 1px solid #444444">
	            <tr>
	              <td bgcolor=#7DCF23></td>
	              <td colspan=2 bgcolor=#7DCF23><p align=center><?php print $str_admin_nick; ?></td>
	              <td bgcolor=#7DCF23><p align=center><?php print $str_admin_rights; ?></td>
	              <td bgcolor=#7DCF23><p align=center><?php print $str_admin_ip; ?></td>
	              <td bgcolor=#7DCF23><p align=center><?php print $str_admin_ltime; ?></td>
	              <td bgcolor=#7DCF23><p align=center><?php print $str_admin_bonus; ?></td>
	            </tr>
	            <?php
	              $sql = "SELECT * FROM my4_session";
	              $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	              while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
	                $ip = $line['ip'];
	                $lasttime = $line['lasttime'];
	                $id_user1 = $line['id_user'];
	                $sex1 = get_user_param($id_user1, "sex");
	                if ($sex1 == "") $sex1 = "u";
	                $status1 = get_user_param($id_user1,"rights");
	                $name1 = get_user_param($id_user1,"name");
	                if ($avatar_file = get_user_param($id_user1,"avatar")) {
	                  $user_image = "<img src=avatars/$avatar_file style=border:none>";
	                } else {
	                  $user_image = "<img src=images/i_$sex1.gif style=border:none>";
	                }
                  $sql1 = "SELECT
                  	u.caps_time as caps_time, u1.name as caps_name,
                  	u.vowel_time as vowel_time, u2.name as vowel_name,
                  	u.consonan_time as consonan_time, u3.name as consonan_name,
                  	u.swear_time as swear_time, u4.name as swear_name
                  	FROM my4_users u
                    LEFT JOIN my4_users u1 ON u.caps_id_user=u1.id_user
                    LEFT JOIN my4_users u2 ON u.vowel_id_user=u2.id_user
                    LEFT JOIN my4_users u3 ON u.consonan_id_user=u3.id_user
                    LEFT JOIN my4_users u4 ON u.swear_id_user=u4.id_user
                    WHERE u.id_user=$id_user1";
	                $rst1 = $db->sql_query($sql1) or die("<p>$sql1<p>".mysql_error());
                  $bonus = "";
                  if ($line1 = $db->sql_fetchrow($rst1, MYSQL_ASSOC)) {
                  	$bonus_color='brown';
	                  $user_color ='gray';
	                  if ($line1['caps_time']!=0) {
	                    if ($bonus!="") $bonus .= "<br>";
                    	$bonus .= "<font color=$bonus_color>$str_admin_caps_str</font><font color=$user_color>|".$line1['caps_name']."</font>";
                    }
	                  if ($line1['vowel_time']!=0) {
	                    if ($bonus!="") $bonus .= "<br>";
                    	$bonus .= "<font color=$bonus_color>$str_admin_vowel_str<font color=$user_color>|".$line1['vowel_name']."</font>";
                    }
	                  if ($line1['consonan_time']!=0) {
	                    if ($bonus!="") $bonus .= "<br>";
                    	$bonus .= "<font color=$bonus_color>$str_admin_consonan_str<font color=$user_color>|".$line1['consonan_name']."</font>";
                    }
	                  if ($line1['swear_time']!=0) {
	                    if ($bonus!="") $bonus .= "<br>";
                    	$bonus .= "<font color=$bonus_color>$str_admin_swear_str<font color=$user_color>|".$line1['swear_name']."</font>";
                    }
                  }
	                print "<tr>";
	                print "<td bgcolor=white valign=top align=center><input type=checkbox name='who".$id_user1."' value='$name1'></td>";
	                print "<td bgcolor=white valign=top align=center>$user_image</td>";
	                print "<td bgcolor=white valign=top><p><font class=uname color=$sex_color[$sex1]>&nbsp;$name1</font></td>";
	                print "<td bgcolor=white valign=top><p><font color=$stat_color[$status1]>$status1</font></td>";
	                print "<td bgcolor=white valign=top><p><a href=../ipinfo.php?ip=$ip target=_blank>$ip</a></td>";
	                print "<td bgcolor=white valign=top><p>".date("i:s",(time()-$lasttime))."</td>";
	                print "<td bgcolor=white valign=top><p>$bonus</td>";
	                print "</tr>";
	              }
	            ?>
	          </table>
	        </td></tr><tr>
	        <td valign=top>
	          <input name='mid' value='<?php print $mid; ?>' type=hidden>
	          <input name='words' type=hidden>
	          <p><?php print $str_admin_act_str; ?>: <select class=i onchange="document.forms[0].words.value = this.options[this.selectedIndex].value;">
	            <option value=''><?php print $str_admin_act_select; ?></option>
	            <option value='@kick'><?php print $str_admin_act_kick; ?></option>
	            <option value='@ban'><?php print $str_admin_act_ban; ?></option>
	            <option value='@caps'><?php print $str_admin_act_caps; ?></option>
	            <option value='@vowel'><?php print $str_admin_act_vowel; ?></option>
	            <option value='@consonan'><?php print $str_admin_act_consonan; ?></option>
	            <option value='@swear'><?php print $str_admin_act_swear; ?></option>
	            <option value='@delmessage'><?php print $str_admin_act_delmessage; ?></option>
	          </select>
	          <p><?php print $str_admin_reason; ?>:<br><input class='i' name='reason' style='width:200px'>
	          <p><input class=b type=submit value='OK'>
	        </td>
	      </tr></table>
	    </form>
    </td><td valign=top>
    <?php
      $sql = "SELECT
        u.id_user, u.sex, u.name, u.avatar, u.ban_time, u1.name as ban_name
        FROM my4_users u
        LEFT JOIN my4_users u1 ON u.ban_id_user=u1.id_user
        WHERE u.ban_time<>0";
      $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
      if ($db->sql_affectedrows($rst)) {
    ?>

	    <form action=admin.php method=post>
      	<p><?php print $str_adimin_banned; ?>:
	      <table><tr>
	        <td valign=top>
	          <table cellspacing=1 cellpadding=3 style="border: 1px solid #7DCF23">
	            <tr>
	              <td bgcolor=#7DCF23></td>
	              <td colspan=2 bgcolor=#7DCF23><p align=center><?php print $str_admin_banned_nick; ?></td>
	              <td bgcolor=#7DCF23><p align=center><?php print $str_admin_banned_whom; ?></td>
	              <td bgcolor=#7DCF23><p align=center><?php print $str_admin_banned_when; ?></td>
	            </tr>
	            <?php
	              while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
	                $id_user1 = $line['id_user'];
	                $sex1 = $line['sex'];
	                if ($sex1 == "") $sex1 = "u";
	                $name1 = $line['name'];
	                if ($avatar_file = $line['avatar']) {
	                  $user_image = "<img src=avatars/$avatar_file style=border:none>";
	                } else {
	                  $user_image = "<img src=images/i_$sex1.gif style=border:none>";
	                }
                  $who = $line['ban_name'];
                  $when = date("d.m.Y H:i:s",$line['ban_time']);
	                print "<tr>";
	                print "<td bgcolor=white valign=top align=center><input type=checkbox name='who".$id_user1."' value='$name1'></td>";
	                print "<td bgcolor=white valign=top align=center>$user_image</td>";
	                print "<td bgcolor=white valign=top><p><font class=uname color=$sex_color[$sex1]>&nbsp;$name1</font></td>";
	                print "<td bgcolor=white valign=top><p>$who</td>";
	                print "<td bgcolor=white valign=top><p>$when</td>";
	                print "</tr>";
	              }
	            ?>
	          </table>
	        </td></tr><tr>
	        <td valign=top>
	          <input name='mid' value='<?php print $mid; ?>' type='hidden'>
	          <input name='words' type='hidden' value='@ban'>
	          <p><input class='i' type='submit' name='offban' value='<?php print $str_admin_uban; ?>'>
	        </td>
	      </tr></table>
	    </form>
  <?php } ?>
    </td>
  </tr>
</table>
</body></html>