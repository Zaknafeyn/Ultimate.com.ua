<?
	include($_SERVER["DOCUMENT_ROOT"]."/include/globals.php");
    $lang = "ru";
    $TITLE_chat = "����� :: ��� :: ULTIMATE ������ �� ������� (����)";
	include($_SERVER["DOCUMENT_ROOT"]."/include/header.php");

	$mid = $_REQUEST['mid'];

?>
	<iframe name="archive" id="archive" src="archivebody.php?mid=<?php print $mid; ?>&day=" scrolling="yes" frameborder="0" width="100%" height="100%">
	</iframe>

<?

    $FEEDBACK_chat = "��������";
	include($_SERVER["DOCUMENT_ROOT"]."/include/footer.php");
?>