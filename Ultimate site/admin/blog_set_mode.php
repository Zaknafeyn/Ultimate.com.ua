<?
	session_start();
	require("include/common.php");

	if (isset($_REQUEST['id'])) {
		if(!check_login())
		{
			header("location:login.php");
			exit;
		};
		$rights = $_SESSION['rights'];
		if (!$rights['all_rights'])
			if (! ($rights['news_edit'] || $rights['news_add']) ) {
			echo 0;
			exit;
		}
		$db->sql_query("UPDATE blog SET active=".$_REQUEST['mode']." WHERE id=".$_REQUEST['id']);
		echo 1;
	} else echo 0;
?>
