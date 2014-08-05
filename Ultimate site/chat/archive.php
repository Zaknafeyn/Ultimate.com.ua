<?
	include($_SERVER["DOCUMENT_ROOT"]."/include/globals.php");
    $lang = "ru";
    $TITLE_chat = "Àðõèâ :: ×ÀÒ :: ULTIMATE ÔÐÈÇÁÈ ÍÀ ÓÊÐÀÈÍÅ (ÊÈÅÂ)";
	include($_SERVER["DOCUMENT_ROOT"]."/include/header.php");

	$mid = $_REQUEST['mid'];

?>
	<iframe name="archive" id="archive" src="archivebody.php?mid=<?php print $mid; ?>&day=" scrolling="yes" frameborder="0" width="100%" height="100%">
	</iframe>

<?

    $FEEDBACK_chat = "ÊÎÍÒÀÊÒÛ";
	include($_SERVER["DOCUMENT_ROOT"]."/include/footer.php");
?>