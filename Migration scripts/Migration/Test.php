<?php

	$str = "select * from phpbb_posts where post_id = 4";
	$res = mysql_query($str, $ConnDest);
	$row = mysql_fetch_array($res);
	echo "post_id = ".$row['post_id'];
	
	/*
	  $str = "INSERT INTO phpbb_posts (`post_id`, `topic_id`, `forum_id`, `poster_id`, `icon_id`, `poster_ip`, `post_time`, `post_approved`, `post_reported`, `enable_bbcode`, `enable_smilies`, `enable_magic_url`, `enable_sig`, `post_username`, `post_subject`, `post_text`, `post_checksum`, `post_attachment`, `bbcode_bitfield`, `bbcode_uid`, `post_postcount`, `post_edit_time`, `post_edit_reason`, `post_edit_user`, `post_edit_count`, `post_edit_locked`) VALUES (5,4,1,2,0,'55caa138',1152494925,1,0,1,1,1,1,'','Тупо всякий гон','И чё все сидят? :) Пишем сюда всякую хрень','c103ba6685032d509e859f5216e9cf7b',0,'','12cb4c6fe4',1,0,'',0,0,0)";
	  */
  echo "1";	  
  $str = "INSERT INTO `new_forum`.`phpbb_ranks`
            (`rank_id`,
             `rank_title`,
             `rank_min`,
             `rank_special`,
             `rank_image`)
VALUES (22,
        'rank_title',
        3,
        3,
        'rank_image')";	  
		  echo "2";
  mysql_query($str, $ConnDest);
	  echo "3";
?>