<?php
include ("c_config.php");

	if (isset($_GET['mid'])){$mid = $_GET['mid'];}
	if (isset($_POST['mid'])){$mid = $_POST['mid'];}

	$id_user = sp_room($mid);
	$name = get_user_param($id_user,"name");

	if (isset($_GET['words'])){$words = $_GET['words'];}
	if (isset($_POST['words'])){$words = $_POST['words'];}

  $whom='';
	if (isset($_GET['whom'])){$whom = $_GET['whom'];}
	if (isset($_POST['whom'])){$whom = $_POST['whom'];}

	if (isset($_POST['not_smile'])){$not_smile = $_POST['not_smile'];}
	if (isset($_POST['w_stat'])){$w_stat = $_POST['w_stat'];}
	if (isset($_POST['w_act'])){$w_act = $_POST['w_act'];}

	if (!isset($not_smile)){$not_smile='off';};
	if (!isset($w_stat)){$w_stat='здесь';};
	if (!isset($w_act)){$w_act='сказать';};
	if (!isset($words)){$words='';};

	if (isset($_GET['lmid'])){$lmid = $_GET['lmid'];}
	if (isset($_POST['lmid'])){$lmid = $_POST['lmid'];}
	if (isset($_GET['lut'])){$lut = $_GET['lut'];}
	if (isset($_POST['lut'])){$lut = $_POST['lut'];}

	if ($id_user == -1) {sp_pe(1);}

	$status = get_user_param($id_user, "rights");

  $words = sp_c_m_s4(sp_c_m_s(substr($words,0, $max_strlen+5)));
	$whom = sp_c_m_s4(sp_c_m_s($whom));
  $whom = sp_apply_restrictions($id_user, $whom);
	$w_stat = sp_c_m_s4(sp_c_m_s($w_stat));

	if (($words == "")&&(($w_act == $str_act_say)||($w_act == $str_act_private)||($w_act == $str_act_do)))  {exit;}

// проверка времени последнего ответа
	if (!sp_user_can_say($mid)){exit;}

	$tnot_smile = sp_c_m_s($not_smile);

	$er=0;
	if ($tnot_smile != $not_smile){$er=1;}

	if ($er == 1){exit;}

	$not_smile = $tnot_smile;

	$serv_color = 'red';

	$sex = get_user_param($id_user,"sex");

# Разбиваем входную строку на 3 части ($code1 и $code2 и code3)
	$w = $words."         ";
	list($code1,$code2,$code3)= split ('[ ]', ltrim($w));

	if (($code1 == "@promote")&&($code2 != "")&&($code3 != "")&&($status == "admin")) {
	  if ((($code3 == "guest")||($code3 == "guard")||($code3 == "admin"))&&($code2 != $name)) {
			if (substr($code2,0,1)=="%") {
      	$id_user1 = substr($code2,1);
        $name1 = get_user_param($id_user1, "name");
      } else {
      	$id_user1 = get_user_id_by_name($code2);
        $name1 = $code2;
      }

	    $sql = "UPDATE my4_users SET rights='$code3' WHERE id_user=$id_user1";
	    $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
	    /*if ($code3 == "guest") {$g = $str_46;}
	    if ($code3 == "guard") {$g = $str_47;}
	    if ($code3 == "admin") {$g = $str_48;}*/
	    sp_print_s($id_user,"<b> * <font color=$serv_color>$name</font> устанавливает <font color=$serv_color>$name1</font> новый статус <font color=$serv_color>$code3</font></b>",3);
    }
	  sp_chat_update($mid, $lmid, $lut);
	  exit;
	}

	if ( (($code1=="@ban")||($code1=="@caps")||($code1=="@vowel")||($code1=="@consonan")||($code1=="@swear")) && ($code2 != "") && ($status != "guest") ) {
		process_command($id_user, $code1, $code2, $code3);
	  exit;
	}

/*	if (($code1 == "@clear")&&($status != "guest")){
    $sql = "DELETE FROM my4_messages";
    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

    sp_print_s($id_user,"<b> * <font color=$serv_color>$name</font> $str_51. Нажмите <a>F5</a> :)</b>",3);

	  sp_chat_update($mid, $lmid, $lut);
	  exit;
	}*/

	if (($code1 == "@delmessage")&&($code2 != "")&&($status != "guest")) {
		process_command($id_user, $code1, $code2, $code3);
	  exit;
	}

	if (($code1 == "@kick")&&($code2 != "")&&($status != "guest")){
		process_command($id_user, $code1, $code2, $code3);
    exit;
	}

	if ($code1 == "@quit"){
	  sp_del_user_session($id_user);
	  sp_say_exit($id_user);
	  sp_pe(4);
	  exit;
	}

	if ($code1 == "@changestatus"){

	  $sql = "UPDATE my4_session SET status='$w_stat', lut=".time()." WHERE id_user=$id_user";
	  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

	  sp_chat_update($mid,$lmid,$lut);
	  exit;
	}

if (($code1 == "@topmessage")&&($code2 != "")&&($status != "guest")){
/* $w_array = array();
 $w_array = split ('[ ]', $words);
 $m_g = "";
 $co = sizeof ($w_array);
 for ($i=1;$i<$co;$i++){ $m_g .= " ".$w_array[$i];}
 $m_g = "<font color=#008800 size=-1> ".$m_g." </font><br>";
 # Переписываем файл
 $pfp1 = fopen($c_p.$topmessages_suff, "w" );
 fputs ($pfp1, $m_g);
 @fflush($pfp1);
 fclose($pfp1);
 sp_d_trp($topmessages_suff);
 sp_chat_update($mid, $lmid, $lut);*/
 exit;
}

if (($code1 == "@addmessage")&&($code2 != "")&&($status != "guest")){
/* $w_array = array();
 $w_array = split ('[ ]', $words);
 $m_g = "";

 $co = sizeof ($w_array);
 for ($i=1;$i<$co;$i++){ $m_g .= " ".$w_array[$i];}

 # Читаем файл верхнего сообщения для главного фрейма
 $b_array =array();

 $m_g = "<font color=#0000ff size=-1> ***  ".$m_g." </font>";

 sp_c_trp($topmessages_suff);
 $topbf1 = file ($c_p.$topmessages_suff);
 $co = sizeof ($topbf1);
 $pfp1 = fopen($c_p.$topmessages_suff, "w" );
 for ($i=0;$i<$co;$i++){ fputs ($pfp1, $topbf1[$i]);}
 fputs ($pfp1, $m_g."\n");
 @fflush($pfp1);
 fclose($pfp1);

 sp_d_trp($topmessages_suff);
 sp_chat_update($mid, $lmid, $lut);*/
 exit;
}

	if ($not_smile == "on"){
  	$words = sp_add_smile($words);
//    if ($status != "admin")
    	$words = check_url_etc($words, $id_user);
  }

	if ($w_act != $str_act_private) { ?>
	  <script>
	    parent.bottomframe.bottomform.w_act.selectedIndex=0;
	    parent.bottomframe.bottomform.whom.value="";
	  </script>
	<?php }
?>
	<script>
	  parent.bottomframe.bottomform.words.value="";
	  parent.bottomframe.bottomform.words.focus();
	</script>  
<?php
  
  
  $who1 = "";
  $whom1 = "";
  $private = 0;

# Это сообщение
        if (($w_act == $str_act_say)||($w_act == $str_act_private)||(!$w_act)) {
    if ($w_act != $str_act_private) {
      $whom2 = "<z>".$whom."</z>";
      $private = 0;
    } else {
      $whom2 = "<zz>".$whom."</zz>";
      $private = 1;
    }

//    $who1 = "<font color=".get_user_param($id_user,"nick_color")." style=cursor:hand; onClick=parent.sn(&quot;$name&quot;);parent.sn2(".(0+$private).");><b><u>$name</u></b></font>";
//    $who1 = "<font color=".get_user_param($id_user,"nick_color")." style=cursor:hand; onClick=parent.s3(`$name`,".(0+$private).");><b><u>$name</u></b></font>";
    $who1 = "<a href=javascript:parent.sn3('$name',".(0+$private).")><font color=".get_user_param($id_user,"nick_color")."><b><u>$name</u></b></font></a>";
    $who1 .= "<font color=".get_user_param($id_user,"mes_color").">";
    $whom1 = ($whom) ? "&gt;<a href=javascript:parent.sn3('$whom',".(0+$private).")><font color=".get_user_param($id_user,"mes_color")."><u><b>$whom2</b></u></a> " : "";
    $words1 = ": $words";
        }

# Это действия
        if ($w_act == $str_act_ask) {
          $words1 = "<font color=$act_ask_color><b>*&nbsp;$name " . $str_act_ask_str;
          if ($whom) { $words1 .= " у $whom"; }
    $words1 .= " $words</b></font>";
        }

        if ($w_act == $str_act_send) {
                $words1 = "<font color=$act_send_color><b>*&nbsp;$name " . $str_act_send_str;
                if ($whom) { $words1 .= " $whom"; }
                $words1 .= " " . $str_act_send_str_tail . " $words</b></font>";
        }

        if ($w_act == $str_act_think) {
          $words1 = "<font color=$act_think_color><b>*&nbsp;$name " . $str_act_think_str;
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " $words</b></font>";
        }

        if ($w_act == $str_act_do) {
          $words1 = "<font color=$act_do_color><b>*&nbsp;$name $words";
          if ($whom) { $words1 .= " <b>$whom</b>"; }
          $words1 .= "</b></font>";
        }

        if ($w_act == $str_act_gift) {
          $words1 = "<font color=$act_gift_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_gift_str_w; } else { $words1 .= $str_act_gift_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " $words</b></font>";
        }

        if ($w_act == $str_act_tea) {
          $words1 = "<font color=$act_tea_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_tea_str_w; } else { $words1 .= $str_act_tea_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " " . $str_act_tea_str_tail . " <img src=images/tea.gif> $words</b></font>";
        }

        if ($w_act == $str_act_coffee) {
          $words1 = "<font color=$act_coffee_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_coffee_str_w; } else { $words1 .= $str_act_coffee_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " ". $str_act_coffee_str_tail ." <img src=images/coffee.gif> $words</b></font>";
        }

        if ($w_act == $str_act_meet) {
          $words1 = "$a_teg<font color=$act_meet_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_meet_str_w; } else { $words1 .= $str_act_meet_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " ". $str_act_meet_str_tail. " <img src=images/meet.gif> $words</b></font>";
        }

        if ($w_act == $str_act_100b ) {
          $words1 = "<font color=$act_100b_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_100b_str_w; } else { $words1 .= $str_act_100b_str_m; }
          if ($whom) { $words1 .= " ".$whom; }
          $words1 .= " " . $str_act_100b_str_tail . " <img src=images/100b.gif> $words</b></font>";
        }

        if ($w_act == $str_act_song ) {
          $words1 = "<font color=$act_song_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_song_str_w; } else { $words1 .= $str_act_song_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " " . $str_act_song_str_tail . ": $words</b></font> <img src=images/song.gif>";
        }

        if ($w_act == $str_act_naezd ) {
          $words1 = "<font color=$act_naezd_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_naezd_str_w; } else { $words1 .= $str_act_naezd_str_m; }
          if ($whom) { $words1 .= " на $whom"; }
          $words1 .= "<img src=images/naezd.gif> $words</b></font>";
        }

        if ($w_act == $str_act_teeth ) {
          $words1 = "<font color=$act_teeth_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_teeth_str_w; } else { $words1 .= $str_act_teeth_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " <img src=images/teeth.gif> $words</b></font>";
        }

        if ($w_act == $str_act_phone ) {
          $words1 = "<font color=$act_phone_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_phone_str_w; } else { $words1 .= $str_act_phone_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " <img src=images/phone.gif> $words</b></font>";
        }

        if ($w_act == $str_act_ears ) {
          $words1 = "<font color=$act_ears_color><b>*&nbsp;$name ";
          if ($sex == "w") { $words1 .= $str_act_ears_str_w; } else { $words1 .= $str_act_ears_str_m; }
          if ($whom) { $words1 .= " $whom"; }
          $words1 .= " " . $str_act_ears_str_tail . " <img src=images/bunny.gif> $words</b></font>";
        }


  $words1 = sp_apply_restrictions($id_user, $words1);

  // проверка на повторение предыдущего сообщения 
  $sql = "SELECT * FROM my4_messages WHERE who_id=$id_user ORDER BY -mtime LIMIT 1"; 
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error()); 
  if ($db->sql_affectedrows($rst)) { 
          $line = $db->sql_fetchrow($rst, MYSQL_ASSOC); 
    if ( ($line['private']==$private) && ($line['whom']==$whom1) && (trim($line['message'])==trim($words1)) ) { 
            ?><script>alert('<?php print $str_empty_flood; ?>');</script><?php 
            exit; 
    } 
  }  
  
  $sql = 'INSERT INTO my4_messages (private, who_id, who, whom, message, mtime)
    VALUES('.$private.', '.$id_user.', "'.$who1.'", "'.$whom1.'", "'.$words1.' ", '.time().')';

  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

  sp_chat_update($mid, $lmid, $lut);
?>
