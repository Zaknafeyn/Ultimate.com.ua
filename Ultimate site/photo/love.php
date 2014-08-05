<?
	$title = "Избранные фотографии";
	$meta_description = "Алтимат фризби фото ultimate frisbee photo";
	$meta_keywords = "фото, альбомы, фотографии, алтимат, ultimate, frisbee, photo, albums";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/header.php";
?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="100%" style="padding-left: 15" colspan="2">
			<p><a id="o0" title="Избранные фотографии"><img src="/img/love.png" style="border: none" title="Избранные фотографии" alt="Избранные фотографии" /></a>
			<br /><a href="/photo/">Альбомы</a></p>
			<p><h1>Избранные фотографии</h1></p>
			<p>
			<div class="thumbnails">
			<?
				$parray = array();
				$sql="SELECT id,fname,id_album FROM photo AS p WHERE showonmain=1";
				$rst = $db->sql_query($sql);
				if ($db->sql_affectedrows($rst)) {
					while ($line = $db->sql_fetchrow($rst))
						array_push($parray, $line);
					$size=sizeof($parray);
					$rnd = array();
					for ($i=0; $i<$size; $i++) {
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
						array_push($rnd,$parray[$key]);
						unset($parray[$key1]);
					}
					$rnd=array_slice($rnd,0,15);
					foreach($rnd as $p) {
						$url = GetPath($db, $p['id_album']);
			    		//print "<div class=\"thumb\" style=\"width: 18%\">";
    					print "<ins class=\"thumbnail\" style=\"width: 18%;\">";
        				print "<div class=\"r\">";
			    		print "<a href=\"/photo/$url/-".$p['id']."#o0\">";
			    		print "<img width=\"150\" height=\"150\" src=\"./photo/albums/$url/small/".$p['fname']."\" style=\"border: none\" />";
			    		print "</a>";
			    		//if ($com[$l['id']])
			    		if (false)
			    			print "<span class=\"thumbcom\">".$com[$p['id']]." ".num_decline($com[$p['id']],"комментарий","комментария","комментариев")."</span>";
			    		print "</div>";
			    		print "</ins>";
					}
				}
			?>
			</div>
			</p>
			<p><br style="clear: both" /></p>
		</td>
	</tr>
	<tr style="background-color: #ededed;">
		<td style="padding-left: 15;">
			<p><h2>Новые <a href="/photo/">альбомы</a></h2></p>
			<p>
			<?
				/*
				InitAlbums(0);
				$i = 4;
				foreach($al as $a) {
					if (!$i--) break;
					if ($a['show'])
						print "<div class=\"album\">".$a['show']."</div>";
				}
				*/
				ShowAlbums(0,4);
			?>
			</p>
			<br />
		</td>
	</tr>
</table>

<?
	include_once $path_site."/tmpl/footer.php";
?>