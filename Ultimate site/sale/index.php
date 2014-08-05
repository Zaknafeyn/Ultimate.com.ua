<?
//	$title = "Учебник";
	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";

    function GetPath1($db, $id) {
        $sql = "SELECT * FROM sale WHERE id=$id";
        $rst = $db->sql_query ($sql);// or die ("<p>$sql<p>".mysql_error());
		$line = $db->sql_fetchrow($rst);
        if ($line['parent_id']==0) {
        	return $line['title'];
        } else
        	return GetPath1($db, $line['parent_id'])." - ".$line['title'];
    }

    function GetPath1I($db, $id) {
        $sql = "SELECT * FROM sale WHERE id=$id";
        $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
		$line = $db->sql_fetchrow($rst);
        if ($line['parent_id']==0) {
        	return $line['title'];
        } else
        	return $line['title']." - ".GetPath1I($db, $line['parent_id']);
    }


	if (isset($_GET['id'])) {
		$id = digits_only($_GET['id']);
	} else {
		if (isset($_GET['char_id'])) {
			$char_id = $_GET['char_id'];
			if (substr($char_id,-1)=="/") $char_id = substr($char_id,0,strlen($char_id)-1);
		   	$pg = "sale/1";
		} else {
			$char_id = "about/frisbee";
			$pg = "sale";
			//$id=0;
	    	//$pg = "man";
	    }

		if ($id = GetIDbyPath($db, $char_id,0,"sale")) {
			$title = GetPath1($db, $id,0);
		} else {
			$id = 0;
		}
	}

	if ($id) {
	    $sql = "SELECT * FROM sale WHERE id=$id";
	   	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	    $line = $db->sql_fetchrow($rst);
	    if (trim($line['descr']))
			$title = trim($line['descr']);
	    $meta_description = $line['meta'];;
	    $meta_keywords = "магазин,купить,фризби,".$title;
    } else {
	    $title = "Магазин";
	    $meta_description = $title;
	    $meta_keywords = "купить,фризби";
	    $sql = "SELECT * FROM sale WHERE char_id='main' AND parent_id=0";
	   	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	    $line = $db->sql_fetchrow($rst);
    }
	$ttl = stripslashes($line['descr']);
	$doc = stripslashes($line['doc']);


	include_once $path_site."/tmpl/header.php";

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
            </div>
            <br /><br /></p>
        </td>

        <td width="25%">
        	<?
        		include_once ("menu.php");
        	?>
        </td>

    </tr>
</table>


<?
	include_once $path_site."/tmpl/footer.php";
?>