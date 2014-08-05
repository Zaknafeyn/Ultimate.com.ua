<?php
	function abs_urls($s)
	{
		$s = preg_replace("/(<img[^>]*src=\"?)([^http|\"http|][^\s\"]*)(\"?[^>]*>)/","\\1http://ultimate.com.ua\\2\\3",$s);
		$s = preg_replace("/(<a[^>]*href=\"?)([^http|\"http|][^\s\"]*)(\"?[^>]*>)/","\\1http://ultimate.com.ua\\2\\3",$s);
		$s = preg_replace("/&(l|r)aquo;/","",$s);
		$s = preg_replace("/&nbsp;/"," ",$s);
		return $s;
	}

	//$sql1 = "SELECT * FROM news ORDER BY date DESC LIMIT 0,15";
	$sql2 = "SELECT * FROM blog ORDER BY date DESC LIMIT 0,15";

	include "rss.inc";
	include $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	//if (isset($_REQUEST['feed'])) $feed = $_REQUEST['feed']; else $feed = '';

	//if (!$feed) exit;

	//if (!isset($feeds[$feed])) exit;



	$Rss= new CRss();

	$Rss->Title="Алтимат в Украине";
	$Rss->Link="http://ultimate.com.ua";
	$Rss->Copyright="© 2007—".date("Y")." frisbee.by";
	$Rss->Description="Алтимат фрисби (Utimate Frisbee) в Украине";
	$Rss->Category = "Спорт";
	$Rss->Language="ru";
	$Rss->ManagingEditor="admin@ultimate.com.ua * ultimate.com.ua";
	$Rss->WebMaster="admin@ultimate.com.ua * ultimate.com.ua";


	$Rss->Open($db_host,$db_name,$db_user,$db_pass);

	$news = array();
	/*
	$rst = $db->sql_query($sql1);
	while ($line = $db->sql_fetchrow($rst))
	{		$title = preg_replace("/&[0-9a-z#]+;/"," ",strip_tags(stripslashes($line["txt"])))." ";
		if ( strlen($title) > 75 ) $title = substr($title, 0, strpos($title, " ", 75)) . " ...";
		$news[$line["date"]] =
			array(
				"title" => $title,
				"descr" => abs_urls(stripslashes($line["txt"]))."<br /><br />",
				"pdate" => date("r",$line["date"]),
				"link"  => "http://www.frisbee.by/news/".date("Y",$line["date"])."/#".$line["id"]
			);	}
	mysql_free_result($rst);
	*/
	$rst = $db->sql_query($sql2);
	while ($line = $db->sql_fetchrow($rst))
	{		$url = "http://ultimate.com.ua/blog/".$line["id"]."/";		$photo = "";		$news[$line["date"]] =
			array(
				"title" => abs_urls(stripslashes($line["title"])),
				"descr" => abs_urls(stripslashes($line["doc"])),
				"pdate" => date("r",$line["date"]),
				"link"  => $url
			);
	}
	mysql_free_result($rst);

	krsort($news);
	$keys = array_keys($news);

	$Rss->LastBuildDate=date("r",$keys[0]);
	$Rss->PubDate=$Rss->LastBuildDate;

 	$Rss->PrintHeader();

	$news = array_slice($news,0,15);
	foreach ($news as $n)
	{
	    $Rss->PrintBody($n["title"],$n["link"],$n["descr"],"",$n["pdate"]);
	}

        $Rss->PrintFooter();
	$Rss->Close();

?>









