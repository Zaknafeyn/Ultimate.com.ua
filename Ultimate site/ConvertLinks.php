<?php

	$db_host = "localhost";
	$db_user = "root";//"ultimate_forum";//"ultimate_u";
	$db_pass = "root";//ultimateforum2010";//"lJHe2bs89";
	$db_name = "phpbb3";//ultimate_forum";//"ultimate_main";

	$link = mysql_connect($db_host, $db_user, $db_pass)
		or die("Could not connect.");		
	mysql_select_db($db_name) or die("Could not select database $db_name");

	$db->sql_query('SET character_set_database = cp1251');
	$db->sql_query('SET NAMES cp1251');
	
	$str = "select * from sale where doc like '%/sale/%'";
	$result = $db->sql_query($str);

	
while($row = $db->sql_fetchrow($result))
  {	  
  	$doc = $row['doc'];
  	  	
  	$doc = str_replace("/sale/","/site/sale/", $doc);
  	$doc = str_replace("ultimate.com.ua/sale/","ultimate.com.ua/site/sale/", $doc);
  	
  	$updateScript = "UPDATE sale ".
  	"set doc = '".$doc."' ".
  	"where id = ".$row['id'];

  	$db->sql_query($updateScript);
  	
  	//echo $updateScript;
  }

?>