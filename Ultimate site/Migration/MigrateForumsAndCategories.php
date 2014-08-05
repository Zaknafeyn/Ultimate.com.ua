<?php


  //clear forums table in dest
  $deleteClause = "delete from ".$dbnameSource.".".$dbPrefixDest."forums";
  mysql_query($deleteClause, $ConnDest);

//import categories  
  $q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."categories";
  $result = mysql_query($q, $ConnSource);
  $orderId = 1;
  
   $categoryCountQuery =  "SELECT max(cat_id) as cnt
		    					FROM ".$dbnameSource.".".$dbPrefixSource."categories";
					
	if ($enableLogging)
		echo $categoryCountQuery."<br>";
								
    $queryResult = mysql_query($categoryCountQuery, $ConnSource);
	$fetchRow = mysql_fetch_array($queryResult);
    $categoryRowsCount = $fetchRow['cnt'];
	
	if ($enableLogging)
	  echo "categoryRowsCount = ".$categoryRowsCount."<br>";

  while($row = mysql_fetch_array($result))
  {	
    $forumsCountQuery = "select count(*) as cnt from ".$dbnameSource.".".$dbPrefixSource."forums where cat_id = ".$row['cat_id'];
	$queryResult = mysql_query($forumsCountQuery, $ConnSource);
	$r = mysql_fetch_array($queryResult);
    $forumsCnt = $r['cnt'];
  
	$InsertStatement = "INSERT INTO ".$dbnameDest.".".$dbPrefixDest."forums
            (`forum_id`,
             `parent_id`,
             `left_id`,
             `right_id`,
             `forum_parents`,
             `forum_name`,
             `forum_desc`,
             `forum_desc_bitfield`,
             `forum_desc_options`,
             `forum_desc_uid`,
             `forum_link`,
             `forum_password`,
             `forum_style`,
             `forum_image`,
             `forum_rules`,
             `forum_rules_link`,
             `forum_rules_bitfield`,
             `forum_rules_options`,
             `forum_rules_uid`,
             `forum_topics_per_page`,
             `forum_type`,
             `forum_status`,
             `forum_posts`,
             `forum_topics`,
             `forum_topics_real`,
             `forum_last_post_id`,
             `forum_last_poster_id`,
             `forum_last_post_subject`,
             `forum_last_post_time`,
             `forum_last_poster_name`,
             `forum_last_poster_colour`,
             `forum_flags`,
             `forum_options`,
             `display_subforum_list`,
             `display_on_index`,
             `enable_indexing`,
             `enable_icons`,
             `enable_prune`,
             `prune_next`,
             `prune_days`,
             `prune_viewed`,
             `prune_freq`)
	VALUES(".
		$row['cat_id'].",".
		"0,".
		$orderId."," .
		($orderId + $forumsCnt * 2 + 1) . "," .
		"''," .	
		"'".$row['cat_title'] . "'," .
		"''," .	
		"''," .	
		"7," .
		"0," .
		"''," .	
		"''," .	
		"0," .
		"''," .	
		"''," .	
		"''," .			
		"0," .
		"7," .
		"0," .
		"0," .
		"0," .
		"0," .		
		"1," .
		"0," .
		"0," .
		"0," .
		"0," .	
		"''," .	
		"0," .		
		"''," .	
		"''," .		
		"32," .		
		"0," .		
		"1," .
		"1," .
		"1," .
		"1," .
		"0," .
		"0," .
		"0," .
		"0," .
		"0
		)";
				
		echo "<br><hr>category insert<br>".$InsertStatement;
				
		mysql_query($InsertStatement, $ConnDest);
		
		$orderId += 1;

		//------------
		
		//import forums
		$q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."forums where cat_id = ".$row['cat_id'];
		$subResult = mysql_query($q, $ConnSource);
		
	  
		while ($forumRow = mysql_fetch_array($subResult ))
		{
			
		   $pruneEnable = $forumRow ['prune_enable'];
		   if ( $pruneEnable == null)
		   {
			   $pruneEnable = 0;
		   }
		   
		   $pruneNext = $forumRow ['prune_next'];
		   if ( $pruneNext == null)
		   {
			   $pruneNext = 0;
		   }
		   
			
		  $InsertStatement = 
		  "INSERT INTO ".$dbnameDest.".".$dbPrefixDest."forums
				  (`forum_id`,
				   `parent_id`,
				   `left_id`,
				   `right_id`,
				   `forum_parents`,
				   `forum_name`,
				   `forum_desc`,
				   `forum_desc_bitfield`,
				   `forum_desc_options`,
				   `forum_desc_uid`,
				   `forum_link`,
				   `forum_password`,
				   `forum_style`,
				   `forum_image`,
				   `forum_rules`,
				   `forum_rules_link`,
				   `forum_rules_bitfield`,
				   `forum_rules_options`,
				   `forum_rules_uid`,
				   `forum_topics_per_page`,
				   `forum_type`,
				   `forum_status`,
				   `forum_posts`,
				   `forum_topics`,
				   `forum_topics_real`,
				   `forum_last_post_id`,
				   `forum_last_poster_id`,
				   `forum_last_post_subject`,
				   `forum_last_post_time`,
				   `forum_last_poster_name`,
				   `forum_last_poster_colour`,
				   `forum_flags`,
				   `forum_options`,
				   `display_subforum_list`,
				   `display_on_index`,
				   `enable_indexing`,
				   `enable_icons`,
				   `enable_prune`,
				   `prune_next`,
				   `prune_days`,
				   `prune_viewed`,
				   `prune_freq`)
	   
	  VALUES (".
		  ($forumRow ["forum_id"] + $categoryRowsCount).",".
		  $forumRow ["cat_id"].",".
		  $orderId.",".
		  ($orderId + 1).",".
		  "'',".
		  "'".$forumRow ['forum_name']."',".
		  "'".$forumRow ['forum_desc']."',".
		  "'',".
		  "7,".
		  "0,".
		  "'',".
		  "'',".
		  "0,".
		  "'',".
		  "'',".
		  "'',".
		  "0,".
		  "7,".
		  "0,".
		  "10,".
		  "1,".
		  $forumRow ['forum_status'].",".
		  $forumRow ['forum_posts'].",".
		  $forumRow ['forum_topics'].",".
		  $forumRow ['forum_topics'].",".
		  $forumRow ['forum_last_post_id'].",".
		  "0,".
		  "'',".
		  "0,".
		  "'',".
		  "'',".
		  "0,".
		  "0,".
		  "1,".
		  "1,".
		  "1,".
		  "1,".
		  $pruneEnable.",".
		  $pruneNext.",".
		  "7,".
		  "7,".
		  "1)";
		
		$orderId += 2;
		
		echo "<br>".$InsertStatement;
		
		mysql_query($InsertStatement, $ConnDest);			
		
		//------------
		
		//echo "<br>".$InsertStatement."<br>";
		
		$orderId += 1;// ($forumsCnt * 2 + 2);
		$categoryRowsCount ++;
        }
  }

/*
//import forums
  $q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."forums";
  $result = mysql_query($q, $ConnSource);
  

  while ($row = mysql_fetch_array($result))
  {
	  
	 $pruneEnable = $row['prune_enable'];
	 if ( $pruneEnable == null)
	 {
		 $pruneEnable = 0;
	 }
	 
	 $pruneNext = $row['prune_next'];
	 if ( $pruneNext == null)
	 {
		 $pruneNext = 0;
	 }
	  
	$InsertStatement = 
	"INSERT INTO ".$dbnameDest.".".$dbPrefixDest."forums
            (`forum_id`,
             `parent_id`,
             `left_id`,
             `right_id`,
             `forum_parents`,
             `forum_name`,
             `forum_desc`,
             `forum_desc_bitfield`,
             `forum_desc_options`,
             `forum_desc_uid`,
             `forum_link`,
             `forum_password`,
             `forum_style`,
             `forum_image`,
             `forum_rules`,
             `forum_rules_link`,
             `forum_rules_bitfield`,
             `forum_rules_options`,
             `forum_rules_uid`,
             `forum_topics_per_page`,
             `forum_type`,
             `forum_status`,
             `forum_posts`,
             `forum_topics`,
             `forum_topics_real`,
             `forum_last_post_id`,
             `forum_last_poster_id`,
             `forum_last_post_subject`,
             `forum_last_post_time`,
             `forum_last_poster_name`,
             `forum_last_poster_colour`,
             `forum_flags`,
             `forum_options`,
             `display_subforum_list`,
             `display_on_index`,
             `enable_indexing`,
             `enable_icons`,
             `enable_prune`,
             `prune_next`,
             `prune_days`,
             `prune_viewed`,
             `prune_freq`)
 
VALUES (".
  	($row["forum_id"] + $categoryRowsCount).",".
  	$row["cat_id"].",".
	$orderId.",".
	($orderId + 1).",".
	"'',".
	"'".$row['forum_name']."',".
	"'".$row['forum_desc']."',".
	"'',".
	"7,".
	"0,".
	"'',".
	"'',".
	"0,".
	"'',".
	"'',".
	"'',".
	"0,".
	"7,".
	"0,".
	"10,".
	"1,".
	$row['forum_status'].",".
	$row['forum_posts'].",".
	$row['forum_topics'].",".
	$row['forum_topics'].",".
	$row['forum_last_post_id'].",".
	"0,".
	"'',".
	"0,".
	"'',".
	"'',".
	"0,".
	"0,".
	"1,".
	"1,".
	"1,".
  	"1,".
	$pruneEnable.",".
	$pruneNext.",".
	"7,".
	"7,".
  	"1)";
  
  $orderId += 2;
  
  //echo "<br>".$InsertStatement."<br>";
  
  mysql_query($InsertStatement, $ConnDest);
  
  }
*/


?>