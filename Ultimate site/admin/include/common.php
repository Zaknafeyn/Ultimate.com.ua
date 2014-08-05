<?

include ("../tmpl/init.php");
//include ("../tmpl/functions.php");

$month1 = array ("€нварь","февраль","март","апрель","май","июнь","июль","август","сент€брь","окт€брь","но€брь","декабрь");
$month2 = array ("€нвар€","феврал€","марта","апрел€","ма€","июн€","июл€","августа","сент€бр€","окт€бр€","но€бр€","декабр€");

//$db = @mysql_connect("localhost", "root", "ytkjlrf");
//@mysql_select_db("ultimate",$db);

if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
  $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
else
  $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];

define('DR', $_root);
unset($_root);


function showtree($id)
{
	global $ids,$DB;
	$data_result=$db->sql_query("select * from  prodcategory where parent=".$id,$DB);
	print mysql_error($DB);
	while($row=$db->sql_fetchrow($data_result))
	{
		array_push($ids,$row['id']);
		showtree($row['id'],$ids);
	};
};

function check_login()
{
	if($_SESSION['login'])
		return true;
	else
		return false;
};


function check_access($tbl) {
	global $rights;
	global $action;

	if ($action=="delete"||$action=="edit"||$action=="moveup"||$action=="movedown")
	    if (!$rights['all_rights'])
	        if (!$rights[$tbl.'_edit']) {
	            $sql = "SELECT id_forum_user FROM $tbl WHERE id=".$_REQUEST['id'];
	            $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
	            $line = $db->sql_fetchrow($rst);
	            if ($line['id_forum_user']!=$rights['id_forum_user']) {
	                return false;
	                exit;
	            }
	        }

	if ($action=="add")
	    if (!$rights['all_rights'])
	        if (!$rights[$tbl.'_add']) {
	            return false;
	            exit;
	        }

	if ($action=="save")
	    if (!$rights['all_rights']) {
        	if ($_REQUEST['id']&&!$rights[$tbl.'_edit']) {
	            $sql = "SELECT id_forum_user FROM $tbl WHERE id=".$_REQUEST['id'];
	            $rst = $db->sql_query ($sql) or die ("<p>$sql<p>".mysql_error());
	            $line = $db->sql_fetchrow($rst);
	            if ($line['id_forum_user']!=$rights['id_forum_user']) {
	                return false;
	                exit;
	            }
            } else
                return ($rights[$tbl.'_add']) ? true : false;
	    }

	return true;
}






function backtree($id)
{
	global $ids,$DB;
	$data_result=$db->sql_query("select * from  prodcategory where id=".$id,$DB);
//	print "select * from  prodcategory where id=".$id;
	print mysql_error($DB);
	$row=$db->sql_fetchrow($data_result);
	array_push($ids,$row['id']);
	if($row['parent']!=0)
	{
		backtree($row['parent']);
	};
};



/*
function recalc_sort($id)
{
	global $DB;

	$res=$db->sql_query("select * from prodcategory where id=".$id."",$DB);
	$this=$db->sql_fetchrow($res);

	if($this['parent']!=0)
	{
		$res=$db->sql_query("select * from prodcategory where id=".$this['parent']."",$DB);
		$parent=$db->sql_fetchrow($res);
		$this_srt=sprintf("%02d",$this['sort']);
		$db->sql_query("update prodcategory set sort_str='".$parent['sort_str'].$this_srt."' where id=".$id." ",$DB);
	}else
	{
		$this_srt=sprintf("%02d",$this['sort']);
		$db->sql_query("update prodcategory set level=0,sort_str='".$this_srt."' where id=".$id." ",$DB);
	};


	$res=$db->sql_query("select * from prodcategory where parent=".$id."",$DB);
	while($row1=$db->sql_fetchrow($res))
	{
		$srt=sprintf("%02d",$row1['sort']);
		$db->sql_query("update prodcategory set level=".($row['level']+1).",sort_str='".$row['sort_str'].$srt."' where id=".$row1['id']." ",$DB);
		recalc_sort($row1['id']);
	};
};


function full_recalc()
{
	global $DB;
	$res=$db->sql_query("select * from prodcategory where parent=0",$DB);
	while($row=$db->sql_fetchrow($res))
		recalc_sort($row['id']);
};

*/

?>