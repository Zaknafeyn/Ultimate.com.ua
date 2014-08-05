<?

include ("../tmpl/init.php");

session_start();

if($_REQUEST['action']=="login")
{
	$login = isset($HTTP_POST_VARS['login']) ? $HTTP_POST_VARS['login'] : '';
	$pass  = isset($HTTP_POST_VARS['pass'])  ? $HTTP_POST_VARS['pass']  : '';

    if ($login == '') {
		$error="Вы должны ввести имя пользователя";
    } else {
	    $sql="SELECT * FROM phpbb_users WHERE username='$login'";
	    $rst=$db->sql_query($sql) or die ($sql);
	    if (!$db->sql_affectedrows($rst)) {
			$error="Неверное имя пользователя";
	    } else {
	        $line=$db->sql_fetchrow($rst,MYSQL_ASSOC);
	    	$sql1 = "SELECT * FROM admins WHERE id_forum_user=$line[user_id]";
	    	$rst1 = $db->sql_query($sql1) or die ($sql1);
            if (!$db->sql_affectedrows($rst1)) {
				$error="У Вас нет прав администрирования";
            } else {
	            if ($line['user_password']==md5($pass)) {
	                session_register("login");
	                $_SESSION['idadmin']=$line['user_id'];
	                //$_SESSION['uname']=$line['user_realname'];
	                session_register("rights");
                    $_SESSION['rights'] = $db->sql_fetchrow($rst1);
	                header("location:main.php");
	            } else {
	                $error="Неверный пароль";
	            }
            }
      	}
    }
};

if($_REQUEST["action"]=="logout")
{
	session_unregister("login");
    header("location:/");
};

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

	<title>Вход в систему администрирования сайта</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">

<link href="css/admin.css" rel="stylesheet">

    <link rel="icon" href="/admin/favicon.ico" type="image/x-icon">
    
	<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon">
	
</head>


<body>

<br>

<br>

<br>

<br>

<br>

<br>

<?if($error):?>

<div align="center"><span class="error"><?=$error?></span></div>

<?endif;?>

<table cellspacing="1" cellpadding="3" align="center" style="border: 1px solid #999">

<form action="" method="post">

<input type="hidden" name="action" value="login">

<tr>

  <th>Вход в систему администрирования сайта</th>

</tr>

<tr>

  <td class="light">Логин
  <br /><input type="text" name="login" class="inp w2" style="font-size: 2em; padding: 5;" value="<?=$_REQUEST['u']?>"></td></tr>

<tr>

  <td class="light">Пароль
  <br /><input type="password" name="pass" class="inp w2" style="font-size: 2em; padding: 5;"></td></tr>

<tr><td align="right" class="light"><input type="submit" value="Войти" class="inp"></td></tr></form>

</table>

</body>

</html>