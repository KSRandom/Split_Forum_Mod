<?php
/**********************************************************************************
* Subs-SplitForumHooks.php - Hooks for the Split Forum mod
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/
if (!defined('SMF')) 
	die('Hacking attempt...');

/**********************************************************************************
* Split Forum system initialization functions:
**********************************************************************************/
function SplitForum_PreLoad()
{
	global $boardurl, $cookiename, $boarddir, $forumid, $mbname, $forumdir;
	global $subtheme, $language, $favicon, $subforum_tree, $modSettings;

	// Define primary subforum entry if it is not already defined:
	if (!is_array($subforum_tree))
	{
		$subforum_tree = array(
			0 => array(		// Primary subforum
				'forumid' => $forumid = 0,
				'boardurl' => $boardurl,
				'boardname' => $mbname,
				'language' => $language,
				'forumdir' => $boarddir,
				'favicon' => '',
				'primary_membergroup' => 0,
				'subtheme' => 0,
			),
		);
	}
	else
	{
		// Set the primary forum to whatever the url and directory is set in Settings.php:
		$subforum_tree[0]['boardurl'] = $boardurl;
		$subforum_tree[0]['forumdir'] = $forumdir;

		// Determine which subforum we are in and load settings for it ONLY IF not already done:
		if (empty($forumid))
		{
			$forumid = 0;
			$host = strtolower($_SERVER['SERVER_NAME']);
			$uri = str_replace('/index.php', '', substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')));
			foreach ($subforum_tree as $id => $row)
			{
				$url = parse_url($row['boardurl']);
				$test1 = strtolower($url['host']);
				$test2 = strtolower(str_replace('www.', '', $test1));
				$check = ($test1 == $host || $test2 == $host);
				if (isset($url['path']))
					$check = $check && (strpos($uri, substr($url['path'], 1)) > 0);
				if (!$check && !empty($row['sp_standalone']))
				{
					$url = parse_url($row['sp_standalone']);
					$test1 = strtolower($url['host']);
					$test2 = strtolower(str_replace('www.', '', $test1));
					$check = ($test1 == $host || $test2 == $host);
					if (isset($url['path']))
						$check = ($check && $url['path'] == $uri);
				}			
				if ($check)
					$forumid = (int) $row['forumid'];
			}
		}

		// Overwrite certain board settings with the Subforum setting if available:
		$row = &$subforum_tree[$forumid];
		$boardurl = !empty($row['boardurl']) ? $row['boardurl'] : $boardurl;
		$forumdir = !empty($row['forumdir']) ? $row['forumdir'] : $forumdir;
		$subtheme = !empty($row['subtheme']) ? $row['subtheme'] : $subtheme;
		$mbname = !empty($row['boardname']) ? $row['boardname'] : $mbname;
		$language = !empty($row['language']) ? $row['language'] : $language;
		$favicon = !empty($row['favicon']) ? $row['favicon'] : $favicon;
		$modSettings['primary_membergroup'] = !empty($row['primary_membergroup']) ? $row['primary_membergroup'] : 0;
		$modSettings['news' . $forumid] = !empty($modSettings['news' . $forumid]) ? $modSettings['news' . $forumid] : $modSettings['news'];

		// Overwrite settings related to Pretty URLs:
		if (isset($modSettings['pretty_root_url']))
			$modSettings['pretty_root_url'] = !empty($row['boardurl']) ? $row['boardurl'] : $modSettings['pretty_root_url'];
		if (isset($row['enable_pretty']) && isset($modSettings['pretty_enable_filters']))
			$modSettings['pretty_enable_filters'] = !empty($row['enable_pretty']) ? $row['enable_pretty'] : $modSettings['pretty_enable_filters'];

		// Overwrite settings related to Simple Portal:
		if (isset($row['sp_portal']) && isset($modSettings['sp_portal_mode']))
			$modSettings['sp_portal_mode'] = !empty($row['sp_portal']) ? $row['sp_portal'] : $modSettings['sp_portal_mode'];
		if (isset($row['sp_standalone']) && isset($modSettings['sp_standalone_url']))
			$modSettings['sp_standalone_url'] = !empty($row['sp_standalone']) ? $row['sp_standalone'] : $modSettings['sp_standalone_url'];
	}

	// Each subforum is effectively an alias, so let's add all of the them to $modSettings:
	$urls = array();
	if (!empty($modSettings['forum_alias_urls']))
		$urls = explode(',', $modSettings['forum_alias_urls']);
	foreach ($subforum_tree as $subforum)
		$urls[] = $subforum['boardurl'];
	$modSettings['forum_alias_urls'] = implode(',', array_unique($urls));
}

function SplitForum_EzPortal_Init()
{
	global $ezpSettings, $subforum_tree, $forumid;
	
	// Overwrite settings related to EzPortal:
	$row = &$subforum_tree[$forumid];
	if (isset($row['ez_portal_enable']))
		$ezpSettings['ezp_portal_enable'] = $row['ez_portal_enable'];
	if (isset($row['ez_homepage_title']))
		$ezpSettings['ezp_portal_homepage_title'] = $row['ez_homepage_title'];
	if (isset($row['ez_shoutbox']))
		$ezpSettings['ezp_shoutbox_enable'] = $row['ez_shoutbox'];
}

/**********************************************************************************
* Actions hook
**********************************************************************************/
function SplitForum_Actions(&$actions)
{
	$actions['unreadglobal'] = array('Recent.php', 'UnreadTopics');
}

/**********************************************************************************
* Load Theme hook (workaround for restrictions before any action taken)
**********************************************************************************/
function SplitForum_DenyAccess()
{
	global $forumid, $user_info, $modSettings;

	// Make sure we can log out.  Otherwise, we stuck in an endless loop:
	if (isset($_GET['action']) && $_GET['action'] == 'logout')
		return;
	
	// Make sure we are NOT ADMIN and have not been denied access to this subforum....
	// NOTE: If we skip the admin check, all admins will be denied access to all subforums!!!
	if (empty($user_info['is_admin']) && !empty($modSettings['subforum_settings_permission_access']))
	{
		if (allowedTo('deny_subforum' . $forumid))
		{
			loadLanguage('ManageSplitForum');
			fatal_lang_error('subforum_access_denied', 'user', !empty($modSettings['subforum_settings_permission_access_log']));
		}
	}
}

/**********************************************************************************
* Top menu hook
**********************************************************************************/
function SplitForum_Menu_Buttons(&$areas)
{
	global $txt, $scripturl, $subforum_tree, $modSettings, $forumid, $user_info;;

	// Return if only one subforum is defined OR top menu option is turned completely off:
	if (count($subforum_tree) <= 1 || empty($modSettings['subforum_settings_topmenu']))
		return;

	// Return if top menu for admin only option is set AND user isn't an admin:
	if (!empty($modSettings['subforum_settings_topmenu_admin_only']) && empty($user_info['is_admin']))
		return;

	// Add the Subforums list to the top menu:
	loadLanguage('ManageSplitForum');
	if (empty($modSettings['subforum_settings_topmenu_under_home']))
	{
		$new = array();
		foreach ($areas as $needle => $section)
		{
			$new[$needle] = $section;
			if ($needle == 'home')
			{
				$new['subforums'] = array(
					'title' => (empty($modSettings['subforum_sister_sites_title']) ? $txt['subforum_sister_sites'] : $modSettings['subforum_sister_sites_title']),
					'href' => $scripturl,
					'show' => true,
					'sub_buttons' => array(
					),
				);
			}
		}
		$areas = $new;
		$new = &$areas['subforums'];
	}
	else
		$new = &$areas['home'];

	// Populate the specified area with the Subforum name(s):
	foreach ($subforum_tree as $id => $subforum)
	{
		if (empty($modSettings['subforum_settings_topmenu_include_this']) && $subforum['forumid'] == $forumid)
			continue;
		$new['sub_buttons'][$id] = array(
			'title' => $subforum['boardname'],
			'href' => $subforum['boardurl'],
			'show' => true,
		);
	}
}

/**********************************************************************************
* Subforum Support functions:
**********************************************************************************/
function SplitForum_PreUpdate(&$config_vars)
{
	global $subforum_tree, $forumid;

	// Put available data in the Subforum Tree variable:
	if (isset($config_vars['mbname']))
		$subforum_tree[$forumid]['boardname'] = $config_vars['mbname'];
	if (isset($config_vars['boardurl']))
		$subforum_tree[$forumid]['boardurl'] = $config_vars['boardurl'];
	if (isset($config_vars['cookiename']))
		$subforum_tree[$forumid]['cookiename'] = $config_vars['cookiename'];
	if (isset($config_vars['favicon']))
		$subforum_tree[$forumid]['favicon'] = $config_vars['favicon'];
	unset($config_vars['favicon']);
	
	// Clear some variables if we are in a subforum:
	if ((isset($config_vars['mbname']) || isset($config_vars['boardurl']) || isset($config_vars['cookiename'])) && $forumid != 0)
	{
		unset($config_vars['mbname']);
		unset($config_vars['boardurl']);
		unset($config_vars['cookiename']);
	}
}

?>