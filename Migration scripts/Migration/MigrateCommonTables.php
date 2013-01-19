<?php

  require_once './MigrationConfig.php';
  
  //clear ban list
  $q = "delete from ".$dbnameDest.".".$dbPrefixDest."banlist";
  mysql_query($q, $ConnSource);
  
  //import ban list
  $q = "SELECT a.* FROM ".$dbnameSource.".".$dbPrefixSource."banlist a";
  $result = mysql_query($q, $ConnSource);
  $orderId = 0;
  $rowsCount = 0;

  while($row = mysql_fetch_array($result))
  {	  

  	$banIp = $row['ban_ip'];
  	if ($banIp == null)
	{
	  $banIp = '';	
	}

    $InsertStatement = "INSERT INTO ".$dbnameDest.".".$dbPrefixDest."banlist
            (`ban_id`,
             `ban_userid`,
             `ban_ip`,
             `ban_email`,
             `ban_start`,
             `ban_end`,
             `ban_exclude`,
             `ban_reason`,
             `ban_give_reason`)
 		VALUES(".
  			$row['ban_id'].",".
  			$row['ban_userid'].",".
  			"'".banIp."',".
  			"'".$row['ban_email']."',".
  			"0,".
  			"0,".
  			"0,".
  			"'',".
  			"'')";
  
  
	 mysql_query($InsertStatement, $ConnDest);
  
     //echo "<br>".$InsertStatement."<br>";
  }
  
  
  
  
?>