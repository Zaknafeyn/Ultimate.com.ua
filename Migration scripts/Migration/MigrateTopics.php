<?php


$enableLogging = 0;

require_once './MigrationConfig.php';
  
  //clear
  $q = "delete from ".$dbnameDest.".".$dbPrefixDest."topics";
  mysql_query($q, $ConnDest);
  
  //import
  $q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."topics";
  $result = mysql_query($q, $ConnSource);
  $orderId = 0;

  while($row = mysql_fetch_array($result))
  {	  
    //------------
    $firstPosterNameQuery =  "SELECT u.username
		    					FROM ".$dbnameSource.".".$dbPrefixSource."posts p,".
     			  					  $dbnameSource.".".$dbPrefixSource."users u
		   					   WHERE p.post_id = ".$row['topic_first_post_id'].
   		    					" AND p.poster_id = u.user_id";
					
	if ($enableLogging)
		echo $firstPosterNameQuery."<br>";
								
    $queryResult = mysql_query($firstPosterNameQuery, $ConnSource);
	$fetchRow = mysql_fetch_array($queryResult);
	$firstPosterName = $fetchRow['username'];
	//-----------
    $lastPosterNameQuery = "SELECT u.username
		    					FROM ".$dbnameSource.".".$dbPrefixSource."posts p,".
     			  					  $dbnameSource.".".$dbPrefixSource."users u
		   					   WHERE p.post_id = ".$row['topic_last_post_id'].
   		    					" AND p.poster_id = u.user_id";
						
	if ($enableLogging)
		echo $lastPosterNameQuery."<br>";
									
    $queryResult = mysql_query($lastPosterNameQuery, $ConnSource);
	$fetchRow = mysql_fetch_array($queryResult);
	$lastPosterName = $fetchRow['username'];
	//------------
    $lastPosterIdQuery = "SELECT p.poster_id
		    					FROM ".$dbnameSource.".".$dbPrefixSource."posts p
		   					   WHERE p.post_id = ".$row['topic_last_post_id'];
	if ($enableLogging)
		echo $lastPosterIdQuery."<br>";							   
							   
    $queryResult = mysql_query($lastPosterIdQuery, $ConnSource);
	$fetchRow = mysql_fetch_array($queryResult);
	$lastPosterId = $fetchRow['poster_id'];
	//-----------

    $InsertStatement = "INSERT INTO ".$dbnameDest.".".$dbPrefixDest."topics
            (`topic_id`,
             `forum_id`,
             `icon_id`,
             `topic_attachment`,
             `topic_approved`,
             `topic_reported`,
             `topic_title`,
             `topic_poster`,
             `topic_time`,
             `topic_time_limit`,
             `topic_views`,
             `topic_replies`,
             `topic_replies_real`,
             `topic_status`,
             `topic_type`,
             `topic_first_post_id`,
             `topic_first_poster_name`,
             `topic_first_poster_colour`,
             `topic_last_post_id`,
             `topic_last_poster_id`,
             `topic_last_poster_name`,
             `topic_last_poster_colour`,
             `topic_last_post_subject`,
             `topic_last_post_time`,
             `topic_last_view_time`,
             `topic_moved_id`,
             `topic_bumped`,
             `topic_bumper`,
             `poll_title`,
             `poll_start`,
             `poll_length`,
             `poll_max_options`,
             `poll_last_vote`,
             `poll_vote_change`)
VALUES (".
		$row['topic_id'].",".
		"(SELECT MAX(cat_id) FROM ".$dbnameSource.".".$dbPrefixSource."categories) + ".$row['forum_id'].",".
		//($row['forum_id'] + $categoryRowsCount).",".
		
        "0,".
        "0,".
        "1,".
        "0,".
		"'".$row['topic_title']."',".
        "'".$row['topic_poster']."',".
        $row['topic_time'].",".
        "0,".
        $row['topic_views'].",".
        $row['topic_replies'].",".
        "0,".
        $row['topic_status'].",".
        $row['topic_type'].",".
        $row['topic_first_post_id'].",".
		"'".$firstPosterName."',".
        "'',".
        $row['topic_last_post_id'].",".
		$lastPosterId.",".
		"'".$firstPosterName."',".
        "'',".
        "'',".
        "0,".
        "0,".
        "0,".
        "0,".
        "0,".
        "'',".
        "0,".
        "0,".
        "1,".
        "0,".
        "0)";
    
	 mysql_query($InsertStatement, $ConnDest);
	 
	if ($enableLogging)  
		echo "<br>".$InsertStatement."<br>";
  }
  

?>