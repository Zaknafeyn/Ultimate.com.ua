<?
    if (isset($_GET['img'])) {
    	$img = digits_only($_GET['img']);
    	$r = $db->sql_query("SELECT * FROM photo WHERE id=$img");
    	if ($db->sql_affectedrows($r)) {
    		$pict = $db->sql_fetchrow($r);
    		$pict["author"] = $pict['photo_author_id']?NameSurname($pict['photo_author_id'],true):stripslashes($pict['photo_author']);
    		$pid = $pict['id'];
			$db->sql_query("UPDATE photo SET views=views+1 WHERE id=$pid");
    		$aid = $pict['id_album'];
    		if (!isset($al[$aid]))
	   			die();
		} else
			die();
    } else
    	die();

    $album = $al[$aid];

   	// камент
    if (isset($_POST['comment'])) {
    	save_comment(
    		MY_PHOTO,
    		digits_only($_REQUEST['img']),
    		$_REQUEST['author'],
    		$_REQUEST['email'],
    		$_REQUEST['comment'],
    		$_REQUEST['ip'],
    		digits_only($_REQUEST['i1']),
    		digits_only($_REQUEST['i2']),
    		digits_only($_REQUEST['ar'])
    	);
	    header("location:/photo/".$album['url']."/-$pid#com");
    }


			if (isset($_POST["save"])) {

	    		if ($user->data['user_id'] != ANONYMOUS) {

					if ($_POST['del']) {
					    	$mark_id=digits_only($_POST['del']);
					    	$r = $db->sql_query("SELECT * FROM photo_marks WHERE id=$mark_id");
					    	if ($db->sql_affectedrows($r)) {
					    		$l = $db->sql_fetchrow($r);
					    		// если отмечен или отмечал я -- можно удалять
					    		if (($l['user_id']==$user->data['user_id']) || ($l['markedby']==$user->data['user_id']))
									$db->sql_query("DELETE FROM photo_marks WHERE id=$mark_id");
					    	}
					} else {

					    $x1 = digits_only($_POST["x1"]);
					    $y1 = digits_only($_POST["y1"]);
					    $x2 = digits_only($_POST["x2"]);
					    $y2 = digits_only($_POST["y2"]);
					    $w = digits_only($_POST["w"]);
					    $h = digits_only($_POST["h"]);

					    if ($w) {
					    	// себя или кого-то или что-то?
					    	if ($_POST['me']==1) {
					    		$user_id=$user->data['user_id'];
					    		$approved = 1;
					    	} elseif ($_POST['freetext']) {
					    		$user_id=0;
					    		$freetext=htmlspecialchars(trim($_POST['freetext']));
					    		$approved = 1;
					    	} else {
					    		$user_id=digits_only($_POST["user_id"]);
					    		$approved = 0;
					    	}
					    	if ($user_id)
								$db->sql_query("DELETE FROM photo_marks WHERE photo_id=$img AND user_id=$user_id");
							if ($user_id||$freetext)
								$db->sql_query("INSERT INTO photo_marks (photo_id,user_id,x1,y1,x2,y2,w,h,markedby,mtime,approved,freetext) VALUES($img,$user_id,$x1,$y1,$x2,$y2,$w,$h,".$user->data['user_id'].",".time().",$approved,'$freetext')");
						}

					    header("location:/photo/".$album['url']."/-$img");
					    exit();
					}
				 }
			}


			// если отмечен на фотке, то approved=1
			// upd: первый раз надо бы вывести мессагу
    		if ($user->data['user_id'] != ANONYMOUS) {
				$r = $db->sql_query("SELECT * FROM photo_marks WHERE photo_id=$img AND user_id=".$user->data['user_id']." AND approved=0");
				if ($db->sql_affectedrows($r)) {
					$l = $db->sql_fetchrow($r);
					$appmsg = "<div id=\"appmsg\" class=\"info\">";
					$appmsg.= "".NameSurname($l['markedby'],true)." отметил".(Sex($l['markedby'])?"":"а")." тебя на этой фотографии ок?";
					$appmsg .= " &nbsp; &nbsp; <a class=\"pseudo_link clickable\" onclick=\"javascript:$('#appmsg').hide();\">ок</a>";
					$appmsg .= " &nbsp; &nbsp; <a class=\"pseudo_link clickable del\" rel=".$l['id'].">не ок</a>";
					$appmsg .= "</div></p>";
					$db->sql_query("UPDATE photo_marks SET approved=1 WHERE photo_id=$img AND user_id=".$user->data['user_id']);
				}
			}


			// Список пользователей форума
			$sql = "SELECT * FROM phpbb_users u
				LEFT JOIN players p ON u.user_id=p.id
				WHERE";
				if ($user->data['user_id'] != null)
				{
					$sql = $sql."user_id<>".$user->data['user_id']." AND";
				}
				
				$sql = $sql." (p.id OR u.user_posts<>0)
				ORDER BY edited DESC, active DESC, Surname ASC, username ASC";
			$r = $db->sql_query($sql);
			$users = array();
			if ($db->sql_affectedrows($r)) {
				while ($line=$db->sql_fetchrow($r))
					$users[(1-$line['edited']).(1-$line['active']).$line['Surname'].$line['Name'].$line['username']] = $line;
				ksort($users);
				$ulist = "\r\n\t<option value=\"0\"></option>";
                foreach ($users as $us) {
					$ulist .= "<option value=\"".$us['user_id']."\">";
					if ($us['active']) $ulist.=($us['Surname']?$us['Surname']:$us['patronymic'])." ".$us['Name']." (".$us['username'].")"; else $ulist.= $us['username'];
					$ulist .= "</option>";
				}
			}

			// кто на фотке?
			$rst = $db->sql_query("SELECT m.*,u.user_id,u.username,p.p_char_id FROM photo_marks m
				LEFT JOIN phpbb_users u
				ON u.user_id=m.user_id
				LEFT JOIN players p
				ON p.id=m.user_id
				WHERE photo_id=$img");
			if ($db->sql_affectedrows($rst)) {
				$coord = array();
				while ($line = $db->sql_fetchrow($rst)) {
					$coord[$line['id']] = array(
						"mark_id"=>$line['id'],
						"user_id"=>$line['user_id'],
						"p_char_id"=>$line['p_char_id'],
						"c"=>"x1: ".$line['x1'].", y1: ".$line['y1'].", x2: ".$line['x2'].", y2: ".$line['y2'].", movable:false, resizable: false, disable:false ",
						"u"=>($line['user_id']?$line['username']:$line['freetext']),
						"markedby"=>$line['markedby'],
						"approved"=>$line['approved'],
						"aim"=>$line['user_aim']);
				}
				foreach ($coord as $u=>$c) {
					$func .= "\r\n\$('#u".$u."').hover(function(){\$('img#i0').imgAreaSelect({".$c["c"]."})},function(){\$('#frm').hide(); \$('img#i0').imgAreaSelect({ disable:".($user->data['user_id'] != ANONYMOUS?"false":"true").", hide:true, movable:true, resizable: true });});";
					$user_id=$c["user_id"];
					$p_char_id=$c["p_char_id"];
					if ($user_id) {
						$r = $db->sql_query("SELECT * FROM players WHERE id=$user_id");
						if ($db->sql_affectedrows($r)) {
							$l = $db->sql_fetchrow($r);
							$url="<span class=\"sex".$l['id_sex']."\"><a id=\"u".$u."\" href=\"/players/".($p_char_id?$p_char_id:$user_id)."/\">".NameSurname($l['id'])."</a></span>";
						} else {
							//if ($c["aim"]) {
							if (false) {
								$url="<a id=\"u".$u."\" href=\"".$c["aim"]."\">".$c["u"]."</a>";
							} else {
								$url="<a id=\"u".$u."\" href=\"/4room/profile.php?mode=viewprofile&u=$user_id\">".$c["u"]."</a>";
							}
						}
						$uof .= ($uof? ", ":"").$url;
					} else {
						$uof .= ($uof? ", ":"") . "<a class=\"pseudo_link\" id=\"u".$u."\" href=\"#\">".$c["u"]."</a>";
					}
					// если я на фотке, или я отмечал, то дать возможность удалить
					// upd: а если заапрувили фотку, то уже нельзя
					if (($user_id==$user->data['user_id']) || ( ($c['markedby']==$user->data['user_id'])&&($c['approved']==0) )) {
						$uof .= " (<a class=\"del hid\" rel=".$c['mark_id'].">убрать</a>)";
					}
					$uof .= "";
				}
				//$uof = ExtLink($uof);
			}


   	$r = $db->sql_query("SELECT * FROM photo WHERE id_album=$aid");
   	$th = array();
   	while ($l = $db->sql_fetchrow($r)) {
   		array_push($th, $l);
   		if ($l['id']==$pid) {
   			$i = sizeof($th);
   		}
   	}
   	$total = sizeof($th);
   	if ($i>($total-1)) {
   		$i=$total-1;
   	}


	$title = $pict['title']?$pict['title']:($album['titlefull']?$album['titlefull']:$album['title']);
	$title = stripslashes($title);
	$meta_description = $title;
	$meta_keywords = $title;
	include_once $_SERVER['DOCUMENT_ROOT'].$site_folder_prefix."/tmpl/header.php";
?>
<script type="text/javascript" src="/js/jquery.jcarousel.pack.js"></script>
<script type="text/javascript" src="/js/jquery.imgareaselect-0.6.2.min.js"></script>
<script type="text/javascript">
function mycarousel_itemLoadCallback(carousel, state)
{
    if (carousel.has(carousel.first, carousel.last)) {
        return;
    }
    $.get(
        '/photo/dynamic_ajax_php.php',
        {
            first: carousel.first,
            last: carousel.last,
            pid: <?=$pid?>
        },
        function(xml) {
            mycarousel_itemAddCallback(carousel, carousel.first, carousel.last, xml);
        },
        'xml'
    );
};
function mycarousel_itemAddCallback(carousel, first, last, xml)
{
    carousel.size(parseInt($('total', xml).text()));
    $('image', xml).each(function(i) {
        carousel.add(first + i, mycarousel_getItemHTML($(this)));
    });
};
function mycarousel_getItemHTML(itm)
{
	if ($('url',itm).text()!='') {
    	return '<a href="'+$('url',itm).text()+'#o0"><img src="'+$('src',itm).text()+'" width="'+$('width',itm).text()+'" style="border: none;" /></a>';
    } else {
    	return '<img src="'+$('src',itm).text()+'" width="'+$('width',itm).text()+'" style="border: none;" />';
    }
};

var $x1, $y1, $x2, $y2, $w, $h;
function selectChange(img, selection)
{
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
	if(selection.width=="" || selection.height==""){
		$('#frm').hide();
	} else {
		$('#frm').show();
	}
}
$(document).ready(function () {
		$(".del").click(function(){
			$("#del").val($(this).attr("rel"));
			$('#save').click();
			return true;
		});
	    $('#mycarousel').jcarousel({
	    	start: <?=$i?>,
	    	scroll: 2,
	        itemLoadCallback: mycarousel_itemLoadCallback
	    });
<? if ($user->data['user_id'] != ANONYMOUS) { ?>
		 $('#save').click(function() {
		 		 var x1 = $('#x1').val();
		 		 var y1 = $('#y1').val();
		 		 var x2 = $('#x2').val();
		 		 var y2 = $('#y2').val();
		 		 var w = $('#w').val();
		 		 var h = $('#h').val();
		 		 var del = $('#del').val();
		 		 if((x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h=="") && del==0 ){
		 		 		 alert("You must make a selection first");
		 		 		 return false;
		 		 }else{
		 		 		 return true;
		 		 }
		 });
		 $('#thatsme').click(function() {
		 			$('#me').val(1);
		 		    $('#save').click();
	 		 		return true;
		 });
<? } ?>
	$('img#i0').imgAreaSelect({onSelectChange: selectChange, selectionOpacity: 0, outerOpacity: 0.4, borderColor1: 'white', borderColor2: 'white', borderWidth: 3, disable: <? print ($user->data['user_id'] != ANONYMOUS)?"false":"true"?>});
	<?=$func?>

	 $('#title_show').hover(
	 	function() {
			$('#title_edit_link').show();
	 	}, function() {
			$('#title_edit_link').hide();
	 	});
	$('#title_edit_link').click(function() {
		$('#title_show').hide();
		$('#title_edit').show();
		return true;
	});
	$('#title_ok').click(function() {
		$('#preloader').html('<img src="/img/ajax-loader.gif" alt="wait" />');
		$.post("/photo/save_title.php", { title_id: $('#title_id').val(), title: $('#new_title').val() },
			function(data){
				$('#title_show').show();
				$('#title_edit').hide();
				$('#title_edit_link').hide();
				$('#title').text(data);
				$('#preloader').html('');
			});
   		return true;
	});
	$('#title_cancel').click(function() {
		$('#title_show').show();
		$('#title_edit').hide();
		$('#title_edit_link').hide();
		return true;
	});
});
</script>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr valign="top">
		<td width="100%" style="padding-left: 15" colspan="2">
			<p><?=$loveurl?>
			<br /><? $aid0=0; print GetPathTxt($aid); ?>
			</p>

			<p>
				<div id="title_show">
					<h1>
						<span id="title"><?=$title?></span>
						&nbsp;<sup><span id="title_edit_link" class="small" style="display: none;"><a id="title_edit_link" class="pseudo_link clickable">редактировать</a></span></sup>
					</h1>
				</div>
				<div id="title_edit" style="display: none;">
					<input id="new_title" value="<?=htmlspecialchars($title)?>" style="font-size: 1.9em; font-family: Trebuchet MS; width: 800px;" />
					<p><input type="submit" id="title_ok" value="OK" />
					&nbsp;&nbsp;<input type="submit" id="title_cancel" value="Отмена" />
					<input type="hidden" id="title_id" value="<?=$pict['id']?>" />
					&nbsp;&nbsp;<span id="preloader"></span></p>
				</div>
			</p>

<? /*
			<p><h1><? print "<a class=\"hid\" href=\"/photo/".$album['url']."/\">".$pict['title']."</a>"; ?></h1></p>*/ ?>
		</td>
	</tr>
	<? if ($appmsg) { ?> <tr><td style="padding-left: 15;" colspan="2"><?=$appmsg?></td></tr><? } ?>
	<tr valign="top">
		<td style="padding-left: 15;" align="center">
			<div style="min-width: 600px;">
				<?
					$alt = preg_replace("/\"/","",$title).($uof?" (".str_replace("(убрать)","",strip_tags($uof)).")":"");
					$src = $album["picasa"] ? $pict["fname"] : "/photo/albums/".$album["url"]."/large/".$pict["fname"];
					print "<p><img id=\"i0\" src=\"".$src."\" alt=\"".$alt."\" title=\"".$alt."\" />";
				?>
				<? if ($user->data['user_id'] != ANONYMOUS) { ?>
				<div id="frm" name="frm" style="display: none; padding: 10 0 5 0; background-color: #FF9700; ">
				<form name="save_selection" action="<?="/photo/".$album['url']."/-$img"?>" method="post">
					<input type="hidden" name="x1" value="" id="x1" />
					<input type="hidden" name="y1" value="" id="y1" />
					<input type="hidden" name="x2" value="" id="x2" />
					<input type="hidden" name="y2" value="" id="y2" />
					<input type="hidden" name="w" value="" id="w" />
					<input type="hidden" name="h" value="" id="h" />
					<input type="hidden" name="photo_id" value="<?=$img?>" id="photo_id" />
					<input type="hidden" name="me" value="0" id="me" />
					<input type="hidden" name="del" value="0" id="del" />
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td width="50%" valign="top" align="center">
							<p>&nbsp;
							<br /><input type="button" name="thatsme" value="Это я!" id="thatsme" /></p>
						</td><td width="50%" valign="top">
							<p>Можно ввести текст:
							<br /><input type="text" name="freetext" id="freetext" style="width: 90%" />
							</p><p>Или выбрать человека:
							<br /><select name="user_id" style="width: 90%"><?=$ulist?></select>
							</p><p>
							<input type="submit" class="button" name="save" value="Отметить" id="save" /></p>
						</td></tr>
					</table>
				</form>
				</div>
				<? } ?>
			</div>
		</td>
		<td width="100%">
			<div style="padding-left: 30;">
				<div class="small">
					<? print "В альбоме <a href=\"/photo/".$album['url']."/\">$total ".num_decline($total,"фотография","фотографии","фотографий")."</a>"; ?>
				</div>
				<p>
				<div id="mycarousel" class="jcarousel-skin-ie7">
					<ul>
					<!-- The content will be dynamically loaded in here -->
					</ul>
				</div>
				</p>
				<div class="small">
					<?
						$url = "/photo/albums/".$album['url']."/original/".$pict["fname"];
						print $album["picasa"] ? "" : "<br />Большой размер: <a href=\"$url\">".$pict["width"]."x".$pict["height"]."</a>&nbsp;<a href=\"$url\" target=\"_blank\"><img src=\"/img/new-window-icon.gif\" style=\"border: none;\" width=\"11\" height=\"11\" alt=\"Открыть в новом окне\" /></a>";
						if ($pict["author"]) print "<br />Автор: ".$pict["author"]."</p>";
						if ($uof) print "<br /><p>На фото: $uof</p>";
					?>
				</div>
			</div>
		</td>
	</tr>
	<tr valign="top">
		<td style="padding-left: 15;" colspan="2">
			<p>
		<?
			print get_comments(MY_PHOTO, $pid, 'Комментарии', 'ASC');
			print get_comments_form(MY_PHOTO, $pid);
		?>
			</p>
		</td>
	</tr>
</table>


<?
	include_once $_SERVER['DOCUMENT_ROOT'].$site_folder_prefix."/tmpl/footer.php";
?>