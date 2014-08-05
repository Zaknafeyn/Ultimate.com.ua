<?php
	$table_name = "user_group";
//clear users
  //echo "dont forget uncomment where necessary";
  $q = "delete from ".$dbnameDest.".".$dbPrefixDest.$table_name." where group_id > ".$groupIdAdditional. " OR user_id > ".$userIdAdditional;
  mysql_query($q, $ConnDest);

  //import user groups
  $q = "SELECT ug.* 
  FROM ".$dbnameSource.".".$dbPrefixSource.$table_name. " ug,".
    $dbnameSource.".".$dbPrefixSource."groups g
 WHERE g.group_id = ug.group_id
  AND g.group_single_user = 0";
  echo "---$q---";
  $result = mysql_query($q, $ConnSource);
$i =0;
  while($row = mysql_fetch_array($result))
  {	  
	$i++;
    $InsertStatement = "INSERT INTO ".$dbnameDest.".".$dbPrefixDest.$table_name.
    	"(
    		`group_id`,
  			`user_id`,
  			`group_leader`,
  			`user_pending`
    	)
    	VALUES
    	(".
    	($row['group_id'] + $groupIdAdditional).",".
    	($row['user_id'] + $userIdAdditional).",".
    	"0,".
    	"0
    	)";
    
    	//echo $InsertStatement;
    	 mysql_query($InsertStatement, $ConnDest);
	}
echo $i;
?>