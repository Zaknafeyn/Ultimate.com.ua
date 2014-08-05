<script language="JavaScript">
<!--
var msgs=new Array();
var new_msgs;
var ppl;
var connect_fails = 0;
var img_ok = new Image; img_ok.src="images/connect/stat_ok.gif";
var img_w1 = new Image; img_w1.src="images/connect/stat_warning1.gif";
var img_w2 = new Image; img_w2.src="images/connect/stat_warning2.gif";
var img_error = new Image; img_error.src="images/connect/stat_error.gif";
function sn(s){ parent.chat.bottomframe.bottomform.whom.value = s; parent.chat.bottomframe.bottomform.words.focus(); }
function sn2(s){ ff = parent.chat.bottomframe.bottomform; ff.w_act.selectedIndexd = s; ff.words.focus(); }
function sn3(s1,s2) { ff = parent.chat.bottomframe.bottomform; ff.whom.value=s1; ff.w_act.selectedIndex = s2; ff.words.focus(); }
//-->
</script>
<? $mid=$_REQUEST['mid']; ?>
<frameset name="mainframeset" rows='*,85,0,0' frameborder='no'>
	<frameset cols='77%,*'>
	    <frameset rows='30,*' border=0>
	            <frame src="menu.php?mid=<?php echo $mid; ?>" name="menuframe" scrolling='no' NORESIZE frameborder="0">
	            <frame src="body.php?mid=<?php echo $mid; ?>" name="bodyframe">
	    </frameset>
	    <frame src="people.php?mid=<?php echo $mid; ?>" name="peopleframe">
	</frameset>
	<frame src="bottom.php?mid=<?php echo $mid; ?>" scrolling='no' name="bottomframe" NORESIZE>
	<frame name="hiddenframe" scrolling='no' NORESIZE>
	<frame src="" name="f_empty" scrolling='no' NORESIZE>
</frameset>

<!--
<frameset name="mainframeset" rows='115,*,85,50,0,0,0' frameborder='no'>
	<frame src="vtop.php?mid=<?php echo $mid; ?>" name="vtop" scrolling='no' NORESIZE frameborder="0">
	<frameset cols='77%,*'>
	    <frameset rows='30,*' border=0>
	            <frame src="menu.php?mid=<?php echo $mid; ?>" name="menuframe" scrolling='no' NORESIZE frameborder="0">
	            <frame src="body.php?mid=<?php echo $mid; ?>" name="bodyframe">
	    </frameset>
	    <frame src="people.php?mid=<?php echo $mid; ?>" name="peopleframe">
	</frameset>
	<frame src="bottom.php?mid=<?php echo $mid; ?>" scrolling='no' name="bottomframe" NORESIZE>
	<frame src="footer.php" scrolling='no' name="footer" NORESIZE>
	<frame name="hiddenframe" scrolling='no' NORESIZE>
	<frame src='' name="f_empty" scrolling='no' NORESIZE>
</frameset>
-->
