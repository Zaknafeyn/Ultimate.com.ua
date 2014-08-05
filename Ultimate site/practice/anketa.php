<?
	include "../tmpl/init.php";

    $title = "Анкета";
    $nifty2 = 1;
	include "../tmpl/header.php";
?>

<?
	$done = 0;
	if (isset($_REQUEST['name'])&&isset($_REQUEST['contact'])) {
    	if ($_REQUEST['i1']+$_REQUEST['i2']==$_REQUEST['ar']) {
	        $dat = date("d.m.Y H:s:i");
	        $name = $_REQUEST['name'];
	        $age = $_REQUEST['age'];
	        $occupation = $_REQUEST['occupation'];
	        $city = $_REQUEST['city'];
	        $radiobutton2 = $_REQUEST['radiobutton2'];
	        $contact = $_REQUEST['contact'];
	        $comments = $_REQUEST['comments'];
	        $sql = "INSERT INTO anketa (dat, name, city, learned_from, contact, comments,ip, age, occupation)
	            VALUES('$dat','$name','$city','$radiobutton2','$contact','$comments','".getenv("REMOTE_ADDR")."','$age','$occupation')";
	        $db->sql_query($sql) or die (mysql_error());

	        $abz = "\r\n";
	        $dat = date("d M Y, H:i:s");
	        $info  = "Дата        : " . $dat . $abz . $abz ;
	        $info .= "Имя         : " . $name . $abz;
	        $info .= "Возраст     : ". $age . $abz;
	        $info .= "Род занятий : ". $occupation . $abz;
	        $info .= "Город       : ". $city . $abz;
	        $info .= "Контакты    : ". $contact . $abz;
	        $info .= "Узнал от    : ". $radiobutton2 . $abz;
	        $info .= "Сообщение   : ". $comments . $abz;
	        $info .= "---------------------------------------------------";
	        $info .= $abz . getenv("REMOTE_ADDR");
	        mail("frisbee@tut.by","АНКЕТА ultimate.com.ua: $name, $contact",$info);

	        $done = 1;
        }
    }

?>
<script language="javascript">
<!--

function sendform() {

	if (document.form1.name.value == "") {
    	alert('Представьтесь, пожалуйста.');
        document.form1.name.focus();
		return false;
    }
    if (document.form1.city.value == "") {
    	alert('Откуда Вы?');
        document.form1.city.focus();
		return false;
    }
    if (document.form1.contact.value == "") {
    	alert('Сообщите, пожалуйста, как с Вами связаться.');
        document.form1.contact.focus();
		return false;
    }

	return true;
}

//-->

</script>


<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
    <tr valign="top">
        <td>
	    	<table width="100%" cellspacing="0" cellpadding="10" border="0">
                <tr valign="top">
					<td>
                        <? if (!$done) { ?>
                			<div class="titlemain">Заполните, пожалуйста, анкету</div>
                			<p>
                            <form name="form1" method="post" action="/anketa/" onsubmit="return sendform();">
                            	<div id="round2"><div id="padding">
                           		<h4>Обязательно заполните эти поля:</h4>
                              <table border="0" dwcopytype="CopyTableRow" cellspacing="1" cellpadding="3" width="100%">
                                <tr valign="top">
                                  <td><div align="right"><p>Как Вас зовут?</p></div></td>
                                  <td><p><input name="name" type="text" size="37"></p></td>
                                </tr>
                                <tr valign="top">
                                  <td><div align="right"><p>Из какого Вы города?</p></div></td>
                                  <td><p><input name="city" type="text" size="37"></p></td>
                                </tr>
                                <tr valign="top">
                                  <td><div align="right"><p>Контактная информация<br /><nobr>(телефон,&nbsp;e-mail,&nbsp;ICQ)</nobr></p></div></td>
                                  <td><p><input name="contact" type="text" size="37"></p></td>
                                </tr>
                                <tr valign="top">
                                	<? list($usec, $sec) = explode(' ', microtime()); srand((float) $sec + ((float) $usec * 100000)); $i1=rand(1,9); $i2=rand(1,9); ?>
                                    <input type="hidden" name="i1" value="<?=$i1?>">
                                    <input type="hidden" name="i2" value="<?=$i2?>">
                                    <td><div align="right"><p>Сколько будет <?=$i1?> плюс <?=$i2?>?</p></div></td>
                                    <td><p><input name="ar" type="text" size="7"></p></td>
                                </tr>
                              </table>
                              </div></div>
                              <div id="padding">
                              	<p>
                           		<h4>Можете заполнить еще и эти поля:</h4>
                              <table border="0" dwcopytype="CopyTableRow" cellspacing="1" cellpadding="3" width="100%">
                                <tr valign="top">
                                  <td><div align="right"><p>Ваш возраст</p></div></td>
                                  <td><p><input name="age" type="text" size="37"></p></td>
                                </tr>
                                <tr valign="top">
                                  <td><div align="right"><p>Род занятий</p></div></td>
                                  <td><p><input name="occupation" type="text" size="37"></p></td>
                                </tr>
                                <tr valign="top">
                                  <td valign="top"><div align="right"><p>О фрисби Вы узнали&nbsp;</div></td>
                                  <td><p>
                                  	<input type="radio" style="width: 20px; border:0;" name="radiobutton2" value="От друзей">От друзей<br>
                                  	<input type="radio" style="width: 20px; border:0;" name="radiobutton2" value="На презентации">На презентации<br>
                                    <input type="radio" style="width: 20px; border:0;" name="radiobutton2" value="Из буклетов-журналов">Из газет/журналов<br>
                                    <input type="radio" style="width: 20px; border:0;" name="radiobutton2" value="Случайно увидел на улице">Случайно увидел на улице<br>
                                    <input type="radio" style="width: 20px; border:0;" name="radiobutton2" value="Из интернета">Из интернета
                                  </td>
                                </tr>
                                <tr valign="top">
                                    <td valign=top><div align="right"><p>Примечание&nbsp;</div></td>
                                	<td><textarea rows="5" name="comments" style="width:250px;"></textarea></td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="2" align="center">
                                    </td>
                                </tr>
                                <tr height="10">
                                    <td colspan="2"></td>
                                    </tr>
                                <tr height="36" valign="top">
                                  <td colspan="2" align="center" valign="middle"><input type="submit" name="Submit" value="Отправить информацию"></td>
                                </tr>
                              </table>
                              </div>
                            </form>
                        <? } else { ?>
                        	<center>
                            <p><div id="titlemain">Спасибо, <?=$_REQUEST['name']?>, в&nbsp;ближайшее время мы&nbsp;с&nbsp;Вами свяжемся.</div></p>
                            <p>Если, конечно, контактная информация указана верно.</p>
                            </center>
                            <div id="round2" style="display:none"></div>
                        <? } ?>
                    </td>
                </tr>
            </table>
        </td>
        <td>
        </td>
    </tr>
</table>

<?
	include "../tmpl/footer.php";
?>