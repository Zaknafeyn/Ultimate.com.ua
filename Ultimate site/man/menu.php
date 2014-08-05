<SCRIPT language=Javascript>
<!--
  var curDiv = 0;
  var lastDiv = 2;

  var browser;
  if ( document.all ) browser = 1;
  else if ( document.layers ) browser = 2;
  else if ( document.getElementById ) browser = 3;

  function toggle(divNum) {
    if ( divNum == curDiv ) {
      divHide(curDiv);
      curDiv = 0;
    } else {
      if ( curDiv>0 ) divHide(curDiv);
      divShow(divNum);
      curDiv = divNum;
    }
  }

  function divShow(divNum){
    if ( browser == 1 ) {
      document.all["sub" + divNum].style.display='block';
    } else if ( browser == 2 ) {
      o = document.layers["tci"];
      o.layers["sub"+divNum].visibility='show';
      h = o.layers["sub"+divNum].clip.height;
      for (i=divNum+1; i<=lastDiv; i++ ) {
        o.layers["lab"+i].moveBy(0,h);
      }
    } else if ( browser == 3 ) {
      document.getElementById("sub" + divNum).style.display="block";
    }
  }

  function divHide(divNum){
    if ( browser == 1 ) {
      document.all["sub" + divNum].style.display='none';
    } else if ( browser == 2 ) {
      o = document.layers["tci"];
      o.layers["sub"+divNum].visibility='hide';
      h = o.layers["sub"+divNum].clip.height;
      for (i=divNum+1; i<=lastDiv; i++ ) {
        o.layers["lab"+i].moveBy(0,-h);
      }
    } else if ( browser == 3 ) {
      document.getElementById("sub" + divNum).style.display="none";
    }
  }

  function imgSwap(iname,isrc) {
    if ( document.images && ! document.layers ) {
      var o = eval("document."+iname);
      o.src = isrc;
    }
  }
//-->
</SCRIPT>
<?
$menu = array ();
$r = $db->sql_query ( "SELECT * FROM manual" );
while ( $l = $db->sql_fetchrow ( $r ) ) {
	$key = GetPath2 ( $db, $l ['id'] );
	$title = stripslashes ( $l ['title'] );
	$url = "<a id=\"test\" href=\"" . $site_folder_prefix . "/man/$key/\">$title</a>";
	if ($key == $char_id) {
		$menu [$key] = "<li class=\"selected\">$title</li>";
	} else {
		$menu [$key] = "<li>" . ($l ['doc'] ? $url : $title) . "</li>";
	}
}

function GetChild($db, $id) {
	$a = array ();
	$rst = $db->sql_query ( "SELECT * FROM manual WHERE parent_id=$id ORDER BY o" );
	while ( $line = $db->sql_fetchrow ( $rst ) )
		array_push ( $a, array ($line ['parent_id'], $line ['id'], $line ['title'], ($line ['doc'] ? 1 : 0), GetChild ( $db, $line ['id'] ) ) );
	return $a;
}

$div = 2;
function ExpandNode($db, $a) {	
	global $id;
	global $div;
	global $site_folder_prefix;
	foreach ( $a as $val )
		if (sizeof ( $val )) {
			print "<div class=\"small\" style=\"padding-left: 15; line-height: 20px;\">";
			
			if ($val [0] == 0) {
				
				print "<p><LAYER id=\"lab" . $div . " top=" . $div * 20 . "\">";
				print "<a class=\"pseudo_link clickable hid\" href=\"javascript: toggle($div);\">" . $val [2] . "</a>";
				
				print "<div id=\"sub" . $div ++;
				if (GetFirstLeveID ($db,  $id ) != $val [1])
					print "\" style=\"display: none;\"";
				print ">";
				//echo $site_folder_prefix."_".$val[1]."_".$val[2]."_".$val[3]."_<br>";
			} else {
				if ($val [1] == $id) {
					print "<b>" . $val [2] . "</b>";
				} else {
					if ($val [3])
						print "<a href=\"" . $site_folder_prefix . "/man/" . GetPath2 ($db,  $val [1] ) . "/\">" . $val [2] . "</a>";
					else
						print $val [2];
				}
				print "<br />";
			}
			ExpandNode ($db, $val [4] );
			//print "</ul>";
			if ($val [0] == 0) {
				print "</div></layer>";
			}
			print "</div>";
		}
}

function GetPath2($db, $id) {
	$sql = "SELECT * FROM manual WHERE id=$id";
	$rst = $db->sql_query ( $sql ); // or die ("<p>$sql<p>".mysql_error());
	$line = $db->sql_fetchrow ( $rst );
	if ($line ['parent_id'] == 0) {
		return $line ['char_id'];
	} else
		return GetPath2 ( $db, $line ['parent_id'] ) . "/" . $line ['char_id'];
}

?>



<div class="vmenu2">
			<?
			ExpandNode ($db, GetChild ( $db, 0 ) );
			?>
			</div>
