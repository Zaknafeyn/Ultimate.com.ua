<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];

if (!$rights['all_rights']) {
    header("location:login.php");
    exit;
}

	if (isset($_POST['cb'])) {
    	$cb = $_POST['cb'];
        $sql = "SELECT * FROM admins";
        $rst = $db->sql_query($sql);
        while ($line = $db->sql_fetchrow($rst)) {
        	foreach($line as $key=>$val) {
            	if (substr($key,0,4)=="team") {
                	if (isset($cb[$line['id']][$key]))
                    	$db->sql_query("UPDATE admins SET ".$key."=1 WHERE id=".$line['id']);
                    else
                    	$db->sql_query("UPDATE admins SET ".$key."=0 WHERE id=".$line['id']);
                }
            }
        }
		header("location:admins_teams.php");
		exit;
    }

