<?
	function team_has_foto($tid) {
		global $db;
		return $db->sql_affectedrows($db->sql_query("SELECT id FROM teams_foto WHERE id_team=$tid"))>0;
	}


	function get_team_foto($tid) {
		global $teams;
		global $user_has_rights_to_upload_team_foto;
		global $max_team_foto;
		global $db;

		$out = "";
		$already_uploaded = $db->sql_affectedrows($db->sql_query("SELECT id FROM teams_foto WHERE id_team=$tid"));

		if ($already_uploaded) {
			$out .= <<<EOD
	<div id="image_wrap">
			<img src="/img/blank.gif" />
			<div id="foto_desc"></div>
	</div>

	<div id="items">
EOD;
		} else {
			$out .= "Не&nbsp;загружено ни&nbsp;одной фотографии.";
		}

		$r = $db->sql_query("SELECT * FROM teams_foto WHERE id_team=$tid ORDER BY dat DESC, id DESC");
		if ($db->sql_affectedrows($r)) while ($l = $db->sql_fetchrow($r)) {
			$out .= "\n<a href=\"#".$l['id']."\"><img src=\"/teams/photo/th_".$l['foto']."\" alt=\"".preg_replace("/\"/","''",stripslashes($l['descr']))."\" id=\"i".$l['id']."\" /></a>";
		}
		$out .= "</div>";


		$del_link = $already_uploaded ? "&nbsp; &nbsp; <a id=\"delete_link\" class=\"pseudo_link clickable\">удалить эту фотографию</a>&nbsp;<span id=\"preloader\"></span>" : "";
		if ($user_has_rights_to_upload_team_foto) {
			$out .= <<<EOD

		<br /><br /><div class="small">
		<a id="upload_link" class="pseudo_link clickable">загрузить новую фотографию</a>
		$del_link
		<div id="upload_foto" style="background-color: #f0f0f0; padding: 15; margin: -25 -15 -15 -15; display: none;"><p>
EOD;
			if ($already_uploaded>=$max_team_foto) {
				$out .= "Уже загружено максимальное количество фотографий. Чтобы загрузить еще, нужно что-то удалить, сори.";
			} else {
				$out .= "<br />Еще можно загрузить ".($max_team_foto-$already_uploaded)." ".num_decline($max_team_foto-$already_uploaded,"фотографию","фотографии","фотографий");
				$cur_date = date("d.m.Y");
				$out .= <<<EOD
		<form name="editform" method="post" action="/teams/upload_team_foto.php" enctype="multipart/form-data">
		<input type="hidden" id="id_team" name="id_team" value="$tid">
		<p><input type="file" id="foto_name" name="foto_name" style="width:350px;" />
		<p>Что на фотке?
		<br /><input type="text" id="foto_descr" name="foto_descr" style="width:450px;" />
		<p>Когда фотали?
		<br /><input id="foto_date" name="foto_date" class="date-pick" value="$cur_date" />
		<br /><br /><br /><button type="submit">Загрузить</button>
		</form>
		<script type="text/javascript" src="/js/jquery.datePicker.js"></script>
		<script type="text/javascript" src="/js/date.js"></script>
		<script type="text/javascript" src="/js/date_ru_win1251.js"></script>
		<script type="text/javascript">
			$(function()
			{
				$('.date-pick').datePicker({startDate:'01.01.2005'});
			});
		</script>

EOD;
			}
		}


		$out .= <<<EOD
<!-- javascript coding -->
<script>
$(document).ready(function() {
    $(function() {

$("#items img").click(function() {
	$(".active").removeClass("active");
	$(this).addClass("active");

	var url = $(this).attr("src").replace("/th_", "/");
	var desc= $(this).attr("alt");

	var img = new Image();
	img.onload = function() {
		$("#image_wrap").find("img").attr("src", url);
		$("#foto_desc").html(desc);
		$("#delete_link").show();
	};
	img.src = url;
});

var i = document.location.toString();
if (i.match('#')) {
	$("#i"+i.split('#')[1]).click();
} else {
	$("#items img").filter(":first").click();
}

$("#upload_link").click(function() { $("#upload_foto").toggle(); } );

$("#delete_link").click(function() {
	var id;
	if (confirm('Удалить?')) {
		$('#preloader').html('<img src="'+img_ajax_preloader.src+'" alt="wait for 2 hours" />');
		$(".active").each(function() { id = $(this).attr("id").split('i')[1]; });
		$.post("/teams/delete_team_foto.php", { id: id },
			function(data){
				if (data=='') {
					$("#i"+id).remove();
					$("#delete_link").hide();
					$("#items img").filter(":first").click();
				} else {
					alert(data);
				}
				$('#preloader').html('');
			});
		//
	}
} );


});
});

</script>
EOD;


		return $out;

	}
?>



