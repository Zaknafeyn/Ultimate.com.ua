<?php

// Array indexes are 0-based, jCarousel positions are 1-based.
$first = max(0, intval($_GET['first']) - 1);
$last  = max($first + 1, intval($_GET['last']) - 1);

$length = $last - $first + 1;

// ---

	include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";
	$pid = digits_only($_GET['pid']);
	$aid = 0;
	$r= $db->sql_query("SELECT * FROM photo WHERE id=$pid");
	if ($db->sql_affectedrows($r)) {
		$l = $db->sql_fetchrow($r);
		$aid = $l['id_album'];
	}
	$picasa = false;
	if ($db->sql_affectedrows($db->sql_query("SELECT picasa_user FROM photo_albums WHERE id=$aid AND picasa_user<>''")))
		$picasa = true;

	$r= $db->sql_query("SELECT * FROM photo WHERE id_album=$aid");
	$images = array();
	$album = GetPath($db, $aid);
	while ($l = $db->sql_fetchrow($r)) {
		array_push($images,
			array(
				'url'  => ($l['id']==$pid) ? '' : '/photo/'.$album.'/-'.$l['id'],
				'src'  => $picasa ? str_replace("/s144/","/s72/",$l['tn_fname']) :'/photo/albums/'.$album.'/small/_'.$l['fname'],
				'width'=> $picasa ? ($l['tn_width']/2): 75
			)
		);
	}

$total    = count($images);
$selected = array_slice($images, $first, $length);

// ---

header('Content-Type: text/xml');

echo '<data>';

// Return total number of images so the callback
// can set the size of the carousel.
echo '  <total>' . $total . '</total>';

foreach ($selected as $img) {
    echo '  <image><url>'.$img['url'].'</url><src>'.$img['src'].'</src><width>'.$img['width'].'</width></image>';
}

echo '</data>';

?>