<?php
/*
    $DOC_ROOT = dirname(__FILE__)."/";
		include_once $DOC_ROOT."tmpl/config.php";
		include_once $DOC_ROOT."tmpl/functions.php";
		//include_once $DOC_ROOT."tmpl/init.php";
		mysql_connect($db_host,$db_user,$db_pass);
		mysql_select_db($db_name);
*/



    include $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";
  
  $feeds = array(
    "http://community.livejournal.com/ua_ultimate/data/rss",
    "http://gigolo-strela.livejournal.com/data/rss"
  );
  


    foreach($feeds as $feed) {
	if ($xml = simplexml_load_file($feed)) {
		foreach($xml->channel->item as $post) {
			if (strstr($feed, 'community')) {
			    $poster = $post->children('http://www.livejournal.org/rss/lj/1.0/')->poster;
			} else {
			    $poster = $xml->channel->children('http://www.livejournal.org/rss/lj/1.0/')->journal;
			}
			print "<p>guid: ".$post->guid;
			print "<br />".$post->title;
			print "<br />$poster";
			if ($post->guid) {
			    print "<br />num_rows=".$db->sql_affectedrows($db->sql_query("SELECT id FROM blog WHERE guid='".$post->guid."'"));
				if ($db->sql_affectedrows($db->sql_query("SELECT id FROM blog WHERE guid='".$post->guid."'"))) {
				    print "<br />update";
					$db->sql_query("UPDATE blog SET
						date=".strtotime($post->pubDate).",
						poster='".mysql_escape_string($poster)."',
						title='".mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$post->title))."',
						doc='".mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$post->description))."'
						WHERE guid='".mysql_escape_string($post->guid)."'
					");
				} else {
					//$r = $db->sql_query("SELECT max(o) FROM blog");
					//$l = $db->sql_fetchrow($r);
					//$o = $l[0]+1;
					print "<br />insert";
					$db->sql_query("INSERT INTO blog SET
						id=".digits_only($post->guid).",
						guid='".mysql_escape_string($post->guid)."',
						date=".strtotime($post->pubDate).",
						poster='".mysql_escape_string($poster)."',
						title='".mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$post->title))."',
						doc='".mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$post->description))."',
						o=".strtotime($post->pubDate)."
					");
					print "<br />".mysql_error();
				}
			}
		}
	}
    }
?>
