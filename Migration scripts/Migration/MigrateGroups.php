<?php


require_once './MigrationConfig.php';
  $table_name = "groups";
  //clear 
  $q = "delete from ".$dbnameDest.".".$dbPrefixDest.$table_name." where group_id > ".$groupIdAdditional;
  mysql_query($q, $ConnDest);
  
  //import
  //$q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."groups";
  
  
  $q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource.$table_name." g WHERE (SELECT COUNT(*) FROM ".$dbnameSource.".".$dbPrefixSource."user_group ug WHERE ug.group_id = g.group_id) >= 1";
  //echo $q;
  //return;
  $result = mysql_query($q, $ConnSource);
  $orderId = 0;  

  while($row = mysql_fetch_array($result))
  {	  

    $InsertStatement = "INSERT INTO ".$dbnameDest.".".$dbPrefixDest."groups
            (`group_id`,
             `group_type`,
             `group_founder_manage`,
             `group_skip_auth`,
             `group_name`,
             `group_desc`,
             `group_desc_bitfield`,
             `group_desc_options`,
             `group_desc_uid`,
             `group_display`,
             `group_avatar`,
             `group_avatar_type`,
             `group_avatar_width`,
             `group_avatar_height`,
             `group_rank`,
             `group_colour`,
             `group_sig_chars`,
             `group_receive_pm`,
             `group_message_limit`,
             `group_max_recipients`,
             `group_legend`)
 		VALUES(".
  			($row['group_id'] + groupIdAdditional).",".
  			$row['group_type'].",".
  			"0,".
  			"0,".
			"'".$row['group_name']."',".
			"'".$row['group_description']."',".
  			"'',".
  			"7,".
  			"'',".
  			"0,".
			"'',".
			"0,".
			"0,".
			"0,".
			"0,".
			"'',".
			"0,".
			"0,".
			"0,".
			"0,".
			"0)";
  
  
	 mysql_query($InsertStatement, $ConnDest);
  
     //echo "<br>".$InsertStatement."<br>";
  }
  

?>