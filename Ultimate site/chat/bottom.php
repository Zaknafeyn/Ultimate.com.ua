<?php
	include ("c_config.php");
	if (getenv("REQUEST_METHOD")!='GET') exit;
	if (isset($_GET['mid'])) {
  	$mid = $_GET['mid'];
    $id_user = sp_room($mid);
    $name = get_user_param($id_user, "name");
  }	else $id_user=-1;

	if (!isset($id_user)) $id_user=-1;
	if ($id_user == -1) sp_pe(1);

    $refresh = get_user_param($id_user, "refresh");
?>
<html><head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
</head>
<style>
td{font-family: Tahoma; font-size: 12px;}
body{ font-size:12pt; background-color: #7DCF23}
a{ color:white; font-weight: bold; text-decoration: none; }
a:hover { text-decoration: underline;}
</style>

<script language='JavaScript'>
<!--
function Upd() {
	parent.hiddenframe.location.href ='hiddenframe.php?mid=<?=$mid?>&lmid='+parent.bottomframe.bottomform.lmid.value+'&lut='+parent.bottomframe.bottomform.lut.value;
    parent.connect_fails++;
    if (parent.connect_fails > (180/<?=$refresh?>)) {
    	parent.menuframe.connection_status.src=parent.img_error.src;
        parent.connect_fails = 0;
    }
    if (parent.connect_fails > (120/<?=$refresh?>)) {
    	parent.menuframe.connection_status.src=parent.img_w2.src;
    }
    if (parent.connect_fails > (60/<?=$refresh?>)) {
    	parent.menuframe.connection_status.src=parent.img_w1.src;
    }
    setTimeout("Upd()", <?php print $refresh*1000; ?>);
}
function ExitChat(){parent.f_empty.location.href='empty.php?mid=<?php echo $mid; ?>&words=@quit';}
function addSmile(code){
 bottomform.words.focus();
 bottomform.words.value=bottomform.words.value+' '+code;
}
function Aclear(){
 bottomform.w_act.selectedIndex=0;
 bottomform.whom.value='';
 bottomform.words.value='';
 bottomform.words.focus();
}
function bbcode(s){
        bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[sub]','[/sub]','[sup]','[/sup]','[strike]','[/strike]','[red]','[/red]','[green]','[/green]','[blue]','[/blue]','[yellow]','[/yellow]','[black]','[/black]');
        theSelection = document.selection.createRange().text; // Get text selection
        if (theSelection) {
                // Add tags around selection
                document.selection.createRange().text = bbtags[s] + theSelection + bbtags[s+1];
                bottomform.words.focus();
                theSelection = '';
                return;
        } else {
          bottomform.words.value+=bbtags[s]+bbtags[s+1]
  }
}//-->
</script>

<body OnLoad="Upd()">
<table border=0 cellspacing=0 cellpadding=0><tr>
<td>
<form name=bottomform action=empty.php method=post target=f_empty onSubmit='words.focus(); words.select()'>

<table border=0 cellspacing=0 cellpadding=0>
<tr>
<td valign=top>
	<table border=0 cellspacing=0 cellpadding=0>
  	<tr><td>
    	<img src=images/cross.gif style='cursor:hand;' onClick='Aclear();' alt='<?php print $str_bottom_clear; ?>' title='<?php print $str_bottom_clear; ?>'>
			<select class=i name='w_act' size=1 onChange='this.form.words.focus()'>
			<?php
			 $act_array = array($str_act_say, $str_act_private, $str_act_ask, $str_act_think, $str_act_phone, $str_act_send, $str_act_do, $str_act_gift, $str_act_tea, $str_act_coffee, $str_act_meet, $str_act_100b, $str_act_song, $str_act_naezd, $str_act_ears, $str_act_teeth);
			 $co=sizeof($act_array);
			 for ($i=0;$i<$co;$i++){
			  ?><option value='<?php echo $act_array[$i]; ?>'><?php echo $act_array[$i];
			 }
			?>
			</select>
			<input name='mid' type=hidden value='<?php echo $mid; ?>'>
	 		<input name='lmid' type=hidden>
	 		<input name='lut' type=hidden>
		 	<input name='whom' class=i type=text size=20 MAXLENGTH=20 style="width:100px;" value=''>
		 	<input name='words' class=i type=text size=70 MAXLENGTH=<?php echo $max_strlen; ?> style="width:370px;" value=''>&nbsp;
			<input class=b name=b_send  type=submit value='<?php echo $str_bottom_button_say; ?>' style='color: white; background: #6666FF'>&nbsp;
			<input class=b name=b_quit  type=button value='<?php echo $str_bottom_button_exit; ?>' onClick='ExitChat()' style='color: white; background: coral'>
		</td></tr>
    <tr><td>
			<input type=checkbox value='on' name=not_smile checked><?php echo $str_bottom_smiles; ?>&nbsp;&nbsp;
				<a href=javascript:addSmile(":D")><img src=images/icon_biggrin.gif height=15 style='border:none'></a>
                <a href=javascript:addSmile("*гы")><img src=images/lol.gif style='border:none'></a>
                <a href=javascript:addSmile(":-/")><img src=images/icon_confused.gif width=20 height=18 style='border:none'></a>
                <a href=javascript:addSmile(":P")><img src=images/icon_ton.gif width=31 height=17 style='border:none'></a>
                <a href=javascript:addSmile("*бе")><img src=images/be.gif style='border:none'></a>
                <a href=javascript:addSmile("*ем")><img src=images/chew.gif style='border:none'></a>
                <a href=javascript:addSmile("*)")><img src=images/icon_rolleyes.gif height=15 style='border:none'></a>
                <a href=javascript:addSmile("*незнаю")><img src=images/new_dontknow.gif style='border:none'></a>
                <a href=javascript:addSmile("*скука")><img src=images/bored.gif style='border:none'></a>
                <a href=javascript:addSmile("*nu")><img src=images/icon_nu.gif height=15 style='border:none'></a>
                <a href=javascript:addSmile("*love")><img src=images/icon_love.gif style='border:none'></a>
                <a href=javascript:addSmile("*hug")><img src=images/icon_hug.gif style='border:none'></a>
                <a href=javascript:addSmile("*kiss")><img src=images/icon_kiss.gif height=20 style='border:none'></a>
                <a href=javascript:addSmile("*rrr")><img src=images/icon_rrr.gif style='border:none'></a>
                <a href=javascript:addSmile("*angry")><img src=images/icon_angry.gif style='border:none'></a>
                <a href=javascript:addSmile("*алк")><img src=images/alc.gif style='border:none'></a>
                <a href=javascript:addSmile("*а?")><img src=images/a.gif style='border:none'></a>
                <a href=javascript:addSmile("*что")><img src=images/what.gif style='border:none'></a>
			&nbsp;&nbsp;
			[<a href="smiles.php?page=1" onclick="window.open('smiles.php?page=1', '_smiles', 'resizable=yes,scrollbars=yes');return false;" target="_smiles">ещё смайлики</a>]<br></center>
    </td></tr>
    <tr><td><table width=100% cellspacing=1 cellpadding=0 style="border: 1px solid #C0DCC0"><tr>
            <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(0);>b</a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(2);><i>i</i></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(4);><u>u</u></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(6);><sub>sub</sub></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(8);><sup>sup</sup></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(10);><strike>strike</strike></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(12);><font color=red>red</font></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(14);><font color=green>green</font></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(16);><font color=blue>blue</font></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(18);><font color=yellow>yellow</font></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27>&nbsp;&nbsp;<a href=javascript:bbcode(20);><font color=black>black</font></a>&nbsp;&nbsp;</td>
      <td bgcolor=#409C27 width=99%></td>
    </tr></table></td></tr>  </table>
</td>

</tr>
</table>
</form>
</td>
</tr></table>
<script>
<!--
	bottomform.words.focus();
//-->
</script>
</font></body></html>