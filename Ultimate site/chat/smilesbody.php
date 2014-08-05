<?php

 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
 header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
 header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1

?><html><head>
<META content='text/html; charset=windows-1251' http-equiv='Content-Type'>
<link href="style.css" rel=stylesheet type=text/css>
</head>

<script language='JavaScript'>
<!--
function addsmile(s){
	ff = parent.opener.parent.bottomframe.bottomform.words;
	ff.value = ff.value+' '+s;
  ff.focus();
}
//-->
</script>

<p align=center><a href=smilesbody.php?page=1 style="font-size:14px; color: #4D5E9F">1</a>&nbsp;<a href=smilesbody.php?page=2 style="font-size:14px; color: #4D5E9F">2a</a>&nbsp;<a href=smilesbody.php?page=3 style="font-size:14px; color: #4D5E9F">2b</a>&nbsp;<a href=smilesbody.php?page=4 style="font-size:14px; color: #4D5E9F">2c</a>

<?php
	$page = $_REQUEST['page'];
  if ($page==1) {
?>

<table>
	<tr><td><table cellspacing=1><tr>
  	<td>*буги<img src="images/boogie.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*буги');"></td>
  	<td>*скука<img src="images/bored.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*скука');"></td>
  	<td>*ем<img src="images/chew.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*ем');"></td>
  	<td>*cool2<img src="images/cool2.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*cool2');"></td>
  	<td>*хлоп<img src="images/eusa_clap.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*хлоп');"></td>
  	<td>*eh<img src="images/eusa_eh.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*eh');"></td>
  	<td>*ноно<img src="images/eusa_naughty.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*ноно');"></td>
  	<td>*стена<img src="images/eusa_wall.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*стена');"></td>
  	<td>*eek<img src="images/icon_eek.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*eek');"></td>
  	<td>*imslow<img src="images/imslow.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*imslow');"></td>
  	<td>*медитация<img src="images/meditate.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*медитация');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*незнаю<img src="images/new_dontknow.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*незнаю');"></td>
  	<td>*спать<img src="images/new_sleep.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*спать');"></td>
  	<td>*воршип<span style=background-color:white><img src="images/new_worship.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*воршип');"></span></td>
  	<td>*юпи<img src="images/new_youpi.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*юпи');"></td>
  	<td>*танец2<img src="images/dance2.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*танец2');"></td>
  	<td>*угу<img src="images/nod.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*угу');"></td>
  	<td>*плакать<img src="images/weep.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*плакать');"></td>
  	<td>*whew<img src="images/whew.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*whew');"></td>
  	<td>*вау<img src="images/wow.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*вау');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*обморок<img src="images/faint.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*обморок');"></td>
  	<td>*остынь<img src="images/calm.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*остынь');"></td>
  	<td>*др<img src="images/hb.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*др');"></td>
  	<td>*поздр<img src="images/congr.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*поздр');"></td>
  	<td>*гы<img src="images/lol.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*гы');"></td>
  	<td>*фото<img src="images/foto.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*фото');"></td>
  	<td>*снег<img src="images/snow.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*снег');"></td>
  	<td>*привидение<img src="images/ghost.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*привидение');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*идея<img src="images/idea.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*идея');"></td>
  	<td>*скалка<span style=background-color:white><img src="images/skalka.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*скалка');"></span></td>
  	<td>*болтать<img src="images/talk.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*болтать');"></td>
  	<td>*солнце<img src="images/sun.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*солнце');"></td>
  	<td>*кникенс<span style=background-color:white><img src="images/knikens.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*кникенс');"></span></td>
  	<td>*вишенки<img src="images/cherry.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*вишенки');"></td>
  	<td>*инвалид<img src="images/handycap.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*инвалид');"></td>
  </tr></table></td></tr>
  <tr><td><table><tr>
  	<td>*ок<img src="images/ok.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*ок');"></td>
  	<td>*бэ<img src="images/be.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*бэ');"></td>
  	<td>*птички<img src="images/birds.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*птички');"></td>
  	<td>*хм<img src="images/hm.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*хм');"></td>
  	<td>*курить<img src="images/smoke.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*курить');"></td>
  	<td>*зубы<img src="images/teeth.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*зубы');"></td>
  	<td>*песочница<img src="images/sand.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*песочница');"></td>
  	<td>*силач<span style=background-color:white><img src="images/strong.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*силач');"></span></td>
  </tr></table></td></tr>
  <tr><td><table><tr>
  	<td>*хаха<span style=background-color:white><img src="images/033_hah.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*хаха');"></span></td>
  	<td>*алк<img src="images/alc.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*алк');"></td>
  	<td>*злой<img src="images/angered.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*злой');"></td>
  	<td>*пиво<span style=background-color:white><img src="images/beer.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*пиво');"></span></td>
  	<td>*bath<img src="images/bath.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*bath');"></td>
  	<td>*тост<img src="images/tost.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*тост');"></td>
  	<td>*чупа-чупс<img src="images/chupa-chups.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*чупа-чупс');"></td>
  	<td>*пузырь<img src="images/soap_bubble.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*пузырь');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*а?<img src="images/a.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*а?');"></td>
  	<td>*что<img src="images/what.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*что');"></td>
  	<td>*рыбка2<img src="images/fish2.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*рыбка2');"></td>
  	<td>*рыбка<img src="images/fish.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*рыбка');"></td>
  	<td>*акула<img src="images/shark.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*акула');"></td>
  	<td>*червяк<img src="images/worm.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*червяк');"></td>
  	<td>*конь<img src="images/budenniy.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*конь');"></td>
  	<td>*fly<img src="images/fly.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*fly');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*spot<img src="images/spot.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*spot');"></td>
  	<td>*ждать<img src="images/waiting.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*ждать');"></td>
  	<td>*nnn<span style=background-color:white><img src="images/nnn.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*nnn');"></span></td>
  	<td>*букет<img src="images/flowers.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*букет');"></td>
  	<td>*girl<img src="images/girl.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*girl');"></td>
  	<td>*funnygirl<img src="images/funnygirl.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*funnygirl');"></td>
  	<td>*date<img src="images/date.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*date');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*лебеди<img src="images/lebedy.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*лебеди');"></td>
  	<td>*мужики<img src="images/muzsiberia.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*мужики');"></td>
  	<td>*пока<img src="images/proshay.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*пока');"></td>
  	<td>*танец3<img src="images/dance3.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*танец3');"></td>
  	<td>*сердце<img src="images/heart.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*сердце');"></td>
  	<td>*ромашка<img src="images/romashka.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*ромашка');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*гроб<img src="images/gro.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*гроб');"></td>
  	<td>*маньяк<img src="images/maniac.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*маньяк');"></td>
  	<td>*молоток<img src="images/hamme008.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*молоток');"></td>
  	<td>*м16<img src="images/m16.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*м16');"></td>
  	<td>*позвонить<img src="images/phone.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*позвонить');"></td>
  	<td>*цветок<img src="images/flower.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*цветок');"></td>
  </tr></table></td></tr>
	<tr><td><table><tr>
  	<td>*stupid<img src="images/stupid.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*stupid');"></td>
  	<td>*offtopic<img src="images/offtopic.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*offtopic');"></td>
  	<td>*welcome<img src="images/welcome.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*welcome');"></td>
  	<td>*noway<img src="images/no_way.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*noway');"></td>
  	<td>*маг<img src="images/mage.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*маг');"></td>
  	<td>*бе<img src="images/be.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*бе');"></td>
  	<td>*виселица<img src="images/vis.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*виселица');"></td>
  	<td>*нафиг<img src="images/nafig.gif" style="cursor:hand; border:none; vertical-align:middle" OnClick="javascript:addsmile('*нафиг');"></td>
  </tr></table></td></tr>
</table>
<?php
	}
  if ($page==2) {
?>
<table>
	<tr><td><table cellspacing=1><tr>
  	<td>*001<img src="images/00100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*001');"></td>
  	<td>*002<img src="images/00200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*002');"></td>
  	<td>*003<img src="images/00300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*003');"></td>
  	<td>*004<img src="images/00400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*004');"></td>
  	<td>*005<img src="images/00500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*005');"></td>
  	<td>*007<img src="images/00700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*007');"></td>
  	<td>*008<img src="images/00800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*008');"></td>
  	<td>*009<img src="images/00900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*009');"></td>
  	<td>*013<img src="images/01300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*013');"></td>
  	<td>*014<img src="images/01400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*014');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
  	<td>*017<img src="images/01700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*017');"></td>
  	<td>*018<img src="images/01800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*018');"></td>
  	<td>*019<img src="images/01900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*019');"></td>
  	<td>*020<img src="images/02000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*020');"></td>
  	<td>*024<img src="images/02400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*024');"></td>
  	<td>*025<img src="images/02500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*025');"></td>
  	<td>*026<img src="images/02600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*026');"></td>
  	<td>*027<img src="images/02700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*027');"></td>
  	<td>*028<img src="images/02800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*028');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
  	<td>*029<img src="images/02900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*029');"></td>
  	<td>*031<img src="images/03100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*031');"></td>
  	<td>*032<img src="images/03200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*032');"></td>
  	<td>*033<img src="images/03300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*033');"></td>
  	<td>*034<img src="images/03400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*034');"></td>
  	<td>*035<img src="images/03500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*035');"></td>
  	<td>*036<img src="images/03600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*036');"></td>
  	<td>*037<img src="images/03700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*037');"></td>
  	<td>*039<img src="images/03900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*039');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
  	<td>*040<img src="images/04000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*040');"></td>
  	<td>*041<img src="images/04100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*041');"></td>
  	<td>*043<img src="images/04300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*043');"></td>
  	<td>*044<img src="images/04400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*044');"></td>
  	<td>*045<img src="images/04500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*045');"></td>
  	<td>*046<img src="images/04600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*046');"></td>
  	<td>*047<img src="images/04700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*047');"></td>
  	<td>*048<img src="images/04800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*048');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
  	<td>*053<img src="images/05300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*053');"></td>
  	<td>*054<img src="images/05400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*054');"></td>
  	<td>*055<img src="images/05500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*055');"></td>
  	<td>*056<img src="images/05600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*056');"></td>
  	<td>*057<img src="images/05700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*057');"></td>
  	<td>*060<img src="images/06000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*060');"></td>
	  <td>*062<img src="images/06200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*062');"></td>
	  <td>*063<img src="images/06300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*063');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*066<img src="images/06600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*066');"></td>
	  <td>*067<img src="images/06700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*067');"></td>
	  <td>*068<img src="images/06800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*068');"></td>
	  <td>*069<img src="images/06900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*069');"></td>
	  <td>*071<img src="images/07100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*071');"></td>
	  <td>*072<img src="images/07200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*072');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*073<img src="images/07300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*073');"></td>
	  <td>*074<img src="images/07400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*074');"></td>
	  <td>*075<img src="images/07500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*075');"></td>
	  <td>*076<img src="images/07600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*076');"></td>
	  <td>*077<img src="images/07700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*077');"></td>
	  <td>*078<img src="images/07800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*078');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*079<img src="images/07900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*079');"></td>
	  <td>*080<img src="images/08000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*080');"></td>
	  <td>*081<img src="images/08100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*081');"></td>
	  <td>*082<img src="images/08200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*082');"></td>
	  <td>*083<img src="images/08300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*083');"></td>
  </tr></table></td></tr>
</table>
<?php
	}
  if ($page==3) {
?>
<table>
	<tr><td><table cellspacing=1><tr>
	  <td>*084<img src="images/08400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*084');"></td>
	  <td>*085<img src="images/08500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*085');"></td>
	  <td>*086<img src="images/08600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*086');"></td>
	  <td>*087<img src="images/08700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*087');"></td>
	  <td>*088<img src="images/08800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*088');"></td>
	  <td>*089<img src="images/08900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*089');"></td>
	  <td>*090<img src="images/09000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*090');"></td>
	  <td>*091<img src="images/09100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*091');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*092<img src="images/09200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*092');"></td>
	  <td>*093<img src="images/09300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*093');"></td>
	  <td>*094<img src="images/09400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*094');"></td>
	  <td>*095<img src="images/09500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*095');"></td>
	  <td>*096<img src="images/09600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*096');"></td>
	  <td>*097<img src="images/09700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*097');"></td>
	  <td>*098<img src="images/09800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*098');"></td>
	  <td>*099<img src="images/09900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*099');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*100<img src="images/10000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*100');"></td>
	  <td>*101<img src="images/10100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*101');"></td>
	  <td>*102<img src="images/10200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*102');"></td>
	  <td>*103<img src="images/10300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*103');"></td>
	  <td>*104<img src="images/10400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*104');"></td>
	  <td>*105<img src="images/10500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*105');"></td>
	  <td>*106<img src="images/10600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*106');"></td>
	  <td>*108<img src="images/10800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*108');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*109<img src="images/10900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*109');"></td>
	  <td>*110<img src="images/11000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*110');"></td>
	  <td>*111<img src="images/11100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*111');"></td>
	  <td>*112<img src="images/11200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*112');"></td>
	  <td>*113<img src="images/11300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*113');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*114<img src="images/11400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*114');"></td>
	  <td>*116<img src="images/11600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*116');"></td>
	  <td>*117<img src="images/11700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*117');"></td>
	  <td>*118<img src="images/11800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*118');"></td>
	  <td>*119<img src="images/11900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*119');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*125<img src="images/12500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*125');"></td>
	  <td>*131<img src="images/13100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*131');"></td>
	  <td>*134<img src="images/13400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*134');"></td>
	  <td>*135<img src="images/13500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*135');"></td>
	  <td>*136<img src="images/13600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*136');"></td>
	  <td>*137<img src="images/13700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*137');"></td>
	  <td>*140<img src="images/14000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*140');"></td>
	  <td>*141<img src="images/14100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*141');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*142<img src="images/14200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*142');"></td>
	  <td>*143<img src="images/14300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*143');"></td>
	  <td>*144<img src="images/14400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*144');"></td>
	  <td>*145<img src="images/14500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*145');"></td>
	  <td>*146<img src="images/14600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*146');"></td>
	  <td>*147<img src="images/14700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*147');"></td>
	  <td>*148<img src="images/14800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*148');"></td>
	  <td>*149<img src="images/14900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*149');"></td>
	  <td>*150<img src="images/15000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*150');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*151<img src="images/15100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*151');"></td>
	  <td>*152<img src="images/15200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*152');"></td>
	  <td>*156<img src="images/15600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*156');"></td>
	  <td>*159<img src="images/15900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*159');"></td>
	  <td>*160<img src="images/16000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*160');"></td>
	  <td>*161<img src="images/16100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*161');"></td>
	  <td>*162<img src="images/16200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*162');"></td>
  </tr></table></td></tr>
</table>
<?php
	}
  if ($page==4) {
?>
<table>
	<tr><td><table cellspacing=1><tr>
	  <td>*165<img src="images/16500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*165');"></td>
	  <td>*166<img src="images/16600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*166');"></td>
	  <td>*169<img src="images/16900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*169');"></td>
	  <td>*170<img src="images/17000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*170');"></td>
	  <td>*171<img src="images/17100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*171');"></td>
	  <td>*172<img src="images/17200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*172');"></td>
	  <td>*173<img src="images/17300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*173');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*174<img src="images/17400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*174');"></td>
	  <td>*175<img src="images/17500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*175');"></td>
	  <td>*176<img src="images/17600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*176');"></td>
	  <td>*177<img src="images/17700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*177');"></td>
	  <td>*178<img src="images/17800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*178');"></td>
	  <td>*179<img src="images/17900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*179');"></td>
	  <td>*180<img src="images/18000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*180');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*183<img src="images/18300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*183');"></td>
	  <td>*187<img src="images/18700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*187');"></td>
	  <td>*188<img src="images/18800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*188');"></td>
	  <td>*189<img src="images/18900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*189');"></td>
	  <td>*191<img src="images/19100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*191');"></td>
	  <td>*193<img src="images/19300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*193');"></td>
	  <td>*194<img src="images/19400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*194');"></td>
	  <td>*196<img src="images/19600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*196');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*202<img src="images/20200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*202');"></td>
	  <td>*204<img src="images/20400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*204');"></td>
	  <td>*205<img src="images/20500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*205');"></td>
	  <td>*208<img src="images/20800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*208');"></td>
	  <td>*209<img src="images/20900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*209');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*211<img src="images/21100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*211');"></td>
	  <td>*213<img src="images/21300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*213');"></td>
	  <td>*215<img src="images/21500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*215');"></td>
	  <td>*216<img src="images/21600000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*216');"></td>
	  <td>*217<img src="images/21700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*217');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*218<img src="images/21800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*218');"></td>
	  <td>*219<img src="images/21900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*219');"></td>
	  <td>*222<img src="images/22200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*222');"></td>
	  <td>*227<img src="images/22700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*227');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*230<img src="images/23000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*230');"></td>
	  <td>*231<img src="images/23100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*231');"></td>
	  <td>*232<img src="images/23200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*232');"></td>
	  <td>*235<img src="images/23500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*235');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*239<img src="images/23900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*239');"></td>
	  <td>*240<img src="images/24000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*240');"></td>
	  <td>*244<img src="images/24400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*244');"></td>
	  <td>*245<img src="images/24500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*245');"></td>
	  <td>*248<img src="images/24800000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*248');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*249<img src="images/24900000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*249');"></td>
	  <td>*250<img src="images/25000000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*250');"></td>
	  <td>*252<img src="images/25200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*252');"></td>
	  <td>*253<img src="images/25300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*253');"></td>
	  <td>*254<img src="images/25400000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*254');"></td>
  </tr></table></td></tr>
	<tr><td><table cellspacing=1><tr>
	  <td>*255<img src="images/25500000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*255');"></td>
	  <td>*257<img src="images/25700000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*257');"></td>
	  <td>*301<img src="images/30100000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*301');"></td>
	  <td>*302<img src="images/30200000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*302');"></td>
	  <td>*303<img src="images/30300000.gif" style="cursor:hand; border:none; vertical-align:middle;" OnClick="javascript:addsmile('*303');"></td>
  </tr></table></td></tr>
</table>

<?php } ?>

</body></html>