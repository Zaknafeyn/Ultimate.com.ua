<?
//    $title = "Блог";

	include_once $_SERVER['DOCUMENT_ROOT']."/site/tmpl/init.php";

	if (isset($_REQUEST['id'])) $id = digits_only($_REQUEST['id']); else $id=0;

    if ($id) {
	    // увеличиваем кол-во просмотров (даже если неверный id :)
	    //$db->sql_query("UPDATE blog SET views=views+1 WHERE id=$id");
	    $sql = "SELECT * FROM blog WHERE id=$id";
	    $rst = $db->sql_query($sql);
        if ($db->sql_affectedrows($rst)) {
	        $line = $db->sql_fetchrow($rst);
		    $title = stripslashes($line['title']);
        } else {
        	$id = 0;
        }
    }

	if (!$id)
		$title = "Блог";

	$meta_description = $title;
	$meta_keywords = $title . ($id?",".get_tags(MY_BLOG,$id):"");

	include_once $path_site."/tmpl/header.php";

/*
	$sql = "SELECT n.*, n.id AS nid, COUNT(c.id) AS comments, u.username
		FROM blog AS n
		LEFT JOIN comments AS c ON n.id=c.id_item AND c.id_category=".MY_NEWS."
		LEFT JOIN phpbb_users AS u ON n.id_forum_user=u.user_id
		GROUP BY n.id
		ORDER BY n.o DESC";
	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	$blog = array();
	while ($line = $db->sql_fetchrow($rst)) {
		$blog[]=$line;
		if ($line['id']==$id) $key1 = sizeof($blog)-1;
	}
*/
?>

<table cellspacing="0" cellpadding="15" border="0">
    <tr valign="top">
                	<? if ($id) {
                		$r = $db->sql_query("SELECT * FROM blog WHERE id=$id");
                		if ($db->sql_affectedrows($r)) $l = $db->sql_fetchrow($r);
						//$b = $blog[$key1];
                	?>
					<td width="70%">
                        <h1><?=stripslashes($l['title'])?></h1>
                        <?
                        $poster = $l['poster'];
						print "<p><div class=\"smalldate0\">";
						if ($poster_id = get_user_id_by_lj($db, $poster)) {
							print GetUserAvatar($poster_id,24,24)."&nbsp;".NameSurname($poster_id, true);
						} else {
							$poster_url = "http://$poster.livejournal.com";
							print "<a href=\"$poster_url\">$lj_user_img</a>&nbsp;<a href=\"$poster_url\">$poster</a>";
						}
						print ", ".make_human_date($line["date"]);
						print "</div>";
                        	/*
                        	if ($b['photo']) {
								$photo_title = $b['photo_descr']." ".$b['photo_author'];
	                        	print "<p><center><img src=\"/blog/img/".$b['photo']."\" alt=\"$photo_title\" title=\"$photo_title\" style=\"border: none\" /></center></p>";
	                        	print "<br />";
	                        }
	                        */
                        	print "<br /><p>".stripslashes($l['doc'])."</p>";
                        	print "<br /><p><a href=\"".$l['guid']."\">Комментарии</a></p>";

                            // навигация
                            /*
                            print "<table width=\"100%\"><tr><td width=\"50%\">";
                            if ($key1<sizeof($blog)-1)
                            	print "<p><div id=\"navigator\"><nobr><a href=\"/blog/".$blog[$key1+1]["id"]."/\">&larr;&nbsp;".$blog[$key1+1]["title"]."</a></nobr></div>";
                            print "</td><td width=\"50%\" style=\"text-align: right\">";
                            if ($key1>0)
                            	print "<p><div id=\"navigator\"><nobr><a href=\"/blog/".$blog[$key1-1]["id"]."/\">".$blog[$key1-1]["title"]."&nbsp;&rarr;</a></nobr></div>";
                            print "</td></tr></table>";
                            */
                        ?>
                    </td>

                    <? } ?>

	            	<td>
				        		<div class="vmenu">
				        			<p><div class="sel0"><h2>Блог</h2></div></p>
	                        <?
                         		$r = $db->sql_query("SELECT * FROM blog WHERE active=1 ORDER BY o DESC, date DESC");
                         		if ($db->sql_affectedrows($r)) while ($l=$db->sql_fetchrow($r)) {
                                    if ($l["id"]==$id) {
                                    	print "<p><div class=\"sel1\">";
                                    	print stripslashes($l["title"])."";
                                    } else {
                                    	print "<p><div class=\"sel0\">";
                                    	print "<a href=\"/blog/".$l["id"]."/\">".stripslashes($l["title"])."</a>";
                                    	//print "<br /><img src=\"/blog/img/".$b['photo_small']."\" vspace=\"5\" style=\"border: none\"/></a>";
                                    }
                                    print "<div class=\"small\" style=\"font-weight: normal;\">".make_human_date($l['date'])."</div>";
                                    print "</div></p>";
                                }
	                        ?>
	                        	</div>
	            	</td>
    </tr>
</table>

<?
	include_once $path_site."/tmpl/footer.php";
?>
