
<?php
	include ("c_config.php");
	$mid = $_REQUEST['mid'];
  if (!$mid) exit;
  $id_user = sp_room($mid);
  if ($id_user == -1) exit;
	$name = get_user_param($id_user,"name");
	$bg = get_user_param($id_user,"bg");
	$top2bot = (get_user_param($id_user,"top2bot")=="0") ? false : true;
?>

<html>
<head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link rel="stylesheet" href="style.css" type="text/css">
<style>
body { font-size: 18px; }
p {font-family:Tahoma;font-size:13px; margin-top: 0; margin-bottom: 5;}
a {color: black;font-size: 12px;font-weight: bold;font-family: tahoma;}
a.nav {color: black; font-size: 10px; text-decoration: none;}
</style>
</head>
<body>
    <?php
    	$day = $_REQUEST['day'];
	    $sql = "SELECT * FROM my4_messages WHERE mtime BETWEEN $day AND " . ($day+24*60*60);
	    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
      $msgs = array();
      while ($line=$db->sql_fetchrow($rst, MYSQL_ASSOC)) {
      	$private = $line['private'];

      	$who = preg_replace("/OnClick.*sn2.*;\);/i","",$line['who']);
      	$whom = preg_replace("/OnClick.*sn2.*;\);/i","",$line['whom']);
      	$message = $line['message'];

	      if ( ($bg) && (preg_match("/<z.?>$name<\/z.?>/i",$whom)) )
	        $who = preg_replace("/(<b><u>[^\s]*?<\/u><\/b>)/i", "<font size=+2>\\1</font>", $who);
	      $message = $who.$whom.$message;
	      if ($private) {
	        if (preg_match("/<zz>$name<\/zz>/i",$whom) || preg_match("/<b><u>$name<\/u><\/b>/i",$who)) {
          	$message = "<font size=-1 color=yellow>*шепотом*&nbsp;</font>".$message;
	          array_push($msgs, $message);
	        }
	      } else {
          array_push($msgs, $message);
	      }
      }


    if ($top2bot) {
	for ($i=0; $i<sizeof($msgs); $i++) {
	    print $msgs[$i]."<br>";
	}
    } else {
	for ($i=sizeof($msgs)-1; $i>=0; $i--) {
	    print $msgs[$i]."<br>";
	}
    }


    ?>
</body>
</html>