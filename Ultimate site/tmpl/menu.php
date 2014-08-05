<table width="100%" cellpadding="10" cellspacing="0" border="0">
<tr>
	<td width="50%"></td>
<?
	$sql = "SELECT * FROM menu ORDER BY orderr ASC";
    $rst = $db->sql_query($sql) or die (mysql_error());
    $is_first = true;
    while ($line = $db->sql_fetchrow($rst)) {
    	?>
        	<td style="padding-left: 5; padding-right: 5">
        		<a href="<?=$line['url']?>" class="menu"><?=$line['name']?></a>
        		<a href="">eee</a>
            </td>
        <?
    }
?>
	<td width="50%"></td>
</tr>
</table>