<?php
  
  //clear posts
  $q = "delete from ".$dbnameDest.".".$dbPrefixDest."posts";
  mysql_query($q, $ConnDest);
  
  //import posts
  $q = "SELECT * FROM ".$dbnameSource.".".$dbPrefixSource."posts p, ".$dbnameSource.".".$dbPrefixSource."posts_text pt WHERE p.post_id = pt.post_id AND forum_id = 1";
  
  //!!!!!!!!!!!!!!!!!!!!!! Delete AND statement
  echo "Ne zabyt udalit AND statement";
  
  $result = mysql_query($q, $ConnSource);
  $orderId = 0;
  $rowsCount = 0;

  $resultMaxCatId = mysql_query("select max(cat_id) as maxCatId from ".$dbnameSource.".".$dbPrefixSource."categories", $ConnSource);
  echo "select max(cat_id) as maxCatId from ".$dbnameSource.".".$dbPrefixSource."categories";
  $catRow = mysql_fetch_array($resultMaxCatId);
  $maxCatId = $catRow['MaxCatId'];


  $cntPosts = 0;

  while($row = mysql_fetch_array($result))
  {	  
/*
  echo "<br>".$cntPosts."<br>";
     $cntPosts++;
	 if ( $cntPosts == 1000)
	 break;
*/
  	$postUsername =  $row['post_username'];
	if ($postUsername == null)
	{
	  $postUsername = '';	
	}

	$postEditTime = $row['post_edit_time'];
	if ($postEditTime == null)
	{
		$postEditTime = 0;	
	}

    $InsertStatement = "INSERT INTO ".$dbnameDest.".".$dbPrefixDest."posts
            (`post_id`,
             `topic_id`,
             `forum_id`,
             `poster_id`,
             `icon_id`,
             `poster_ip`,
             `post_time`,
             `post_approved`,
             `post_reported`,
             `enable_bbcode`,
             `enable_smilies`,
             `enable_magic_url`,
             `enable_sig`,
             `post_username`,
             `post_subject`,
             `post_text`,
             `post_checksum`,
             `post_attachment`,
             `bbcode_bitfield`,
             `bbcode_uid`,
             `post_postcount`,
             `post_edit_time`,
             `post_edit_reason`,
             `post_edit_user`,
             `post_edit_count`,
             `post_edit_locked`)
VALUES (".
		$row['post_id'].",".
        $row['topic_id'].",".
        ($row['forum_id'] + $maxCatId).",".
        $row['poster_id'].",".
        "0,".
        "'".$row['poster_ip']."',".
        $row['post_time'].",".
        "1,".
        "0,".
        $row['enable_bbcode'].",".
        $row['enable_smilies'].",".
        "1,".
        $row['enable_sig'].",".
        "'".$postUsername ."',".
        "'".$row['post_subject']."',".
        "'".$row['post_text']."',".
        "'".md5($row['post_text'])."',".
        "0,".
        "'',".
        "'".$row['bbcode_uid']."',".
        "1,".
        $postEditTime.",".
        "'',".
        "0,".
        $row['post_edit_count'].",".
        "0)";
  
  
	 mysql_query($InsertStatement, $ConnDest);
  
     //echo "<br>".$InsertStatement."<br>";
  }

?>