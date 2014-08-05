<?
	include_once "init.php";
	
	$mode	= request_var('mode', '');
	
	if (in_array($mode, array('login', 'logout', 'confirm', 'sendpassword', 'activate')))
	{
		define('IN_LOGIN', true);
	}
	
	// Basic "global" modes. Handle only 2 of them
	switch ($mode)
	{
		case 'login':
			
			$auth->login("zaknafeyn", "17drizzt09");
			echo "aaa";
			
			if ($user->data['user_id'] != ANONYMOUS)
			{
				echo "Not anon";
			}
			else
			{
				echo "anon";
			}
			meta_refresh(5,request_var('redirect', "{$phpbb_root_path}index.$phpEx"));
			return;
			
			$password	= request_var('password', '', true);
			$username	= request_var('username', '', true);
			$autologin	= (!empty($_POST['autologin'])) ? true : false;
			$viewonline = (!empty($_POST['viewonline'])) ? 0 : 1;
			$admin 		= ($admin) ? 1 : 0;
			$viewonline = ($admin) ? $user->data['session_viewonline'] : $viewonline;
			
			// If authentication is successful we redirect user to previous page
			$result = $auth->login($username, $password, $autologin, $viewonline, $admin);
			
			meta_refresh(3,request_var('redirect', "{$phpbb_root_path}index.$phpEx"));

			//echo request_var('redirect', append_sid("{$phpbb_root_path}index.$phpEx"));
			//login_box(request_var('redirect', append_sid("{$phpbb_root_path}index.$phpEx")));
		break;

		case 'logout':
			if ($user->data['user_id'] != ANONYMOUS && isset($_GET['sid']) && !is_array($_GET['sid']) && $_GET['sid'] === $user->session_id)
			{
				$user->session_kill();
				$user->session_begin();
				$message = $user->lang['LOGOUT_REDIRECT'];
			}
			else
			{
				$message = ($user->data['user_id'] == ANONYMOUS) ? $user->lang['LOGOUT_REDIRECT'] : $user->lang['LOGOUT_FAILED'];
			}
			meta_refresh(3, append_sid("{$phpbb_root_path}index.$phpEx"));

			$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a> ');
			trigger_error($message);

		break;

		
	}
?>