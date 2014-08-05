<?
	if (isset($_REQUEST['name'])&&isset($_REQUEST['contact'])) {
		include_once $_SERVER['DOCUMENT_ROOT']."/tmpl/init.php";
        $dat = date("d.m.Y H:s:i");
        $name = mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$_REQUEST['name']));
        $age = mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$_REQUEST['age']));
        $occupation = mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$_REQUEST['occupation']));
        $city = mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$_REQUEST['city']));
        $contact = mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$_REQUEST['contact']));
        $comments = mysql_escape_string(iconv("UTF-8","WINDOWS-1251",$_REQUEST['comments']));

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
        $info .= "Сообщение   : ". $comments . $abz;
        $info .= "---------------------------------------------------";
        $info .= $abz . getenv("REMOTE_ADDR");


        echo "<div id=\"DivAnketaOK\"><p>".iconv("WINDOWS-1251","UTF-8","Кажется, все в порядке. Мы с вами свяжемся.</p><p>Если, конечно, контактная информация указана верно.")."</p></div>";

        mail("frisbee@tut.by","ANKETA ultimate.com.ua: $name, $contact",$info);
 	}
?>
