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
	if (! $rights['photo_add'] ) {
		header("location:login.php");
		exit;
	}

	function GetChild($id) {
    	$a = array();
	    $sql = "SELECT a.*,COUNT(p.id) AS cnt
	    	FROM photo AS p
	    	RIGHT JOIN photo_albums AS a
	    	ON p.id_album=a.id
	    	WHERE parent_id=$id
	    	GROUP BY a.id
	    	ORDER BY o DESC";
        $rst = $db->sql_query($sql);
        while ($line = $db->sql_fetchrow($rst))
            array_push($a, array(
            	"id"=>$line['id'],
            	"id_forum_user"=>$line['id_forum_user'],
            	"title"=>$line['title'],
            	"picasa"=>($line['picasa_user']?1:0),
            	"o"=>$line['o'],
            	"parent_id"=>$line['parent_id'],
            	"child"=>GetChild($line['id']),
            	"char_id"=>$line['char_id'],
            	"cnt"=>$line['cnt']));
    	return $a;
    }

    function ExpandNode($a,$i) {
	    global $rights;
    	foreach($a as $val)
        	if (sizeof($val)) {
            	print "<ul style=\"margin-left: 25\">";
            	print "<li style=\"margin-left: 20\"><b>";
                if ($val["picasa"])
                	print "<img src=\"/upload/picasaweb-favicon.ico\" /> ";
				if ($rights['all_rights']||$rights['photo_add'])
                	print "<a href=photo_det.php?action=edit&id=".$val["id"].">".stripslashes($val["title"])."</a> <font size=2 style=\"font-weight: normal\">(".$val["char_id"].")</font>";
                else
                	print stripslashes($val["title"])." (".$val["char_id"].")";
                print "</b>";
                if (!$val["picasa"]) {
	            	print " <a href=photo_det.php?action=addphoto&id=".$val["id"]."><b><font size=-1 color=blue>ЗАКАЧАТЬ</font></b></a>";
    	        	if ($val["cnt"]) print " <a href=photo_det.php?action=view&id=".$val["id"]."><font size=-1 color=green>фото на главную</font></a>";
					if ($rights['all_rights']) {
	        	        print " <a href=photo_det.php?action=delete_photo&id=".$val["id"]."><font size=-1 color=red>удалить выборочно фото</font></a>";
					}
				}
	            if ($rights['all_rights']||$rights['photo_add'])
	            	print "<br /><a href=photo_det.php?action=add&parent_id=".$val["id"]."><font size=-1 color=green>добавить альбом</font></a>";
                ExpandNode($val["child"],"в ".$val["title"], $val["id"]);
            	print "</li></ul><br />";
            }
    }

    $albums = GetChild(0);

	$title = "Фото";
	include "include/header.php";
?>


<table width="100%" border="0" cellspacing="1" align="left" valign="top">
<td class="dark"></td>
</tr>
<tr><td class="light">

	<? ExpandNode($albums,0); ?>

<? if ($rights['all_rights']||$rights['photo_add']) { ?>
	<a href=photo_det.php?action=add&parent_id=0><font size=-1 color=green>[новая категория / новый альбом]</font></a>
<? } ?>

</td></tr>
</table>
<!-- End of main part -->
</td></tr>
</table>
<?include "include/footer.php"?>
</body>
</html>