<?php

//paths for forum and site pages
$forum_folder_name = "forum";
$site_folder_name = "";
$site_folder_prefix = "";
$path_forum = $_SERVER ['DOCUMENT_ROOT'] . "/" . $forum_folder_name;
$path_site = $_SERVER ['DOCUMENT_ROOT'];
if ($site_folder_name != "") {
	$path_site .= "/" . $site_folder_name;
	$site_folder_prefix = "/" . $site_folder_name;
}

define ( 'IN_PHPBB', true );
$phpEx = "php";

$phpbb_root_path = $path_forum . "/";

$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : $phpbb_root_path;

include_once $phpbb_root_path . "includes/db/mysqli.php";
include_once $phpbb_root_path . "includes/db/dbal.php";

//include_once $path_site . "/tmpl/connect.php";
$sql_db = "dbal_mysqli";
//echo "___".$path_forum."/includes/db/dbal.php";
include_once $phpbb_root_path . "common.php";
include_once $path_site . "/tmpl/functions.php";


//
// Start session management
//
$user->session_begin();
$auth->acl($user->data);
$user->setup();
//
// End session management
//

//$user = new user ();

$db->sql_query ( "SET NAMES 'utf8'" );

if ($user->data['user_id'] != ANONYMOUS) {
	
	//echo $user->data['username_clean'] ;
	
	$rights = array ();
	$sql = "SELECT * FROM admins WHERE id_forum_user=" . $user->data ['user_id'];
	$rst = $db->sql_query ( $sql );

	if ($db->sql_affectedrows ( $rst )) {
		$rights = $db->sql_fetchrow ( $rst );
	}
	
	$r = $db->sql_query ( "SELECT id_team FROM players WHERE id=" . $user->data ['user_id'] );
	if ($db->sql_affectedrows ( $r ))
		$l = $db->sql_fetchrow ( $r );
	$user->data ['id_team'] = $l ['id_team'];
}
else
{
	//echo "Anonymous";
}
?>