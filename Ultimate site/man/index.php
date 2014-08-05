<?
//	$title = "Учебник";
include_once $_SERVER ['DOCUMENT_ROOT'] . "/tmpl/init.php";

function GetPath1($db, $id) {
	$sql = "SELECT * FROM manual WHERE id=$id";
	$rst = $db->sql_query ( $sql ); // or die ( "<p>$sql<p>" . mysql_error () );
	$line = $db->sql_fetchrow ( $rst );
	if ($line ['parent_id'] == 0) {
		return $line ['title'];
	} else
		return GetPath1 ( $db, $line ['parent_id'] ) . " - " . $line ['title'];
}

function GetPath1I($db, $id) {
	$sql = "SELECT * FROM manual WHERE id=$id";
	$rst = $db->sql_query ( $sql ); // or die ( "<p>$sql<p>" . mysql_error () );
	$line = $db->sql_fetchrow ( $rst );
	if ($line ['parent_id'] == 0) {
		return $line ['title'];
	} else
		return $line ['title'] . " - " . GetPath1I ( $db, $line ['parent_id'] );
}

if (isset ( $_GET ['id'] )) {
	$id = digits_only ( $_GET ['id'] );
} else {
	if (isset ( $_GET ['char_id'] )) {
		$char_id = $_GET ['char_id'];
		if (substr ( $char_id, - 1 ) == "/")
			$char_id = substr ( $char_id, 0, strlen ( $char_id ) - 1 );
		if ($char_id == "ultimate/about")
			$pg = "whatis";
		else
			$pg = "man/1";
	} else {
		$char_id = "ultimate/about";
		$pg = "whatis";
		//$id=0;
	//$pg = "man";
	}
	
	if ($id = GetIDbyPath ( $db, $char_id, 0, "manual" )) {
		$title = GetPath1 ( $db, $id, 0 );
	} else {
		$id = 0;
	}
}

if ($id) {
	$sql = "SELECT * FROM manual WHERE id=$id";
	$rst = $db->sql_query ( $sql ); // or die ( "<p>$sql<p>" . mysql_error () );
	$line = $db->sql_fetchrow ( $rst );
	if (trim ( $line ['descr'] ))
		$title = trim ( $line ['descr'] );
	$meta_description = $line ['meta'];
	
	$meta_keywords = "учебник," . $title;
} else {
	$title = "Учебник алтимат фрисби (Ultimate Frisbee)";
	$meta_description = $title . ", стратегии, тактики и упражнения алтимат, оценка духа игры";
	$meta_keywords = "учебник,упражнения,алтимат,ддц";
	$sql = "SELECT * FROM manual WHERE char_id='main' AND parent_id=0";
	$rst = $db->sql_query ( $sql ); // or die ( "<p>$sql<p>" . mysql_error () );
	$line = $db->sql_fetchrow ( $rst );
}
$ttl = stripslashes ( $line ['descr'] );
$doc = stripslashes ( $line ['doc'] );

	$jquery3 = 1;
	include_once $path_site . "/tmpl/header.php";

?>

<style>
.vmenu2 {
	padding: 0;
	margin: 0;
}
.vmenu2 ul li {
	font-size: 12px;
	line-height: 20px;
	padding-left: 15;
}

.vmenu2 li.selected {
	font-weight: bold;
	background: url("/img/rarr.gif") no-repeat;
	padding-left: 15;
}
</style>

<table width="100%" cellspacing="0" cellpadding="15" border="0">
	<tr valign="top">
		<td width="75%">
		<h1><?=$ttl?></h1>
		<div class="content">
		<p>
			<?=$doc?>
            </p>
        </td>

		<td width="25%">
        	<?
        		include ("menu.php");
        	?>
        </td>

	</tr>
</table>


<?
include_once $_SERVER ['DOCUMENT_ROOT'] . "$site_folder_prefix/tmpl/footer.php";
?>