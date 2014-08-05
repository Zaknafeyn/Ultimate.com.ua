<?
session_start();
require("include/common.php");

if(!check_login())
{
        header("location:login.php".($_SERVER['QUERY_STRING']?"?".$_SERVER['QUERY_STRING']:""));
        exit;
};
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
        <title></title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link href="css/admin.css" rel="stylesheet">
    <link rel="icon" href="/admin/favicon.ico" type="image/x-icon">
    
	<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon">
	
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table width="100%"  border="0" cellspacing="1" cellpadding="0" align="center">
<tr><td height="50 px" colspan="2" class="light">
<!-- Top -->
<?include "include/top.php"?>
<!-- End of Top -->
</td></tr>

<tr>
<td valign="top" class="light">
<!-- Left Menu -->
<?include "include/menu.php"?>
<!-- End of Left Menu -->
</td>
<td width="100%" class="light">
	<center>
  <h1>
    <!-- Main part -->
    <!-- End of main part -->
  </h1></td></tr>
</table>

<?include "include/footer.php"?>


</body>
</html>