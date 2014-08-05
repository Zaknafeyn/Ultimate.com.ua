<?
	include($_SERVER["DOCUMENT_ROOT"]."/include/globals.php");
    $lang = "ru";
    $TITLE_chat = "Ïîëüçîâàòåëÿ :: ×ÀÒ :: ULTIMATE ÔĞÈÇÁÈ ÍÀ ÓÊĞÀÈÍÅ (ÊÈÅÂ)";
	include($_SERVER["DOCUMENT_ROOT"]."/include/header.php");

	$mid = $_REQUEST['mid'];
	$uid = $_REQUEST['uid'];
	$all = $_REQUEST['all'];

?>
	<iframe name="info" id="info" src="infobody.php?mid=<?php print $mid; ?>&uid=<?php print $uid; ?>&user=&all=<?php print $all; ?>" scrolling="yes" frameborder="0" width="100%" height="100%">
	</iframe>

<?

    $FEEDBACK_chat = "ÊÎÍÒÀÊÒÛ";
	include($_SERVER["DOCUMENT_ROOT"]."/include/footer.php");
?>