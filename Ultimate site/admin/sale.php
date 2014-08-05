<?
session_start();
require("include/common.php");

if(!check_login())
{
	header("location:login.php");
	exit;
};

$rights = $_SESSION['rights'];

if (!$rights['all_rights'])
	if (! ($rights['sale_edit'] || $rights['sale_add']) ) {
		header("location:login.php");
		exit;
	}

	function GetChild($id) {
    	$a = array();
        $rst = $db->sql_query("SELECT * FROM sale WHERE parent_id=$id ORDER BY o");
        while ($line = $db->sql_fetchrow($rst))
            array_push($a, array($line['id'],$line['id_forum_user'],$line['title'],$line['o'],$line['parent_id'],GetChild($line['id']),$line['char_id']));
    	return $a;
    }

    function ExpandNode($a,$i) {
	    global $rights;
    	foreach($a as $val)
        	if (sizeof($val)) {
            	print "<ul style=\"margin-left: 25\">";
            	print "<li style=\"margin-left: 20\">";
				if ($rights['all_rights']||$rights['sale_edit']||(!$rights['sale_edit']&&($val[1]==$rights['id_forum_user'])))
                	print "<a href=sale_det.php?action=edit&id=".$val[0]."><b>".$val[2]."</b></a> <font size=2 style=\"font-weight: normal\">(".$val[6].")</font>";
                else
                	print "<b>".$val[2]."</b> (".$val[6].")";
                if ($rights['all_rights']||$rights['sale_add'])
	                print " <a href=sale_det.php?action=add&parent_id=".$val[0]."><font size=-1 color=green>[добавить раздел/статью]</font></a>";
				if ($rights['all_rights']||$rights['sale_edit']||(!$rights['sale_edit']&&($val[1]==$rights['id_forum_user']))) {
	                print " <a href=sale_det.php?action=moveup&id=".$val[0]."&parent_id=".$val[4]."&o=".$val[3]."><font size=-1 color=blue>[вверх]</font></a>";
	                print " <a href=sale_det.php?action=movedown&id=".$val[0]."&parent_id=".$val[4]."&o=".$val[3]."><font size=-1 color=blue>[вниз]</font></a>";
	                print " <a onClick=\"if(confirm('Удалить?')) document.forms.delete".$val[0].".submit(); return false;\" href=\"sale.php\"><font size=-1 color=red>[удалить]</font></a>";
	                print "<form action=\"sale_det.php?action=delete&id=".$val[0]."\" method=\"post\" name=\"delete".$val[0]."\" id=\"delete".$val[0]."\"></form>";
                }
                print "";
                ExpandNode($val[5],"в ".$val[2], $val[0]);
            	print "</li></ul>";
            }
/*		print "<form action=\"sale_det.php\" method=\"post\" name=\"add$i\" id=\"add$i\">";
		print "<input type=\"hidden\" name=\"action\" value=\"add\">";
		print "<input type=\"hidden\" name=\"parent_id\" value=\"$i\">";
		print "</form>";
        print "<input type=\"submit\" class=\"inp\" value=\"Добавить $s\" onClick=\"document.forms.add$i.submit(); return false;\">";
*/
    }

    $sale = GetChild(0);

	$title = "Магазин";
	include "include/header.php";
?>


<table width="100%" border="0" cellspacing="1" align="left" valign="top">
<td class="dark"></td>
</tr>
<tr><td class="light">

	<? ExpandNode($sale,0); ?>

<? if ($rights['all_rights']||$rights['sale_add']) { ?>
	<h5><a href=sale_det.php?action=add&parent_id=0><font size=-1 color=green>[новый раздел]</font></a></h5>
<? } ?>

</td></tr>
</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>