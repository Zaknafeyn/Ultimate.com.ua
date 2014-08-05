<?php
	include_once $path_site."/tmpl/init.php";

    if ($pg == "index")
    	$index_page = "1"; // ну бл и тупость
    	
    //header('Location: http://www.example.com/');	
    	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="ru-ru" xml:lang="ru-ru"> 

<head>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
<meta http-equiv="content-style-type" content="text/css" /> 
<meta http-equiv="content-language" content="ru-ru" /> 
<meta http-equiv="imagetoolbar" content="no" /> 
<meta name="resource-type" content="document" /> 
<meta name="distribution" content="global" /> 
<meta name="description" content="" /> 
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9" /> 


<? if ($meta_keywords) { ?>
	<meta name="keywords" lang="ru" content="фрисби,фризби,алтимат,<?=$meta_keywords?>" />
<? } else { ?>
	<meta name="keywords" lang="ru" content="фрисби,фризби,алтимат,ultimate,frisbee" />
<? } ?>
<? if ($meta_description) { ?>
	<meta name="description" lang="ru" content="<?=$meta_description?>" />
<? } else { ?>
	<meta name="description" lang="ru" content="ultimate frisbee ukraine" />
<? } ?>
	<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<title><?=($title)? "$title" : $site_title?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $site_folder_prefix; ?>/css/stylesheet/stylesheet.css" />
<? if (isset($extra_css)) { foreach($extra_css as $css) {?>
	<link rel="stylesheet" type="text/css" media="screen" href="/css/<?=$css?>" />
<? } } ?>
	<link rel="icon" href="http://ultimate.com.ua/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="http://ultimate.com.ua/favicon.ico" type="image/x-icon">
	<link rel="alternate" type="application/rss+xml" title="Алтимат в Украине" href="/rss" />
	<script language="JavaScript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
	<script language="JavaScript" src="<?php echo $site_folder_prefix; ?>/js/js.js?3"></script>
</head>

<body marginwidth="0" marginheight="0">


<table width="100%" height="100%" align="center" id="center" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td>
			<noindex><? include_once $path_site."/tmpl/top_auth.php"; ?></noindex>
		</td>
	</tr>
	<tr>
		<td valign="top" height="1%">

		    <noindex>
		    <table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr height="100">
			    	<td width="20%" valign="top" align="left" height="1%">
						<div id="logo"><?=$index_page ? $logo_img."" : "<a href=\"/\">$logo_img</a>"?></div>
						<!--<div id="logo"><?=$index_page ? $logo_img."<br />ultimate.com.ua" : "<a href=\"/\">$logo_img<br />ultimate.com.ua</a>"?></div>-->
			    	</td>
			    	<td valign="top">
			    		<div id="topmenu-v">
			    		<ul>
<?
	if ($pg == "whatis")
		print "<li class=\"selected\">Что такое фризби?</li>";
	else
		print "<li><a href=\"$site_folder_prefix/man/ultimate/about/\">Что такое фризби?</a></li>";

	if ($pg == "practice")
		print "<li class=\"selected\">Где поиграть?</li>";
	elseif ($pg == "practice/1")
		print "<li class=\"selected\"><a href=\"/site/practice/\">Где поиграть?</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/practice/\">Где поиграть?</a></li>";

	if ($pg == "last")
		print "<li class=\"selected\">Новое на сайте</li>";
	elseif ($pg == "last/1")
		print "<li class=\"selected\"><a href=\"/site/last/day/\">Новое на сайте</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/last/day/\">Новое на сайте</a></li>";

	/*
	$d = time()-4*60*60;
	$r = $db->sql_query("SELECT dat FROM rssnews WHERE dat>".mktime(0,0,1,date("m",$d),date("d",$d),date("Y",$d)));
	if ($db->sql_affectedrows($r)) {
		$updates = "&nbsp;<span style=\"color: silver;\"><sup>+".$db->sql_affectedrows($r)."</sup></span>";
	}
	if ($pg == "worldnews")
		print "<li class=\"selected\">Новое в&nbsp;мире$updates</li>";
	else
		print "<li><a href=\"/worldnews/\">Новое в&nbsp;мире</a>$updates</li>";
	*/
?>
						</ul>
			    		</div>
			    	</td>

			    	<td valign="top" align="left">
			    		<div id="topmenu-h">
			    		<ul>
<?
	if ($pg == "tourn")
		print "<li class=\"selected\">Турниры</li>";
	elseif ($pg == "tourn/1")
		print "<li class=\"selected\"><a href=\"$site_folder_prefix/tourn/\">Турниры</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/tourn/\">Турниры</a></li>";

	if ($pg == "teams")
		print "<li class=\"selected\">Команды</li>";
	elseif ($pg == "teams/1")
		print "<li class=\"selected\"><a href=\"$site_folder_prefix/teams/\">Команды</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/teams/\">Команды</a></li>";

	if ($pg == "man")
		print "<li class=\"selected\">Учебник</li>";
	elseif (($pg == "man/1")||($pg == "whatis"))
		print "<li class=\"selected\"><a href=\"$site_folder_prefix/man/\">Учебник</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/man/\">Учебник</a></li>";

	if ($pg == "photo")
		print "<li class=\"selected\">Фото</li>";
	elseif ($pg == "photo/1")
		print "<li class=\"selected\"><a href=\"$site_folder_prefix/photo/love/\">Фото</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/photo/love/\">Фото</a></li>";

	if ($pg == "video")
		print "<li class=\"selected\">Видео</li>";
	elseif ($pg == "video/1")
		print "<li class=\"selected\"><a href=\"$site_folder_prefix/video/\">Видео</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/video/\">Видео</a></li>";

	if ($pg == "sale")
		print "<li class=\"selected\">Магазин</li>";
	elseif ($pg == "sale/1")
		print "<li class=\"selected\"><a href=\"$site_folder_prefix/sale/\">Магазин</a></li>";
	else
		print "<li><a href=\"$site_folder_prefix/sale/\">Магазин</a></li>";

	print "<li><a href=\"/".$forum_folder_name."/\">Форум</a></li>";

					    			?>
						</ul>
<? if ($pg_extra) { ?>
						<div class="clear"></div><ul><li><span style="color: #fff;">Турниры</span></li><li><?=$pg_extra?></li></ul>
<? } ?>
						</div>
					</td>
			    </tr>
			</table>
			</noindex>
		</td>
	</tr>
	<tr height="40"><td>&nbsp;</td></tr>
	<tr>
		<td valign="top" height="98%">

