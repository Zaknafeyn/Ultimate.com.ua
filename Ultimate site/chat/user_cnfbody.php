<?php

include ("c_config.php");

if (isset($_GET['mid'])){$mid = $_GET['mid'];}
if (isset($_POST['mid'])){$mid = $_POST['mid'];}
$id_user = sp_room($mid);
if ($id_user == -1) { sp_pe(1); }
$name = get_user_param($id_user, "name");
if (!isset($name)){sp_pe(1);}

if (isset($_POST['c'])){$c = $_POST['c'];}else{$c='1';}
if (isset($_POST['refresh'])){$refresh = $_POST['refresh'];}else{$refresh=15;}
if (isset($_POST['dr'])){$dr = $_POST['dr'];}else{$dr='';}
if (isset($_POST['email'])){$email = $_POST['email'];}else{$email='';}
if (isset($_POST['enter'])){$enter = $_POST['enter'];}else{$enter='';}
if (isset($_POST['info'])){$info = $_POST['info'];}else{$info='';}
if (isset($_POST['sex'])){$sex = $_POST['sex'];}else{$sex='u';}
if (isset($_POST['bg'])){$bg = $_POST['bg'];}else{$bg='1';}
if (isset($_POST['nick_color'])){$nick_color = $_POST['nick_color'];}else{$nick_color='white';}
if (isset($_POST['mes_color'])){$mes_color = $_POST['mes_color'];}else{$mes_color='white';}
if (isset($_POST['avatar'])){$avatar = $_POST['avatar'];}
if (isset($_POST['icq'])){$icq = $_POST['icq'];}else{$icq='';}
if (isset($_POST['city'])){$city = $_POST['city'];}else{$city='';}
if (isset($_POST['top2bot'])){$top2bot = $_POST['top2bot'];}else{$top2bot='0';}
if (isset($_POST['initst'])){$initst = $_POST['initst'];}else{$initst='здесь';}
if (($sex != "m")&&($sex != "w")){$sex="u";}
if (($bg!='1')&&($bg!='0')){$bg='1';}

	$success = false;
  $photo_error = "";

if ($c == 2) {

		// удаление фотографии
	if (isset($_REQUEST['delphoto'])) {
  	print "<p>delete";
    $sql = "UPDATE my4_users SET photo='' WHERE id_user=$id_user";
    $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
  }
		// загрузка фотографии
  elseif (isset($_FILES["fname"])) {
    if ( preg_match('/\.(jpg|jpeg|gif|png)$/i', $_FILES["fname"]["name"]) )
      if (preg_match('#image\/[x\-]*([a-z]+)#', $_FILES["fname"]["type"], $filetype)) {
        if ($_FILES["fname"]["size"] <= $max_upload_file_size) {
          list($width, $height) = @getimagesize($_FILES["fname"]["tmp_name"]);
          if ($width<=$max_upload_file_width && $height<=$max_upload_file_height ) {
            $img_type = $filetype[1];
            $m_photo = uniqid(rand()).".".$img_type;
            if (move_uploaded_file($_FILES["fname"]["tmp_name"],"photo/$m_photo")) {
              @chmod("photo/$m_photo", 0664);
	            $sql = "UPDATE my4_users SET photo='$m_photo' WHERE id_user=$id_user";
	            $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());
              $photo_error='';
            } else {
              $success = false;
              $photo_error = "$str_usr_photo_load_error ".$_FILES["fname"]["name"]."!";
              $c = "1";
            }
          } else {
            $success = false;
            $photo_error = $str_usr_photo_incorrect_size;
            $c = "1";
          }
        } else {
          $success = false;
          $photo_error = $str_usr_photo_file_too_large;
          $c = "1";
        }
      }
  }

	if (!$photo_error){
	  # Обновляем параметры пользователя
	  $dr = substr(sp_c_m_s($dr),0,5);
	  $enter = sp_c_m_s($enter);
	  $initst = sp_c_m_s($initst);
	  $info = sp_c_m_s(substr($info,0,700));
	  $nick_color = substr(preg_replace("/[^a-f0-9]/i","F",$nick_color."FFFFFF"),0,6);
	  $nick_color = cut_too_dark_color($nick_color);
	  $mes_color = substr(preg_replace("/[^a-f0-9]/i","F",$mes_color."FFFFFF"),0,6);
	  $mes_color = cut_too_dark_color($mes_color);

	  $sql = "UPDATE my4_users
	    SET top2bot=$top2bot, refresh='$refresh', sex='$sex', dr='$dr', enter='$enter', info='$info', email='$email',
	      icq='$icq', city='$city', nick_color='$nick_color', mes_color='$mes_color', avatar='$avatar', bg=$bg, initst='$initst'
	   		WHERE id_user=$id_user";
	  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

	  $success = true;
	}
}

# Читаем файл пользователя и ищем там спец.информацию.

	$sql = "SELECT * FROM my4_users WHERE id_user=$id_user";
  $rst = $db->sql_query($sql) or die("<p>$sql<p>".mysql_error());

  $line = $db->sql_fetchrow($rst, MYSQL_ASSOC);
	$m_regdate = $line['regdate'];
	$m_refresh_time = $line['refresh'];
	$m_dr = $line['dr'];
	$m_enter = $line['enter'];
 	$m_info = $line['info'];
	$m_sex = $line['sex'];
	$m_bg = $line['bg'];
	$m_nick_color = $line['nick_color'];
	$m_mes_color = $line['mes_color'];
	$m_avatar = $line['avatar'];
	$m_icq = $line['icq'];
	$m_email = $line['email'];
	$m_city = $line['city'];
	$m_top2bot = $line['top2bot'];
  $m_photo = $line['photo'];
  $m_initst = $line['initst'];
  if ($m_initst=='') $m_initst='здесь';

?><html><head><title>Личные настройки :: <?php print $name; ?> :: Чат :: ULTIMATE ФРИЗБИ НА УКРАИНЕ (КИЕВ)</title></head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
<style>
	p { font-size: 12px; color: black; }
	.uname { font-size: 16px; font-weight: bold;}
</style>
</head>
<body><center>
	<form action='user_cnfbody.php' method=post enctype="multipart/form-data">
	  <table>
    	<?php

      	if ($photo_error)
	    		print "<tr><td colspan=2><font class=uinfo color=red>$photo_error</font></td></tr>";
      	elseif ($success)
	    		print "<tr><td colspan=2><font class=uinfo color=black>$str_usr_success</font></td></tr>";
      ?>
    	<tr><td colspan=2 bgcolor=#409C27>
      	<?php
        	print "<p align=center><font class=uname color=".$sex_color[get_user_param($id_user,"sex")].">&nbsp;".$name."</font> $str_usr_settings</td></tr>";
        ?>
	    <tr><td valign=top>
	    <input type=hidden name=mid value='<?php echo $mid; ?>'>
	    <input type=hidden name=c value='2'>

	    <p><?php print $str_usr_refresh; ?>:<br>
	    <select NAME=refresh size=1 class=i style='width:105px;'>
	    <?php
	       $co=sizeof($ref_array);
	       for ($i=0;$i<$co;$i++){
	        ?><option value='<?php echo $ref_array[$i]; ?>'<?php if ($m_refresh_time == $ref_array[$i]){ ?> selected<?php } ?>><?php echo $ref_array[$i]."\n";
	       }
	    ?>
	    </select>

	    <p><?php echo $str_usr_nick_color; ?><br>
	    <input class=i name='nick_color' value="<?php echo $m_nick_color; ?>" size=6 maxlength=6>
	    <select class=i onchange="document.forms[0].nick_color.value = this.options[this.selectedIndex].value;">
	    <option value='FFFFFF'>- выберите -</option>
	    <?php
	      $co = sizeof($color_array[0]);
	      for ($i=0;$i<$co;$i++){ ?>
		      <option style='background: <?php echo $color_array[1][$i]; ?>; color: <?php echo $color_array[1][$i]; ?>;' value='<?php echo $color_array[1][$i]; ?>' <?php
	      	if ($m_nick_color == $color_array[1][$i]){ ?> selected <?php }
		    	?>><?php echo $color_array[0][$i]; ?></option><?php
		    }
	    ?>
	    </select>

	    <p><?php echo $str_usr_mes_color; ?><br>
	    <input class=i name='mes_color' value="<?php echo $m_mes_color; ?>" size=6 maxlength=6>
	    <select class=i onchange="document.forms[0].mes_color.value = this.options[this.selectedIndex].value;">
	    <option value='FFFFFF'>- выберите -</option>
	    <?php
	      $co = sizeof($color_array[0]);
	      for ($i=0;$i<$co;$i++){ ?>
		      <option style='background: <?php echo $color_array[1][$i]; ?>; color: <?php echo $color_array[1][$i]; ?>;' value='<?php echo $color_array[1][$i]; ?>' <?php
	        if ($m_mes_color == $color_array[1][$i]){ ?> selected <?php }
	        	?>><?php echo $color_array[0][$i]; ?></option><?php
	      }
	    ?>
	    </select>


	    <p><?php echo $str_usr_sex; ?> <input type=radio name=sex <?php
	    if ($m_sex == "m") {echo "checked ";} ?>
	    value="m"><img src=images/i_m.gif border=0>&nbsp;&nbsp;&nbsp;
	    <input type=radio name=sex <?php
	    if ($m_sex == "w") {echo "checked ";} ?>
	    value="w"><img src=images/i_w.gif border=0>

	    <p><?php echo $str_usr_top2bot; ?>
	    <input type=radio name=top2bot <?php
	    if ($m_top2bot == "0") {echo "checked ";} ?>
	    value="0"><?php print $str_usr_top2bot1; ?>&nbsp;&nbsp;&nbsp;
	    <input type=radio name=top2bot <?php
	    if ($m_top2bot == "1") {echo "checked ";} ?>
	    value="1"><?php print $str_usr_top2bot2; ?>

	    <p><?php print $str_usr_dr; ?>:<br>
	    <input class=i type=text name=dr value='<?php echo $m_dr; ?>' maxlength=5 size=25>

	    <p><?php print $str_usr_email; ?>:<br>
	    <input class=i type=text name=email value='<?php echo $m_email; ?>' maxlength=30 size=25>

	    <p><?php print $str_usr_icq; ?>:<br>
	    <input class=i type=text name=icq value='<?php echo $m_icq; ?>' maxlength=30 size=25>

	    <p><?php print $str_usr_city; ?><br>
	    <input class=i type=text name=city value='<?php echo $m_city; ?>' maxlength=30 size=25>

	    <p><?php echo $str_usr_bg; ?> <br><input type=radio name=bg <?php
	    if ($m_bg == "0") {echo "checked ";} ?>
	    value="0"><?php echo $str_usr_bg1; ?>&nbsp;&nbsp;&nbsp;
	    <input type=radio name=bg <?php
	    if ($m_bg == "1") {echo "checked ";} ?>
	    value="1"><?php echo $str_usr_bg2; ?>

	    <p><?php echo $str_usr_enter; ?>:<br>
	    <input class=i type=text name=enter value='<?php echo $m_enter; ?>' maxlength=100 size=50>

	    <p><?php echo $str_usr_initst; ?>:<br>
	    <input class=i type=text name=initst value='<?php echo $m_initst; ?>' maxlength=50 size=50>

	    <p><?php print $str_usr_about; ?><br>
	    <textarea rows="5" name=info class=i style="width:250px;" maxlenght=400 size=70><?php echo $m_info; ?></textarea>
    </td>
    <td valign=top>
    	<p><?php print $str_usr_avatar; ?>:<br>
	    <?php
	      $avatars = my_get_avatars();
	      print "<input type=hidden name=avatar value=$m_avatar>";
	      print "<table cellspacing=5 cellpadding=3><tr>";
	      $col=0;
	      for($i = 0; $i < count($avatars); $i++)
	      {
	        print "<td align=center><img src=\"avatars/".$avatars[$i]."\"><br><input type=radio name=avatar value=".$avatars[$i];
	        if ($avatars[$i] == $m_avatar) { print " checked"; }
	        print "></td>";
	        $col++;
	        if ($col == 10) {
	          print "</tr><tr>";
	          $col = 0;
	        }
	      }
	    ?>
      </tr></table>
      <table>
      	<tr><td colspan=2><p><?php print $str_usr_current_photo; ?>:</td></tr>
        <tr>
        	<td width=150 valign=top align=center>
	          <?php
	            if (!$m_photo)
	              print "<p><img src=photo/nofoto.gif style=\"border: 1px solid #444444; vertical-align: top\" hspace=5 vspace=5>";
	            else
	              print "<p><img src=photo/$m_photo style=\"border: 1px solid #444444; vertical-align: top\" hspace=5 vspace=5>";
	          ?>
          </td>
          <td valign=top align=left>
          	<input type=hidden name=photo value='<?php echo $m_photo; ?>'>
	        	<p><input type=checkbox name=delphoto value=1> <?php print $str_usr_del_photo; ?>
            <p><?php print $str_usr_upload_photo; ?>:
            <br><?php print $str_usr_upload_restr; ?>
				    <br><input class=i style="width: 250px;" type=file name=fname>
          </td>
        </tr>
      </table>
	  </td></tr>
    <tr><td colspan=2><p align=center>
    	<input class=b style="color: white; background: #6666FF; width: 200px" type=submit value='<?php echo $str_usr_button_submit ?>' style='cursor:hand;'>
			&nbsp;&nbsp;<input class=b name=b_quit type=button value='<?php print $str_usr_button_close; ?>' onClick='javascript:window.close();' style='color: white; background: coral'>
			<br><br><br>
    </td></tr>
    </table>
	</form>
</body></html>