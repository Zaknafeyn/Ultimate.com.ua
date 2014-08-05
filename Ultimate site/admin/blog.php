<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];

if (!$rights['all_rights'])
	if (! ($rights['news_edit'] || $rights['news_add']) ) {
		header("location:login.php");
		exit;
	}

	$title = "Блог";
	include "include/header.php";
?>
<style>
.post {
	padding: 5;
	background: white;
}
.post_not_active {
	background: silver;
	color: gray;
}
.invisible {
	display: none;
}
</style>

<script language="javascript">
	var preloaderImg = new Image();
	preloaderImg.src = '/img/ajax-loader.gif';
	function set_post_mode(id,set_mode) {
		$('#preloader_'+id).html('<img src="/img/ajax-loader.gif" alt="wait for 2 hours" style="vertical-align: middle;" />');
		$.post("blog_set_mode.php", { id: id, mode: set_mode },
			function(data){
				$('#preloader_'+id).html('');
				if (1==data) {
					$('#post'+id).toggleClass('post_not_active');
					$('#post'+id+' a').toggleClass('invisible');
					return false;
				} else alert('error');
		});
	}
</script>

<?

	$r = $db->sql_query("SELECT * FROM blog ORDER BY o DESC, date DESC");
	if ($db->sql_affectedrows($r)) {
		while ($l = $db->sql_fetchrow($r)) {
			$id = $l['id'];
			$active = $l['active'];
			$o = $l['o'];
			print "<div id=\"post$id\" class=\"post".($active?" post_active":" post_not_active")."\">";
			print "<p><b>".stripslashes($l['title']?$l['title']:"* * *")."</b>";
			print "&nbsp;<span id=\"preloader_$id\"></span>";
			print "<div class=\"small\">".make_human_date($l['date'])."</div>";
			print "<br />";
			print "<a class=\"pseudo_link clickable".($active?"":" invisible")."\" onclick=\"set_post_mode($id,0);return false;\">спрятать</a>";
			print "<a class=\"pseudo_link clickable".($active?" invisible":"")."\" onclick=\"set_post_mode($id,1);return false;\">показать</a>";
			print " <a href=\"blog_det.php?id=$id&action=moveup&o=$o\">вверх</a> <a href=\"blog_det.php?id=$id&action=movedown&o=$o\">вниз</a>";
			print "</p>";
			print "</div><p>";
		}
	}

	if ($rights['all_rights']||$rights['news_add']) { }
?>

<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>