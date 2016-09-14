<?php
/**********************************************************************************
* Subs-SplitForumHooks.php - Hooks for the Split Forum mod
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE,
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

/**********************************************************************************
* Actions hook
**********************************************************************************/
function SplitForum_Actions(&$actions)
{
	$actions['unreadglobal'] = array('Recent.php', 'UnreadTopics');
}

/**********************************************************************************
* Top menu hook
**********************************************************************************/
function SplitForum_Menu_Buttons(&$areas)
{
	global $txt, $scripturl, $subforum_tree, $modSettings, $context, $forumid;

	// Return if only one subforum is defined OR top menu option is turned completely off:
	if (count($subforum_tree) <= 1 || empty($modSettings['subforum_settings_topmenu']))
		return;

	// Return if top menu for admin only option is set AND user isn't an admin:
	if (!empty($modSettings['subforum_settings_topmenu_admin_only']) && !$context['allow_admin'])
		return;

	// Add the Subforums list to the top menu:
	loadLanguage('ManageSplitForums');
	$new = array();
	foreach ($areas as $needle => $section)
	{
		$new[$needle] = $section;
		if ($needle == 'home')
		{
			$new['subforums'] = array(
				'title' => (empty($modSettings['subforum_sister_sites']) ? $txt['subforum_sister_sites'] : $modSettings['subforum_sister_sites']),
				'href' => $scripturl,
				'show' => true,
				'sub_buttons' => array(
				),
			);
			foreach ($subforum_tree as $id => $subforum)
			{
				if ($subforum['forumid'] == $forumid)
					continue;
				$new['subforums']['sub_buttons'][$id] = array(
					'title' => $subforum['boardname'],
					'href' => $subforum['boardurl'],
					'show' => true,
				);
			}
		}
	}
	$areas = $new;
}

/**********************************************************************************
* Pre-Load integration hook
**********************************************************************************/
function SplitForum_PreLoad()
{
	global $subforum_tree, $modSettings;
	
	// Each subforum is effectively an alias, so let's add all of the them to $modSettings:
	$urls = array();
	if (!empty($modSettings['forum_alias_urls']))
		$urls = explode(',', $modSettings['forum_alias_urls']);
	foreach ($subforum_tree as $subforum)
		$urls[] = $subforum['boardurl'];
	$modSettings['forum_alias_urls'] = implode(',', array_unique($urls));
}

?>