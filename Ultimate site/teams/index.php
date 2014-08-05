<?php
//	$title = "Команды";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	$all_teams = "";
    $rst = $db->sql_query("SELECT * FROM teams ORDER BY team_rating DESC");
    while ($line = $db->sql_fetchrow($rst)) {
    	$all_teams .= ",".$line['team_name'];
    }


	if (isset($_REQUEST['a'])) {
		$a = $_REQUEST['a'];
	} else {
		$a = "";
	}
	if (isset($_REQUEST['e'])) {
		$e = $_REQUEST['e'];
	} else {
		$e = "";
	}
	if (isset($_REQUEST['tid'])) {
		$tid = $_REQUEST['tid'];
	} else {
		$tid = 0;
	}
	if (isset($_REQUEST['pid'])) {
		$pid = $_REQUEST['pid'];
	} else {
		$pid = 0;
	}
	$pg = "teams/1";
	if (!$a&&!$e&&!$tid&&!$pid) {
		$a = "bycity";
		$pg = "teams";
	}


    $nav = "<p><div class=\"vmenu\"><div class=\"small\">";
    if ($a == "bycity")
    	$nav .= "<div class=\"sel1\">По городам</div>";
    else
    	$nav .= "<div class=\"sel0\"><a href=\"/teams/\">По городам</a></div>";
/*
    if ($a == "rating")
    	$nav .= "<div class=\"sel1\">Рейтинг</div>";
    else
    	$nav .= "<div class=\"sel0\"><a href=\"/teams/rating/\">Рейтинг</a></div>";
*/
    if ($a == "roaster")
    	$nav .= "<div class=\"sel1\">Все игроки</div>";
    else
    	$nav .= "<div class=\"sel0\"><a href=\"/players/\">Все игроки</a></div>";
    $nav .= "</div></p>";
	$pg_extra = $nav;


	if ($a == "bycity") {
		include "_bycity.php";
	} elseif ($a == "rating") {
		include "_rating.php";
	} elseif ($a == "roaster") {
		include "_players.php";
	} else {
    	if ($tid) {
			include "_team.php";
		} elseif ($pid) {
			include "_player.php";
		}
	}

