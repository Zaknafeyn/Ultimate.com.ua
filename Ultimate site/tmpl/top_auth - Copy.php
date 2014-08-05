    <div id="topnav">
      <span><a id="login_link"<?=$user->data['session_logged_in']?"":" href=\"/new_forum/login.php\""?> class="pseudo_link clickable"><?=(!$user->data['session_logged_in'])?"Вход":$user->data['username']?></a></span>

    <div id="signin_menu" class="common-form standard-form">
	<?
		if (!$user->data['session_logged_in']) {
	?>
		<form method="post" id="signin_form" action="/4room/login.php">
				<label for="username">Имя пользователя</label><br />
				<input type="text" id="username" name="username" value="" title="Имя пользователя форума"  tabindex="4"/>
			<br />
				<label for="password">Пароль</label><br />
				<input type="password" id="password" name="password" value="" title="Пароль. Сюда пароль." tabindex="5" />
			<p>
				<input type="submit" id="login" name="login" value="Войти" tabindex="7"/>
				&nbsp;&nbsp;<input type="checkbox" id="autologin" name="autologin" value="1" tabindex="6"/>
				<label for="autologin">Запомнить меня</label>
				<input type="hidden" name="redirect" value="..<?=$_SERVER['REQUEST_URI']?>" />
			</p>
			<p>
				<div class="small">
					<a href="/4room/profile.php?mode=register&agreed=true">Регистрация</a>
				</div>
			</p>
		</form>
	<?
		} else {
			$user_pic = GetUserAvatar($user->data['user_id']);
			$user->data['user_photo'] = $user_pic;
			preg_match('/src=\"([^\"]*)\"/',$user_pic,$user_pic_src);
			$user_name = NameSurname($user->data['user_id']);
			$rst0 = $db->sql_query("SELECT * FROM players AS p
				LEFT JOIN teams AS t ON p.id_team=t.id
				WHERE p.id=".$user->data['user_id']."
				");
			if ($db->sql_affectedrows($rst0)) {
				$line0 = $db->sql_fetchrow($rst0);
				$user_url = "/players/".($line0['p_char_id']?$line0['p_char_id']:$user->data['user_id'])."/";
				$user_edited = !$line0['edited'] ? "<span style=\"background-color: beige; font-weight: bold; padding: 10; margin: -10;\">Вы можете <a href=\"/players/".$user->data['user_id']."/edit\">заполнить информацию о себе</a></span><br />" : "";
				$user_profile = "<a href=\"$user_url\">".($line0['cap'] ? "Капитан":"Игрок")."</a>";
				$user_team = $line0['char_id']? " команды <a href=\"/teams/".$line0['char_id']."/\">".stripslashes($line0['team_name'])."</a>" : ($line0['Team']?" команды ".stripslashes($line0['Team']):"");
			}
	?>
		<script language="javascript">var user_img=new Image(); user_img.src='<?=$user_pic_src[1]?>';</script>
    	<table>
    		<tr valign="top">
    			<td style="padding-right: 15px;" width="30">
    				<h3><?=$user_pic?></h3>
    			</td>
    			<td>
    				<h3><?=$user_name?></h3>
    				<div class="small" style="line-height: 20px;">
    				<?=$user_profile.$user_team."<br />"?>
    				<?
						$l = $db->sql_fetchrow($db->sql_query("SELECT COUNT(id) FROM photo_marks WHERE user_id=".$user->data['user_id']));
						$foto = $l[0];
						$l = $db->sql_fetchrow($db->sql_query("SELECT COUNT(id) FROM photo_marks WHERE user_id=".$user->data['user_id']." AND approved=0"));
						$newfoto = $l[0];
						if ($foto)
							print "<a href=\"".$user_url."photo/\">".$foto." фото".($newfoto?" (<strong>+$newfoto</strong>)":"")."</a><br />";

						if ($rights) {
							print "<div class=\"sex0\"><a href=\"/admin/?u=".$user->data['username']."\">Админка</a></div>";
						}
						if ($rights['all_rights']) {
							print "<br /><a href=\"/update-blog.php\">Обновить главную страницу</a>";
						}
						print "<br /><a href=\"/".$forum_folder_name."/login.php?logout=true&sid=".$user->data['session_id']."&redirect=..".$_SERVER['REQUEST_URI']."\">Выход</a>";
    				?>
    				</div>
    			</td>
     		</tr>
    	</table>
	<?

		}
	?>
    </div>
  </div>
