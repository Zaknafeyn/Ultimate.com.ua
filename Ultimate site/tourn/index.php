<?
//    $title = "Турниры";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

	$id = 0;
	$doc = '';
	if (isset($_GET['id'])) {
		$id = digits_only($_GET['id']);
	} else {
		if (isset($_GET['char_id'])) {
			$char_id=$_GET['char_id'];
	        $sql = "SELECT id FROM tourn WHERE char_id='$char_id'";
	        $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	        if ($db->sql_affectedrows($rst)) {
			    $line = $db->sql_fetchrow($rst);
			    $id = $line['id'];
			}
		}
	}
	if ($id) {
	        $sql = "SELECT t.*, COUNT(e.id) AS cnt
	        	FROM tourn AS t
	        	LEFT JOIN extra AS e ON t.id=e.parent_id AND e.id_category=".MY_TOURN."
	        	WHERE t.id=$id
	        	GROUP BY t.id";
	        $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	        if ($db->sql_affectedrows($rst)) {
			    $line = $db->sql_fetchrow($rst);
			    $char_id = $line['char_id'];
	    		$title = preg_replace("/<br( \/)?>/"," ",stripslashes($line['full_name']));
				$title = preg_replace("/<\/?sup>/","",$title);
	    		$full_name = stripslashes($line['full_name']);
	    		$dates = my_create_date($line['dat_begin'],$line['dat_end']);
	    		$city = $line['city'];
	    		$country = $line['country'];
	    		$doc = stripslashes($line['doc']);

				if (isset($_GET['extra'])) {
					$extra = $_GET['extra'];
			        $sql = "SELECT * FROM extra WHERE id_category=".MY_TOURN." AND parent_id=$id AND char_id='$extra'";
			        $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
			        if ($db->sql_affectedrows($rst)) {
			        	$line = $db->sql_fetchrow($rst);
			        	$doc = stripslashes($line['doc']);
			        	$id_extra = $line['id'];
			        	$title = preg_replace("/<[^>]*>/","",stripslashes($line['title']))." - ".$title;
			        } else {
			        	$doc = '';
			        }
				}

	        }
	}

	if (!$id) {
		$title = "Календарь турниров по алтимат фризби";
		$pg = "tourn";
	} else
		$pg = "tourn/1";

	$meta_description = $title;
	$meta_keywords = $title;
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/header.php";
?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr valign="top">
        <td>
	    	<table width="100%" cellspacing="0" cellpadding="15" border="0">
	        	<tr valign="top">

	        		<? if ($id) { ?>

						<td width="75%">
	                        <h1><?=$full_name?></h1>
	                        <p><?=$dates."<br />$city, $country"?></p>
	                    	<? if ($tags = get_tags(2,$id,1)) print "<p><div class=\"smalldate\">Теги: $tags</div>"; ?>
	                    	<p></p><div style="border-bottom: 1px solid #F0F0F0"></div>
	                    	<?
                                if ($e=get_extra($db, MY_TOURN,$id)) {
	                    			print "<p><div class=\"menu\">";
	                    			if (!$extra)
	                    				print "<span class=\"sel1\">Турнир</span>&nbsp;&nbsp;&nbsp;&nbsp;";
	                    			else
	                    				print "<span class=\"sel0\"><a href=\"/tourn/$char_id/\">Турнир</a></span>&nbsp;&nbsp;&nbsp;&nbsp;";
	                                foreach ($e as $e1)
	                    				if ($e1['char_id']==$extra)
	                    					print "<span class=\"sel1\">".$e1['title']."</span>&nbsp;&nbsp;&nbsp;&nbsp;";
	                    				else
		                    				print "<span class=\"sel0\"><a href=\"/tourn/$char_id/".$e1['char_id']."/\">".$e1['title']."</a></span>&nbsp;&nbsp;&nbsp;&nbsp;";
	                    			print "</div>";
                                }
	                    	?>
	                        <br />
	                        <p><?=parse_doc($doc)?></p>
	                    </td>
	                    <td>
		                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
	                        	<tr valign="top">
	                            	<td>
					<noindex>
	                                <?
								        $sql = "SELECT t.*, COUNT(e.id) AS cnt
								        	FROM tourn AS t
								        	LEFT JOIN extra AS e ON t.id=e.parent_id AND e.id_category=".MY_TOURN."
								        	WHERE dat_end>=".date("Ymd")."
								        	GROUP BY t.id
								        	ORDER BY dat_begin ASC";
	                                    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	                                    if ($db->sql_affectedrows($rst)) {
	                                        print "<p>Предстоящие турниры:</p>";
	                                        print "<div class=\"vmenu\">";
	                                        while ($line1 = $db->sql_fetchrow($rst)) {
		                                    	$show_link = ( $line1['doc'] || $line1['cnt'] ) ? true : false;
	                                            print "<p>";
	                                            if ($line1["id"] == $id)
	                                            	print "<div class=\"sel1\">".stripslashes($line1['short_name'])."";
	                                            elseif (!$show_link)
	                                            	print "<div class=\"sel0\">".stripslashes($line1['short_name'])."";
	                                            else
	                                            	print "<div class=\"sel0\"><a href=\"/tourn/".$line1['char_id']."/\">".stripslashes($line1['short_name'])."</a>";
	                                            print "</div>";
	                                            print "<div class=\"small\" style=\"padding-left: 30\">".my_create_date($line1['dat_begin'],$line1['dat_end'],1)."<br />".stripslashes($line1['city']).", ".stripslashes($line1['country'])."</div>";
	                                        }
	                                        print "</div>";
	                                    }
	                                ?>
					</noindex>
	                                </td>
	                            </tr>
								<tr><td><p><br /></p><div class="more"><a href="/tourn/">Календарь турниров</a></div></p></td></tr>
	                        </table>
		            	</td>
		            <? } else { ?>
                <!-- КАЛЕНЬДАРЬ ТУРНИРОВ -->
	                    <td>
		                    <table cellspacing="0" cellpadding="0" border="0">
					        	<tr valign="top">
					        		<td>
						                <h1>Календарь турниров</h1>
						                <p></p>
					        		</td>
					        	</tr>
					        	<tr valign="top">
					        		<td>
					        			<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr valign="top"><td>
				        			<?
								        $sql = "SELECT *
								        	FROM tourn
								        	WHERE dat_end>=".date("Ymd",time()-60*60*24*2)."
								        	ORDER BY dat_begin ASC";
	                                    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	                                    $t = array();
	                                    if ($db->sql_affectedrows($rst)) {
	                                    	while ($line = $db->sql_fetchrow($rst)) {
                                    			$y = substr($line['dat_begin'],0,4);
                                    			if (!$t[$y]) $t[$y]=array();
                                    			$m = substr($line['dat_begin'],4,2);
                                    			if (!$t[$y][$m]) $t[$y][$m]=array();
                                    			$t1 = "<p><h4><a href=\"/tourn/$line[char_id]/\">".stripslashes($line['short_name'])."</a></h4>";
                                    			$t1.= "<p style=\"padding-top: 5;\"><div class=\"small\">";
                                    			if (!$line['short'])
                                    				$t1 .= my_create_date($line['dat_begin'],$line['dat_end'],0)."<br />$line[city], $line[country]";
                                    			else
                                    				$t1 .= stripslashes($line['short']);
                                    			$t1 .= "</div>";
	                                            //if ($e=get_extra($db, MY_TOURN,$line['id'])) {
	                                            if (false) {
	                                            	$t1.= "<div class=\"extra\">";
		                                            foreach ($e as $e1)
			                           					$t1.= "<p><a href=\"/tourn/".$line['char_id']."/".$e1['char_id']."/\">".$e1['title']."</a>";
	                                            	$t1.= "</div>";
	                                            }
	                                            $t1.= "</p><br />";
												array_push($t[$y][$m], $t1);
	                                    	}
	                                 	}
	                                 	if ($t) {
                                    		//print "<div id=\"tourn\" style=\"background-color: #f0f0ff;\">";
                                    		//print "<div class=\"padding\">";
                                    		print "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr valign=\"top\">";
                                    		foreach ($t as $y=>$y1) {
                                    			print "<td style=\"padding-right: 30;\"><p><h3>$y</h3><br />";
                                    			print "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr valign=\"top\">";
                                    			foreach ($y1 as $m=>$m1) {
                                    				print "<td width=\"220\"><p>".$month1[$m]."</p>";
                                    				foreach ($m1 as $t)
                                    					print $t;
                                    				print "</td>";
                                    			}
                                    			print "</tr></table>";
                                    			print "</td>";
                                    		}
                                    		print "</tr></table>";
                                    		//print "</div></div>";
                                    		//print "<script>$('#tourn').corner();</script>";
                                    	}
									?>
										</td></tr></table>
					        		</td>
					        	</tr>
		                        <tr valign="top"><td>
		                        	<br /><br /><br /><p><h4>Прошедшие турниры</h4></p>
		                            <table cellspacing="0" cellpadding="0" width="100%" border="0">
		                                <tr valign="top">
		                                    <?
										        $sql = "SELECT *, YEAR(dat_begin) AS y, MONTH(dat_begin) AS m, DAYOFMONTH(dat_begin) AS d
										        	FROM tourn
										        	WHERE dat_end<".date("Ymd")."
										        	ORDER BY y DESC, m ASC, d ASC";
		                                        $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
		                                        $i = 0;
	                                            $y = "";
		                                        while ($line = $db->sql_fetchrow($rst)) {
		                                            if ($y != substr($line['dat_begin'],0,4)) {
		                                            	$y = substr($line['dat_begin'],0,4);
	                                                	print "<br /></td>";
		                                                if ($i++ == 4) {
		                                                    print "</tr><tr valign=\"top\">";
		                                                    $i = 1;
		                                                }
		                                                print "<td width=\"250\" style=\"padding-right: 30;\"><p><h3>$y</h3></p>";
		                                            }
		                                            print "<p>";
		                                            if ($line['doc'])
		                                            	print "<a href=\"/tourn/".$line['char_id']."/\">".stripslashes($line['short_name'])."</a>";
		                                       		else
		                                            	print stripslashes($line['short_name']);
		                                            print "<div class=\"small\" style=\"padding-left: 15\">".stripslashes($line['city']).", ".stripslashes($line['country']);
		                                            print "<br />".my_create_date($line['dat_begin'],$line['dat_end'],0)."</div>";
		                                            if ($e=get_extra($db, MY_TOURN,$line['id'])) {
		                                            	print "<div class=\"extra\">";
			                                            foreach ($e as $e1)
				                           					print "<p><a href=\"/tourn/".$line['char_id']."/".$e1['char_id']."/\">".$e1['title']."</a>";
		                                            	print "</div>";
		                                            }
		                                        }
		                                    ?>
	                                    </td><td></td></tr>
	                                </table>
	                            </td></tr>
	                        </table>
	                    </td>
			    <?/*<td width="200"><div class="title">&nbsp; </div><div class="padding"><? include "ffindr.php"; ?></div></td>
*/?>
    	    		<? } ?>

	            </tr>
	        </table>
		</td></tr>
	</table>
<div id="round2" style="display: none"></div>

<?
	include_once $_SERVER['DOCUMENT_ROOT']."$site_folder_prefix/tmpl/footer.php";
?>
