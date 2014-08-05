<?

$construction = 0;
if ($construction)
{
  echo "<html>
	<head><title>Ultimate.com.ua is under construction</title></head>
	<body>
		<div align=\"center\"><img src=\"http://ultimate.com.ua/pics/under_construction.jpg\" /></div>
	</body>
	</html>";
  return;
}
	$title = "";
	$pg = "index";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>

<table cellspacing="0" cellpadding="15" border="0" width="100%">
    <tr valign="top">
        <td width="70%">
        	<? /* if (!$userdata['session_logged_in']) { ?>
        	<div id="welcome">
        		<p><strong>&mdash; Где&nbsp;я?</strong>
				<br />&mdash; Ты  находишься на&nbsp;сайте, который посвящен алтимату в&nbsp;Украине. Это такой загадочный вид спорта с&nbsp;фризби (летающим диском).
				</p>
				<p><strong>&mdash; Кто здесь?!</strong>
				<br />&mdash; Основная часть людей, которые сидят на&nbsp;этом сайтe&nbsp;&mdash; это спортсмены, занимающиеся алтиматом. Для того, чтобы лучше нас понимать, рекомендуем сперва посетить другой наш сайт <a href="http://www.frisbee.com.ua">www.frisbee.com.ua</a>.
				</p>
				<br />
        	</div><br /><br />
        	<? } */ ?>
				<?				
				    $r = $db->sql_query("SELECT * FROM auxx WHERE par='index' AND active=1 ORDER BY o");
				    if ($db->sql_affectedrows($r))
					while ($l = $db->sql_fetchrow($r))
					    print $l['val'];
				?>
				<table width="100%">
    			<?
					$sql = "SELECT * FROM blog WHERE active=1 ORDER BY o DESC, date DESC";
					$rst = $db->sql_query($sql);
					$i = 3;
					if ($db->sql_affectedrows($rst))
					while (($line = $db->sql_fetchrow($rst)) && $i--) {
						$id = $line['id'];
						$poster_name = $line['poster'];
						$poster_lj = "<div class=\"small\" style=\"padding: 10px 0px 0px 0px;\">".$lj_user_img."&nbsp;<noindex><a href=\"http://".$poster_name.".livejournal.com\" rel=\"nofollow\">".$poster_name."</a></noindex></div>";
						$poster_pic = "";
						$poster_namesurname = "";
						if ($poster_id = get_user_id_by_lj($db, $poster_name)) {
							$poster_pic = GetUserAvatar($poster_id,75,100);
							$poster_namesurname = "<br /><div class=\"smalldate0\">".str_replace(" ","<br />",NameSurname($poster_id))."</div>";
						} else {
							$poster_pic = "<img src=\"/teams/photo/nophoto_small.gif\" />";
						}
						$title = "<h1><a href=\"/blog/$id/\">".stripslashes($line['title'])."</a></h1>";
						$dat = make_human_date($line["date"]);
						$doc = stripslashes($line['doc']);
						if ($pos = strpos($doc,"<a name=\"cutid")) {
							preg_match('/<div class="ljcut" text="([^"]*)">/',$doc,$matches);
							$cut_txt = $matches[1] ? $matches[1] : "Читать дальше";
							$doc = substr($doc,0,$pos) . "<p><a href=\"/blog/$id/\">$cut_txt</a></p>";
						}
						
						//$doc = preg_replace('|<a\s+href=([^>]+)>(.+)</a>|s', '<noindex><a href=$1 rel="nofollow">$2</a></noindex>', $doc);
						$doc = preg_replace("|<a(.*)href(.*)</a>|Us", '<noindex><a rel="nofollow" $1 href$2</a></noindex>', $doc);
						//$doc = preg_replace('|<noindex><a\s+href="(.+ultimate.+)"\s+rel="nofollow">(.+)</a></noindex>|U', '<a href="$1">$2</a>', $doc);
						
#<span class="Apple-style-span" style="text-align: center; line-height: 14px; font-family: Helvetica,Arial,serif; white-space: nowrap; color: rgb(21, 21, 21); font-size: 11px; font-weight: bold;"><a href="http://punisher-27.livejournal.com/profile" style="padding: 0px; border-width: 0px ! important; margin: 0px; outline-width: 0px; color: rgb(117, 11, 11); font-size: 11px; vertical-align: baseline; text-decoration: none;"><img height="17" width="17" src="http://l-stat.livejournal.com/img/userinfo.gif?v=1" alt="[info]" class=" ContextualPopup" style="padding: 0px 1px 0px 0px; border-width: 0px; margin: 0px; outline-width: 0px; font-size: 11px; vertical-align: bottom; cursor: pointer;"></a><a href="http://punisher-27.livejournal.com/" style="padding: 0px; border-width: 0px; margin: 0px; outline-width: 0px; color: rgb(117, 11, 11); font-size: 11px; vertical-align: baseline; text-decoration: none;"><b style="padding: 0px; border-width: 0px; margin: 0px; outline-width: 0px; font-size: 11px; vertical-align: baseline; cursor: pointer;">punisher_27</b></a></span>
				?>
					<tr valign="top">
						<td width="100" style="padding-right: 30;" align="left">
							<?=$poster_pic?>
							<?=$poster_lj?>
							<br /><br /><br />
						</td>
						<td>
							<?=$title?>
							<div class="smalldate0"><?=$dat?></div>
							<p>
							<?=$doc?>
							<br /><br /><br />
						</td>
					</tr>
				<?
					}
				?>
				</table>
				<?
					print "<p><div id=\"navigator\"><a href=\"/blog/".$line['id']."/\">&larr;&nbsp;".$line['title']."</a></div>";
				?>
		</td>
    	<td width="30%">
    		<h3>Последние 10&nbsp;записей</h3>
    		<p>
    		<?
    			$r = $db->sql_query("SELECT * FROM blog WHERE active=1 ORDER BY o DESC, date DESC LIMIT 10");
    			if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) {
    				$id = $l['id'];
    				$title = stripslashes($l['title']);
    				if (!$title) $title = "* * *";
    				$poster = stripslashes($l['poster']);
/*
					if ($poster_id = get_user_id_by_lj($db, $poster)) {
					    $poster = GetUserAvatar($poster_id,16,16)."&nbsp;".$poster;
					} else {
					    $poster_url = "http://$poster.livejournal.com";
					    $poster = "<a href=\"$poster_url\">$lj_user_img</a>&nbsp;".$poster;
					}
					$date = make_human_date($line["date"]);
*/
    				print "<a href=\"blog/$id/\">$title</a> <span class=\"lite\">($poster)</span><br />";
    			}
    		?>
    		<br />
    			<p>
    			<script src="http://widgets.twimg.com/j/2/widget.js"></script>
				<script>
				new TWTR.Widget({
    				version: 2,
    				type: 'search',
    				search: '#ua_ultimate',
    				interval: 6000,
    				title: 'Twitter news for',
    				subject: 'ULTIMATE.COM.UA',
    				width: 'auto',
    				height: 300,
    				theme: {
        				shell: {
    	    				background: '#8ec1da',
	    					color: '#ffffff'
        				},
						tweets: {
            				background: '#ffffff',
	    					color: '#444444',
    	    				links: '#0097ee'
						}
    				},
    				features: {
        				scrollbar: false,
						loop: true,
        				live: true,
        			hashtags: true,
        			timestamp: true,
        			avatars: true,
        			toptweets: false,
        			behavior: 'default'
     				}
				}).render().start();
				</script>

    			</p>
    			<br />

    		<h3>Фотографии</h3>
    		<p>
    		<div class="thumbnails">
			<?
				$parray = array();
				$sql="SELECT id,fname,id_album FROM photo AS p WHERE showonmain=1";
				$rst = $db->sql_query($sql);
				while ($line = $db->sql_fetchrow($rst))
					array_push($parray, $line);

				for ($i=0; $i<3; $i++) {
					list($usec, $sec) = explode(' ', microtime());
					srand((float) $sec + ((float) $usec * 100000));
					$k = rand(0, sizeof($parray)-1);
					$j=0;
					foreach ($parray as $key=>$value) {
						if ($j==$k) {
							$key1 = $key;
							break;
						}
						$j++;
					}
   					print "<ins class=\"thumbnail\">";
       				print "<div class=\"r\">";
					print "<a href=\"/photo/".GetPath($db, $parray[$key]["id_album"])."/-".$parray[$key]["id"]."#o0\"><img src=\"/photo/albums/".GetPath($db, $parray[$key]["id_album"])."/small/".$parray[$key]["fname"]."\" style=\"border:none\" width=\"150\" height=\"150\" alt=\"\"></a>";
					print "</div></ins>";
					//print "<br style=\"clear: both;\">";
					unset($parray[$key1]);
				}
			?>
			</div>
			<br style="clear: both;">
			<p style="margin: 0px;"><div class="more"><a href="/photo/love/">Все фотографии</a></div></p>

            <?
				$sql = "SELECT c.city, c.char_id, p.dat, p.id_place AS pid, p.place AS place1, p.comment AS comment1, pp.place AS place2, pp.comment AS comment2
					FROM practice AS p
					LEFT JOIN practice_places AS pp ON p.id_place=pp.id
					LEFT JOIN cities AS c ON p.id_city=c.id
					WHERE p.dat>".(time()-2*60*60)/*mktime(0,0,1,date("m"),date("d"),date("Y"))*/."
					ORDER BY c.id, dat ASC";
				$rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
				if ($db->sql_affectedrows($rst)) {
            ?>
      					<br />
						<?/*<h4>Ближайшие игры</h4>*/?><p><a href="/last/day/#pick-up">Ближайшие игры</a></p>
                        <? /*
                        	$city = ""; $k = 3;
                            while ($line = $db->sql_fetchrow($rst)) {
                            	if ($city != $line['city']) {
                            		if ($city) print "<br />";
                            		$city = $line['city'];
					$k = 3;
                            		print "<p><a href=\"/practice/".$line['char_id']."/\">$city</a>";
                            	}
				if ($k--) {
                            	print "<p>";
                           		print "<div class=\"small\" style=\"padding-left: 15;\">";
                            	$d = $line['dat'];
                                if ( date("dmY") == date("dmY",$d))
                                	print "<span style=\"color:red\"><strong>Сегодня</strong></span>";
                                elseif (date("dmY") == date("dmY",$d-60*60*24))
                                	print "<strong>Завтра</strong>";
                                else
                                	print "<strong>".$weekday[date("w",$d)]."</strong>";
                                print "<br />".sprintf("%2d",date("d",$d))." ".$month[date("m",$d)].", ".date("H:i",$d);
                                if ($line['pid']) {
                                	$place = $line['place2'];
                                    $comment = $line['comment2'];
                                } else {
                                	$place = $line['place1'];
                                    $comment = $line['comment1'];
                                }
                                print "<br />" . stripslashes($place) . "<br />" . stripslashes($comment) ."";
                                print "</div>";
				}
                            }*/
                        ?>
            <?
                   }
            ?>
	   	</td>
	</tr>
</table>

<?
	include $path_site."/tmpl/footer.php";
?>
