<?php
//db source
$dbhostSource = 'ultimate.com.ua';
$usernameSource = 'ultimate_u';
$passwordSource = 'lJHe2bs89';
$dbnameSource = 'ultimate_main';
$dbPrefixSource = 'phpbb_';

//db dest
$dbhostDest = 'localhost';
$usernameDest = 'phpbb3_ultimate';
$passwordDest = 'phpbb3_ultimate';
$dbnameDest = "phpbb3_ultimate";
$dbPrefixDest = 'phpbb3_';

$ConnSource = mysql_connect($dbhostSource, $usernameSource, $passwordSource) or die('Error connecting to mysql source');
$ConnDest = mysql_connect($dbhostDest, $usernameDest, $passwordDest) or die('Error connecting to mysql'); 

mysql_select_db($dbnameSource, $ConnSource );
mysql_select_db($dbnameDest, $ConnDest );

//mysql_query('SET character_set_database = cp1251', $ConnSource);
//mysql_query('SET NAMES cp1251', $ConnSource);
mysql_query('SET character_set_database = utf8', $ConnSource);
mysql_query('SET NAMES utf8', $ConnSource);

ini_set("max_execution_time", "1000"); 

$enableLogging = 1;

//add this value for each imported userId
$userIdAdditional = 64;

//add this value for each imported groupId
$groupIdAdditional = 7;


echo "<br>Inited successfully<br>";

?>