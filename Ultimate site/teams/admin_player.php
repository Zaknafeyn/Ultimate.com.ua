<?
	function get_player_tournaments($pid) {
		global $db;
		$out = "";
		$rst = $db->sql_query("SELECT p.*,t.dat_begin,t.short_name,t.char_id,YEAR(dat_begin) AS y, MONTH(dat_begin) AS m, DAYOFMONTH(dat_begin) AS d
			FROM players_tourn p
			LEFT JOIN tourn t ON p.id_tourn=t.id
			WHERE p.id_player=$pid AND ptcp=1
			ORDER BY y DESC, m ASC, d ASC
		");
		if ($db->sql_affectedrows($rst)) {
			$tourn_count = $db->sql_affectedrows($rst);
			$out .= "<div class=\"info_header\">Участвовал".(sex($pid)?"":"а")." как минимум в&nbsp;$tourn_count ".num_decline($tourn_count,"турнире","турнирах","турнирах")."</div>";
			$t = array();
			while ($line = $db->sql_fetchrow($rst, MYSQL_ASSOC)) {
				$y = substr($line['dat_begin'],0,4);
				if (!isset($t[$y])) $t[$y] = array();
				array_push($t[$y], "<a href=\"/tourn/".$line['char_id']."/\">".stripslashes($line['short_name'])."</a>");
			}
			$out .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td>";
			foreach($t as $y=>$t0) {
				$out .= "<tr valign=\"top\"><td width=\"20\"><p><div class=\"small\"><b>$y</b>&nbsp;<span style=\"color: silver;\"><sup>".sizeof($t0)."</span></sup></div></p></td>";
				$out .= "<td><p><div class=\"small\" style=\"padding-left: 15;\">";
				$i=0;
				foreach ($t0 as $t00)
					$out .= ($i++?", ":"").$t00;
				$out .= "</td></tr>";
			}
			$out .= "</table>";
		}
		return $out;
	}
?>
