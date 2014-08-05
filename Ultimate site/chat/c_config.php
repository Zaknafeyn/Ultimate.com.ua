<?php

	include("vars.php");
	include("../include/init.php");

 if (isset($_GET['mid'])) {
    $mid = $_GET['mid'];
    $id_user = sp_room($mid);
    $lang = get_user_param($id_user, "lang");
    include ("lang/$lang.php");
    } elseif (isset($_POST['mid'])) {
	$mid = $_POST['mid'];
	$id_user = sp_room($mid);
	$lang = get_user_param($id_user, "lang");
	include ("lang/$lang.php");
  } else {
    include ("lang/rus.php");
  }


	global $server_time;
	$server_time = $server_time*3600;

# удаляем всё опасное
function sp_c_m_s($s){
 $s = str_replace("\n", " ", $s);
 $s = str_replace("\t", "", $s);
 $s = str_replace("\r", "", $s);
 $s = str_replace("\a", "", $s);
 $s = str_replace("\e", "", $s);
 $s = str_replace("\A", "", $s);
// $s = str_replace("|", "_", $s);
 $s = str_replace("  ", " ", $s);
 $s = str_replace("  ", " ", $s);
 $s = str_replace("  ", " ", $s);
 $s = str_replace("  ", " ", $s);
 $s = str_replace("  ", " ", $s);
// $s = str_replace("&", "&amp;", $s);
 $s = str_replace(">", "&gt;", $s);
 $s = str_replace("<", "&lt;", $s);
// $s = str_replace("\`", "", $s);
// $s = str_replace("\'", "&quot;", $s);
 $s = str_replace("\"", "&quot;", $s);
 $s = stripslashes ($s);
 return $s;
}

function sp_c_m_s2($s){ return ereg_replace("[^a-zA-Z0-9_]","",$s); }
function sp_c_m_s3($s){ return ereg_replace("[^a-z0-9_]","",$s); }

# Добавляем сообщение, что кто-то вошел в чат
function say_enter($id_user){
	global $str_say_enter_chat;
	sp_print_s($id_user,$str_say_enter_chat,0);
	return 1;
}

# Добавляем сообщение, что кто-то выходит из чата
function sp_say_exit($id_user){
	global $str_say_exit_chat;
	sp_print_s($id_user,$str_say_exit_chat,1);
	return 1;
}

# Пишем что связь разоврвалась
function say_disconnected($id_user){
	sp_print_s($id_user, '', 5);
	return 1;
}

# Печатаем сообщение об ошибке и выходим из чата
function sp_pe($i){
 global $str_link_to_server_dropped;
?>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<script language='JavaScript'>
<!--
<?php
 if ($i == 1){ ?>alert('<?php echo $str_link_to_server_dropped; ?>'); <?php }
?>
 parent.parent.window.location.href='/chat/index.php';
//-->
</script>
<?
 exit;
}

# Проверяем ip-адрес человека с именем name, а также время
# последнего его отзыва... и перезаписываем файл аккаунта чата...
function sp_check_users_ip_name($mid){
  global $sleep_period;
  global $server_time;

  $ntime = time()+$server_time;
  $ip = getenv ("REMOTE_ADDR");

  $list_changed = false;

	$sql = "SELECT * FROM my4_session";
  $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
  while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
  	$id_user1 = $line['id_user'];
	  if (($ntime - $line['lasttime'])<$sleep_period) {
	    if ($line['mid'] == $mid) {
      	$sql = "UPDATE my4_session SET lasttime=$ntime WHERE id_user=$line[id_user]";
        $rst1 = $db->sql_query($sql) or die("$sql<p>".mysql_error());
      }
	  } else {
	    $list_changed = true;
      $sql = "DELETE FROM my4_session WHERE id_user=$id_user1";
      $rst1 = $db->sql_query($sql) or die("$sql<p>".mysql_error());
      $sql = "UPDATE my4_session SET lut=$ntime LIMIT 1";
      $rst1 = $db->sql_query($sql) or die("$sql<p>".mysql_error());
	    say_disconnected($id_user1);
	  }
  }
  return 1;
}

# Удаляем из аккаунта логин с id $id_user
function sp_del_user_session($id_user){
	$sql = "DELETE FROM my4_session WHERE id_user=$id_user";
	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	$sql = "UPDATE my4_session SET lut=".time()." LIMIT 1";
	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	return 1;
}


# получение параметров пользователя
function get_user_param($id_user, $param){
	$sql = "SELECT $param FROM my4_users WHERE id_user=$id_user";
	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
  $line = $db->sql_fetchrow($rst);
  if ($param == "enter") { return ($line[0]) ? "<font color=black>$line[0]</font> " : ""; }
  return $line[0];
}

# Проверка на день рождения
function sp_check_dr($id_user){

  global $server_time;
  global $str_say_happy_birthday;

	$sql = "SELECT dr FROM my4_users WHERE id_user=$id_user";
  $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
  $line = $db->sql_fetchrow($rst);
  $m_dr = $line[0];

  $m_dr = $m_dr."....."; # безопасное подключение лишних разделителей
  list($t1_m,$t2_m,$tmp)= split ('[.]', $m_dr,3);

  $d_d=date("d",time()+$server_time);
  $d_m=date("m",time()+$server_time);

  if (("$t1_m" == "$d_d")&&("$t2_m" == "$d_m")){
    # Поздравляем с днем рождения!
    sp_print_s($id_user,$str_say_happy_birthday,2);
  }
}

# Печатаем строку в главном фрейме и в файле главного фрейма
function sp_print_s($id_user,$text,$type){
	global $time_date;
	global $server_time;
	global $str_say_disconnected1;
	global $str_say_disconnected2;


	$t1 = date("H:i:s d-m-Y",time()+$server_time);
	$t1 = "<font size=-2 color=gray>$t1</font>";

	$e_name = get_user_param($id_user, "name");
	$s_name = $e_name;
	if ($type==0){
	  $my_color = "gray";
    $avatar = "";
	  if ($avatar = get_user_param($id_user,"avatar")) {
	    $avatar = "<img style=vertical-align:middle; src=avatars/$avatar> ";
	  }
	  $e_name = "&nbsp;~&gt;&nbsp;$avatar".get_user_param($id_user,"enter")."<u><font color=black>$e_name</font></u>";
	}
	if ($type==1){$my_color = "gray"; $e_name = "&nbsp;&lt;~&nbsp;<u>$e_name</u>";}
	if ($type==2){$my_color = "#0077ff"; $t1=""; $e_name.="";}
	if ($type==3){$my_color = "brown"; $e_name="";}
	if ($type==4){$my_color = "#0099ff"; $e_name="";}
	if ($type==5){$my_color = "gray"; $text = "&nbsp;~x&nbsp;$str_say_disconnected1 <u>$e_name</u> $str_say_disconnected2"; $e_name = "";}

	$m_g = "<font size=-2 color=$my_color style=font-family:Tahoma>$e_name $text $t1</font>";

	$sql = "INSERT INTO my4_messages (message, mtime) VALUES('$m_g',".time().")";
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

 return 1;
}

# Записываем имя и дату+время последнего захода в чат
function sp_put_nil($id_user) {
	$ltime = time();
	$ip = getenv ("REMOTE_ADDR");

	$sql = "SELECT * FROM my4_log WHERE id_user=$id_user";
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
  if ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
  	if ( date("d-m-Y",$line['ltime'])!=date("d-m-Y")) inc_days_in_chat($id_user);
    $sql = "UPDATE my4_log SET lltime=ltime WHERE id_user=$id_user";
    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
    $sql = "UPDATE my4_log SET ltime=$ltime, ip='$ip' WHERE id_user=$id_user";
	  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
  } else {
	  $sql = "INSERT INTO my4_log (id_user,ltime,ip) VALUES($id_user,$ltime,'$ip')";
	  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
    inc_days_in_chat($id_user);
  }
  $sql = "UPDATE my4_users SET ltime=$ltime WHERE id_user=$id_user";
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

	return 1;
}

# увеличивает количество дней, проведенных в чате
function inc_days_in_chat($id_user){
  $sql = "UPDATE my4_users SET days=days+1 WHERE id_user=$id_user";
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	return 1;
}

# Проверка, находится ли имя в списке временно запрещенных
function sp_check_kick($id_user) {
	global $kick_period;
	global $server_time;

	$ntime = time()+$server_time;

	$sql = "SELECT * FROM my4_users WHERE id_user=$id_user";
  $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
  $line = $db->sql_fetchrow($rst, MYSQL_ASSOC);

  if ($line['ban_time']) return 1;

  $kick_time = $line['kick_time'];
  if ($ntime-$kick_time < $kick_period)
  	return 1;
  else {
	  $sql = "UPDATE my4_users SET kick_time=0, kick_id_user=0 WHERE id_user=$id_user";
	  $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
		return 0;
  }
}

# заменяем смайлики на картинки
function sp_add_smile($s){
        global $str_say_cheater;
  global $max_smiles_per_message;

  $sql = "SELECT * FROM my4_smiles";
  $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
  $smiles_count = 0;
  $s1 = str_replace(" ", "  ", $s);
  while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
//    $pattern = "/(\s|^)".preg_quote($line['code'],"/")."(\s|$)/i";
    $pattern = "/".preg_quote($line['code'],"/")."/i";
    $replace = ($line['bg']) ? "<span style=background-color:white><img src=images/$line[image] align=absmiddle></span>" : "<img src=images/$line[image] align=absmiddle>";
    if ($i = preg_match_all($pattern, $s1, $matches)) {
            $smiles_count += $i;
      $s1 = preg_replace($pattern, $replace, $s1);
    }
  }
  if ($smiles_count<=$max_smiles_per_message)
          $s = $s1;

 return $s;
}
function check_url_etc($s, $id_user) {

global $allowed_tags;
global $allowed_colors;

	$sql = "SELECT tag_img FROM my4_users WHERE id_user=$id_user";
	$rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
	$line = $db->sql_fetchrow($rst, MYSQL_NUM);
	if ($line[0]) {
          $s = preg_replace("/\[img\](http:\/\/[^?].*?)\[\/img\]/i","<img src=\\1 align=absmiddle>",$s);
  }


 foreach($allowed_tags as $tag)
          if ($i = preg_match_all("/\[".$tag."\]/", $s, $matches)) {
      $j = preg_match_all("/\[\/".$tag."\]/", $s, $matches);
      if ($j<$i)
              $s .= str_repeat("[/$tag]",$i-$j);
                        $s = preg_replace ("/\[(\/?".$tag.")\]/i", "<\\1>", $s);
    }

 foreach($allowed_colors as $color)
          if ($i = preg_match_all("/\[".$color."\]/", $s, $matches)) {
                        $s = preg_replace ("/\[(".$color.")\]/i", "<font color=\\1>", $s);
      $j = preg_match_all("/\[\/".$color."\]/", $s, $matches);
      if ($j<$i)
              $s .= str_repeat("[/$color]",$i-$j);
                        $s = preg_replace ("/\[(\/".$color.")\]/i", "</font>", $s);
    }

  	$s = preg_replace ("/((\s|^)(ht|f)+tp:\/\/[^\s]*)(\s|$)/"," <a class=lnk href=\\1 target=_blank>\\1</a> ",$s);
	$s = preg_replace ("/([\w\.]+@[a-z0-9-]+(\.[a-z0-9-]+)+)/i", "<a class=lnk href=mailto:\\1>\\1</a>",$s);
	$s = preg_replace (array("/\[(\/?(b|i|u|sub|sup|strike|marquee))\]/i"), array("<\\1>"), $s);
	$s = preg_replace ("/\[(\/?(a))\]/i","<\\1 class=lnk>",$s);
//	$s .= "</b></i></u></sub></sup></strike></marquee></a>";
  return $s;
}

# заставляем чат обновить главный фрейм
function sp_chat_update($mid, $lmid, $lut){
?><script>
	parent.frames['bottomframe'].bottomform.words.value="";
	parent.frames['hiddenframe'].location.href='hiddenframe.php?mid=<?php echo $mid; ?>&lmid=<?php echo $lmid; ?>&lut=<?php echo $lut; ?>';
</script><?php
 return 1;
}

# если ответитли в туже секунду, то возвращаем false
function sp_user_can_say($n){
/* global $c_p;
 global $f_lag;
 global $server_time;

 $t=time()+$server_time;
 $find=0;
 $ret=true;

 $n_l=array();
 sp_c_trp($f_lag);
 $cf1 = file ($c_p.$f_lag);
 $co = sizeof ($cf1);

 for ($i=0; $i<$co; $i++){
  list($n_m,$t_m)= split ('[|]', trim($cf1[$i]));
  if ($n_m==$n){
   $find=1;
   if (($t_m+1) < $t){
    array_push($n_l, $n."|".$t."\n");
   }
   else{
    array_push($n_l, $cf1[$i]);
    $ret = false;
   }
  }
  else{
   if (($t_m+10) > $t){
    array_push($n_l, $n_m."|".$t_m."\n");
   }
  }
 }

 if ($find==0){ array_push($n_l, $n."|".$t."\n"); }
 $co=sizeof($n_l);

 # Переписываем файл лагов
 $pfp1 = fopen($c_p.$f_lag, "w" );
 for ($i=0;$i<$co;$i++){@fputs ($pfp1, $n_l[$i]);}
 @fflush($pfp1);
 @fclose($pfp1);
 sp_d_trp($f_lag);
 return $ret;*/
 return 1;
}

# С помощью этой функции можно вывести информацию о том, кто в чате сейчас есть
# на любую страницу сайта (поддержка PHP необходима)
function sp_print_iapic(){
 global $sex_color;

	$sql = "SELECT * FROM my4_session";
	$rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	echo "<table cellspacing=2 cellpadding=0>";
	while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
  	$id_user1 = $line['id_user'];
    $sex1 = get_user_param($id_user1, "sex");
    if ($sex1=="") $sex1="u";
    $name1 = get_user_param($id_user1, "name");
	  if ($avatar_file = get_user_param($id_user1,"avatar")) {
	    $user_image = "<img src=\"avatars/$avatar_file\" style=\"border:none\">";
	  } else {
	    $user_image = "<img src=\"images/i_$sex1.gif\" style=\"border:none\">";
	  }
	  echo "<tr><td align=center>$user_image</td>";
	  echo "<td valign=center><font style=\"font-size: 13px;\" color=$sex_color[$sex1]>&nbsp;$name1</font>";
	}
	echo "</table>";
 return true;
}

function sp_c_m_s4($s){ return preg_replace("/([\x01-\x19])/e", "", $s);}

function sp_my_id($nn, $ipp){
 $m_id=$nn.time().$ipp.time().$nn;
 $m_id=substr(md5($m_id),0,12);
 return $m_id;
}

function sp_room($mid){
	$sql = "SELECT * FROM my4_session WHERE mid='$mid'";
  $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
  if ($db->sql_affectedrows($rst)) {
  	$line = $db->sql_fetchrow($rst, MYSQL_ASSOC);
    return $line['id_user'];
  } else return -1;
}

function my_get_avatars()
{

	$dir = @opendir('avatars');

	$avatar_images = array();
  $i=0;
	while( $file = @readdir($dir) )
	{
		if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file) )
		{
			$avatar_images[$i] = $file;
      $i++;
		}
	}

	@closedir($dir);

	@ksort($avatar_images);
	@reset($avatar_images);

	if( empty($category) )
	{
		list($category, ) = each($avatar_images);
	}
	@reset($avatar_images);

	return $avatar_images;
}

function sp_apply_restrictions($id_user, $words1) {
	$sql = "SELECT * FROM my4_users WHERE id_user=$id_user";
  $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
  $line = $db->sql_fetchrow($rst, MYSQL_ASSOC);

  if ($line['swear_time']) {
	  $words1 = preg_replace("/([^,])(,)/","\\1 мля\\2",$words1);
		$words1 = preg_replace("/([^!\?])(!|\?)/","\\1 нах\\2",$words1);
  }
  if ($line['caps_time'])
  	$words1 = strtolower($words1);
  if ($line['vowel_time'])
  	$words1 = preg_replace("/[aeiouyаеёиоуыэюя]/i","",$words1);
  if ($line['consonan_time'])
  	$words1 = preg_replace("/[bcdfghjklmnpqrstvwxzбвгджзйклмнпрстфхцчшщъь]/i","",$words1);

  return $words1;
}

function get_user_id_by_name($name) {
  $sql = "SELECT id_user, name FROM my4_users WHERE name LIKE '%$name%'";
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
  while ($line = $db->sql_fetchrow($rst,MYSQL_ASSOC))
    if (strtoupper($line['name'])==strtoupper($name))
      return $line['id_user'];
  return -1;
}

function process_command($id_user, $act, $who, $extra) {
	global $mid;
	global $lut;
  global $lmid;

  global $str_admin_kick_on;
  global $str_admin_kick_off;
  global $str_admin_ban_on;
  global $str_admin_ban_off;
  global $str_admin_caps_on;
  global $str_admin_caps_off;
  global $str_admin_vowel_on;
  global $str_admin_vowel_off;
  global $str_admin_consonan_on;
  global $str_admin_consonan_off;
  global $str_admin_swear_on;
  global $str_admin_swear_off;
  global $str_admin_delmessage_on;
  global $str_admin_delmessage_off;

	$act_array = array(
  	"kick"=>array($str_admin_kick_on,$str_admin_kick_off),
  	"ban" =>array($str_admin_ban_on,$str_admin_ban_off),
  	"caps"=>array($str_admin_ban_on,$str_admin_ban_off),
  	"vowel"=>array($str_admin_vowel_on,$str_admin_vowel_off),
  	"consonan"=>array($str_admin_consonan_on,$str_admin_consonan_off),
  	"swear"=>array($str_admin_swear_on,$str_admin_swear_off),
  	"delmessage"=>array($str_admin_delmessage_on,$str_admin_delmessage_off),
  	"delmessages"=>array($str_admin_delmessage_on,$str_admin_delmessage_off)
  );

  $status = get_user_param($id_user,"rights");
  if ($status == "guest") return -1;

  $serv_color = 'red';
  $sex = get_user_param($id_user,"sex");
  $name = get_user_param($id_user,"name");

  $users = explode(",", $who);

  $extra = ($extra) ? " $extra" : "";
  $act1 = substr($act,1);
  $act_msgs = "";
  $field1 = $act1."_time";
  $field2 = $act1."_id_user";
  switch ($act1) {
    case "ban":
    case "caps":
    case "vowel":
    case "consonan":
    case "swear":
      foreach ($users as $user) {
        if (substr($user,0,1)=="%") {
          $id_user1 = substr($user,1);
          $name1 = get_user_param($id_user1, "name");
        } else {
          $id_user1 = get_user_id_by_name($user);
          $name1 = $user;
        }
        $status1 = get_user_param($id_user1,"rights");
	      if (($status == "guard")&&(($status1 == "admin")||($status1 == "guard"))) {
	        ;
	      } else {
	        $sql = "SELECT * FROM my4_users WHERE id_user=$id_user1";
	        $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());
	        if ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
	          if ($line[$field1]!=0) {
	            $key = 1;
	            $r_time = 0;
	            $r_id_user = 0;
	          } else {
	            $key = 0;
	            $r_time = time();
	            $r_id_user = $id_user;
	          }
	        }
	        $sql = "UPDATE my4_users SET $field1=$r_time, $field2=$r_id_user WHERE id_user=$id_user1";
	        $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());

	        if ($act1 == "ban")
	          sp_del_user_session($id_user1);

	        $act_msg = "<b> * <font color=$serv_color>$name</font> ".$act_array[$act1][$key]." <font color=$serv_color>$name1</font>$extra</b>";
	        $act_msgs .= "<p>$act_msg";
	        sp_print_s($id_user,$act_msg,3);
        }
      }
      break;
    case "kick":
      foreach ($users as $user) {
        if (substr($user,0,1)=="%") {
          $id_user1 = substr($user,1);
          $name1 = get_user_param($id_user1, "name");
        } else {
          $id_user1 = get_user_id_by_name($user);
          $name1 = $user;
        }
        $status1 = get_user_param($id_user1,"rights");
	      if (($status == "guard")&&(($status1 == "admin")||($status1 == "guard"))) {
	        ;
	      } else {
	        $key = 0;
	        $r_time = time();
	        $r_id_user = $id_user;
	        $sql = "UPDATE my4_users SET $field1=$r_time, $field2=$r_id_user WHERE id_user=$id_user1";
	        $rst = $db->sql_query($sql) or die("$sql<p>".mysql_error());

          sp_del_user_session($id_user1);

	        $act_msg = "<b> * <font color=$serv_color>$name</font> ".$act_array[$act1][$key]." <font color=$serv_color>$name1</font>$extra</b>";
	        $act_msgs .= "<p>$act_msg";
	        sp_print_s($id_user,$act_msg,3);
        }
      }
      break;
    case "delmessage":
    case "delmessages":
      foreach ($users as $user) {
        if (substr($user,0,1)=="%") {
          $id_user1 = substr($user,1);
          $name1 = get_user_param($id_user1, "name");
        } else {
          $id_user1 = get_user_id_by_name($user);
          $name1 = $user;
        }
        $status1 = get_user_param($id_user1,"rights");
	      if (($status == "guard")&&(($status1 == "admin")||($status1 == "guard"))) {
	        ;
	      } else {
	        $key = 0;
	        $r_time = time();
	        $r_id_user = $id_user;

	        $sql = "DELETE FROM my4_messages WHERE who LIKE '%<b><u>$code2</u></b>%'";
	        $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
	        $sql = "DELETE FROM my4_messages WHERE message LIKE '%<b>*&nbsp;$who %'";
	        $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

	        $act_msg = "<b> * <font color=$serv_color>$name</font> ".$act_array[$act1][$key]." <font color=$serv_color>$name1</font>$extra</b>";
	        $act_msgs .= "<p>$act_msg";
	        sp_print_s($id_user,$act_msg,3);
        }
      }
      break;
  }
  sp_chat_update($mid, $lmid, $lut);

	return $act_msgs;
}

function cut_too_dark_color($color) {
  $r = hexdec(substr($color,0,2));
  $g = hexdec(substr($color,2,2));
  $b = hexdec(substr($color,4,2));

  if ($r<30 && $g<30 && $b<30) {
  	$color = "303030";
  }
	return $color;
}

function trim_nick_name($name) {
	$name = preg_replace("/[^a-zабвгдеёжзийклмнопрстуфхцчшщъьэюя0-9\/~\^!@#\$_]/i","",$name);
 	return $name;
}

?>