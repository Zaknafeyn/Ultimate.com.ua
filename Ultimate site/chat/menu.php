<?php
	include ("c_config.php");
	if (getenv("REQUEST_METHOD")!='GET') exit;
	if (isset($_GET['mid'])) {
  	$mid = $_GET['mid'];
    $id_user = sp_room($mid);
    $name = get_user_param($id_user, "name");
  }	else $id_user=-1;

	if (!isset($id_user)) $id_user=-1;
	if ($id_user == -1) sp_pe(1);

    $sql = "SELECT lltime FROM my4_log WHERE id_user=$id_user";
    $rst = $db->sql_query($sql);
    $line = $db->sql_fetchrow($rst, MYSQL_NUM);
    $user_last_enter_time = $line[0];

        $sql = "SELECT COUNT(*) FROM my4_board WHERE mtime>$user_last_enter_time";
    $rst = $db->sql_query($sql);
    $line = $db->sql_fetchrow($rst, MYSQL_NUM);
    $new_board_messages = $line[0];
?>
<html><head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link rel="stylesheet" href="style.css" type="text/css">
<style>
body { background-color:#7DCF23; margin-top: 0; margin-bottom: 0px; }
a{ color:white; font-weight: bold; text-decoration: none; font-size: 12px; }
a:hover { text-decoration: underline;}
</style>
</head>
<body>
	<table cellspacing=1 cellpadding=0 border=0 width=100%>
  	<tr><td align=center>
    	<span style="font-size: 12px; ">
        <img src="images/connect/stat_ok.gif" name="connection_status" width="8" height="8">&nbsp;|&nbsp;
        <a href="smiles.php?page=1" onclick="window.open('smiles.php?page=1', '_smiles', 'resizable=yes,scrollbars=yes');return false;" target="_smiles"><?php print $str_bottom_more_smiles; ?></a>&nbsp;|&nbsp;
	    <a href=user_cnf.php?mid=<?php echo $mid; ?> target=MyConf><?php echo $str_bottom_settings; ?></a>&nbsp;|&nbsp;
	    <a href=info.php?mid=<?php echo $mid; ?>&all=1&uid=&user= target=userinfoframe><b><?php print $str_bottom_users; ?></b></font></a>&nbsp;|&nbsp;
	    <a href=board.php?mid=<?php echo $mid; ?>&page=&msg= target=Board><font color=#6666FF><b><?php print $str_bottom_board; ?></b></font><?php if ($new_board_messages) print " ($new_board_messages)"; ?></a>&nbsp;|&nbsp;
	    <?php if (get_user_param($id_user,"rights")!="guest") { ?>
		    <a href=admin.php?mid=<?php echo $mid; ?>&words= target=adminka><font color=red><b><?php print $str_bottom_admin; ?></b></font></a>&nbsp;|&nbsp;
	    <?php } ?>
	    <a href=archive.php?mid=<?php echo $mid; ?>&day= target=archive><b><?php print $str_bottom_archive; ?></b></a>
    	</span>
    </td></tr>
  </table>
</body></html>