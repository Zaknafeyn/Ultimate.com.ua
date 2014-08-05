<?
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	$site_root_folder = "";
	if ($site_folder_name != "")
	{
	  $site_root_folder .= "/".$site_folder_name;
	}
	
	if (isset($_GET['id']))
	{ 
		$id = digits_only($_GET['id']);
		echo "Id is set and equals to ".$_GET['id'];
	} 
	else
	{
		$id=0;	
	}

	$sql = "SELECT * FROM video ORDER BY dateadded ASC";
	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	$v = array();
	while ($line = $db->sql_fetchrow($rst)) {
		array_push($v,$line);
		if ($line['id']==$id) {
			$num = sizeof($v);
			$title = stripslashes($line['title']);
			echo $line."<br>";
		}
	}

	define("ROWS", 5);
	$num_pages = ceil(sizeof($v)/ROWS);
	if (isset($_GET['page'])) {
		echo "Page is set end euqals to ".$_GET['page']."<br>";
		$page = digits_only($_GET['page']);
	} else {
		if ($id) {
			$page = ceil($num/ROWS);
		} else
			$page = $num_pages;
    }

	if (!$id) {
		$title = "Видео-ролики";
		$pg = "video";
	} else {
		//$title = "Видео";
		$pg = "video/1";
	}
	include_once $path_site."/tmpl/header.php";

?>

<table width="100%" cellspacing="0" cellpadding="15" border="0">
	<tr valign="top">
		<td width="70%">
			<h1><?=$title?></h1><p><br /></p>
                                    	<?
	                                        if (($page<1)||($page>$num_pages)) $page = $num_pages;
	                                        if ($num_pages>1) {
	                                            $nav = "<p><table cellspacing=\"0\" cellpadding=\"3\"><td><p style=\"padding-top: 5;\">Страницы: </p></td>"; // навигация
	                                            for ($i=$num_pages;$i>0;$i--) {
	                                            	$url_video_page = $site_root_folder."/video/page".$i."/";
	                                            	$url="<p style=\"padding-top: 5;\"><a href=\"".$url_video_page."\">&nbsp;$i&nbsp;</a></p>";
	                                         		if ($i == $page) {
	                                         			if ($id) {
		                                             		    $nav .= "<td bgcolor=\"#f0f0ff\">$url</td>";
	                                         			} else {
		                                             		    $nav .= "<td bgcolor=\"#f0f0ff\"><p style=\"padding-top: 5;\">&nbsp;$i&nbsp;</p></td>";
	                                         			}
	                                         		} else {
		                                             	$nav .= "<td>$url</td>";
	                                         		}
	                                            }
	                                            $nav .= "</tr></table></p><br />";
	                                            print $nav;
	                                     	}
                                        	if ($id) {
                                        		$i1=$i2=$num-1;
                                            } else {
                                            	$i2 = $page*ROWS-1;
                                            	$i1 = ($page-1)*ROWS;
                                        		if ($i2>(sizeof($v)-1)) {
                                        			$i1 -= ($i2-(sizeof($v)-1));
                                        			$i2 = sizeof($v)-1;
                                        		}
	                                        }

                                            for ($i=$i2;$i>=$i1;$i--) {
                                            	$line = $v[$i];
	                                        	print "<p>";
	                                        	$url_video_page = $site_root_folder."/video/".$line['id']."/";
	                                        	if (!$id) print "<a id=\"".$line['id']."\" name=\"".$line['id']."\"></a><h4><a href=\"".$url_viddeo_page."\">".stripslashes($line['title'])."</a></h4></p><p>";
	                                        	print "<div class=\"small\">";
	                                            print ($line['author']) ? "<b>Автор</b>: ".stripslashes($line['author'])."<br />" : "";
	                                            print ($line['url']) ? "<b>Ссылка</b>: <a href=\"".$line['url']."\">".$line['url']."</a><br />" : "";
	                                            print ($line['description']) ? "<b>Краткое описание</b>: ".stripslashes($line['description'])."<br />" : "";
	                                            $sql1 = "SELECT COUNT(id) as cnt FROM comments WHERE id_category=".MY_VIDEO." AND id_item=$line[id]";
	                                            $rst1 = $db->sql_query($sql1) or die("<p>$sql1<p>".mysql_error());
	                                            $line1 = $db->sql_fetchrow($rst1);
	                                            if ($line1['cnt'])
	                                            	print "<a href=\"".$url_video_page."\">".$line1['cnt']." ".num_decline($line1['cnt'],"комментарий","комментария","комментариев")."</a>";
	                                            print "</div>";
	                                        	if ($line['type']==1)
                                                	print stripslashes($line['youtubecode']);
                                                print "</p><br /><br />";
                                        }
                                        	if ($id) {
                                        		print get_comments(MY_VIDEO, $id, 'Комментарии');
                                        		print get_comments_form(MY_VIDEO,$id);
                                            }
	                                     if ($num_pages>1) print $nav;
                                        ?>
		</td>
		<td width="30%">
			<p>
				Видео-ролики
			</p>
			<div class="vmenu">
				<ul>
	                                  <?
	                                  	for ($i=sizeof($v)-1;$i>=0;$i--) {
	                                  		$line1 = $v[$i];
	                                  		if ($line1["id"] == $id)
	                                  			print "<li class=\"selected\">".stripslashes($line1['title']);
	                                  		else
	                                  		{
	                                  			$url_video_page = $site_root_folder."/video/".$line1['id']."/";
                                            	print "<li><a href=\"".$url_video_page."\">".stripslashes($line1['title'])."</a>";
	                                  		}
                                            if ($line1["id"] == $id) print "</b></font>";
                                            $sql1 = "SELECT COUNT(id) as cnt FROM comments WHERE id_category=".MY_VIDEO." AND id_item=".$line1['id'];
	                                        $rst1 = $db->sql_query($sql1) or die("<p>$sql1<p>".mysql_error());
                                            $line2 = $db->sql_fetchrow($rst1);
                                            if ($line2['cnt'])
                                                print "&nbsp;(".$line2['cnt'].")";
                                            print "</li>";
                                        }
	                                  ?>
				</ul>
			</div>
		</td>
	</tr>
</table>
<?
	include_once $path_site."/tmpl/header.php";
?>
