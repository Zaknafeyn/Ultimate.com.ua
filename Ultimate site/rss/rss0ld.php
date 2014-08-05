<?php
	function abs_urls($s)
	{
		$s = preg_replace("/(<img[^>]*src=\"?)([^http|\"http|][^\s\"]*)(\"?[^>]*>)/","\\1http://www.frisbee.by\\2\\3",$s);
		$s = preg_replace("/(<a[^>]*href=\"?)([^http|\"http|][^\s\"]*)(\"?[^>]*>)/","\\1http://www.frisbee.by\\2\\3",$s);
		$s = preg_replace("/&(l|r)aquo;/","",$s);
		$s = preg_replace("/&nbsp;/"," ",$s);
		return $s;
	}

	$sql1 = "SELECT * FROM news ORDER BY date DESC LIMIT 0,15";
	$sql2 = "SELECT * FROM blog ORDER BY date DESC LIMIT 0,15";

	include "rss.inc";
	include $_SERVER['DOCUMENT_ROOT']."/tmpl/config.php";

	//if (isset($_REQUEST['feed'])) $feed = $_REQUEST['feed']; else $feed = '';

	//if (!$feed) exit;

	//if (!isset($feeds[$feed])) exit;



	$Rss= new CRss();

	$Rss->Title="* Frisbee.by";
	$Rss->Link="http://www.frisbee.by";
	$Rss->Copyright="© 2007—".date("Y")." frisbee.by";
	$Rss->Description="Алтимат фрисби (Utimate Frisbee) в Беларуси";
	$Rss->Category = "Спорт";
	$Rss->Language="ru";
	$Rss->ManagingEditor="frisbee@tut.by (Real Name)";
	$Rss->WebMaster="frisbee@tut.by (Real Name)";


	$Rss->Open($db_host,$db_name,$db_user,$db_pass);

	$news = array();
	$rst = $db->sql_query($sql1);
	while ($line = $db->sql_fetchrow($rst))
	{		$news[$line["date"]] =
			array(
				"title" => date("d.m.Y H:i",$line["date"]),
				"descr" => abs_urls(stripslashes($line["txt"]))."<br /><br />",
				"pdate" => date("r",$line["date"]),
				"link"  => "http://www.frisbee.by/news/".date("Y",$line["date"])."/#".$line["id"]
			);	}
	mysql_free_result($rst);
	$rst = $db->sql_query($sql2);
	while ($line = $db->sql_fetchrow($rst))
	{		$url = "http://www.frisbee.by/blog/".$line["id"]."/";		$photo = "";		if ($line["photo_small"])
		{			$photo  = "<p><a href=\"$url\"><img src=\"http://www.frisbee.by/blog/img/".$line["photo_small"]."\" style=\"border: none\" alt=\"".$line["photo_descr"]."\" /></a></p>";
			$photo .= "<p><a href=\"$url\">Вся статья &rarr;</a>";		}
		$news[$line["date"]] =
			array(
				"title" => abs_urls(stripslashes($line["title"])),
				"descr" => abs_urls(stripslashes($line["subtitle"])) . $photo . "<br /><br />",
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









