<?php
/**********************************************************************************
* Subs-SplitForumAdminHooks.php - Hooks for the Split Forum mod
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
* Split Forum admin menu hook
**********************************************************************************/
function SplitForum_Admin_Menu(&$areas)
{
	global $txt, $scripturl, $subforum_tree, $modSettings, $context, $forumid, $forum_version;

	// Load some stuff:
	loadLanguage('ManageSplitForum');
	loadTemplate('Admin', 'splitforum_' . (substr($forum_version, 0, 7) == 'SMF 2.1' ? '21' : '20'));

	// Insert the Subforums area into the admin menu:
	$temp = array();
	foreach ($areas['config']['areas'] as $label => $area)
	{
		if ($label == 'featuresettings')
			$temp['subforums'] = array(
				'label' => ($forumid != 0 ? $txt['subforum_modify_header'] : $txt['subforums_list']),
				'file' => 'ManageSplitForum.php',
				'function' => 'ManageSplitForums',
				'icon' => 'server.gif',
				'subsections' => array(
					'main' => array($forumid != 0 ? $txt['subforum_modify_header'] : $txt['subforums_list'], 'admin_forum'),
					'newsub' => array($txt['subforums_list_add'], 'admin_forum'),
					'settings' => array($txt['settings'], 'admin_forum'),
				),
			);
		$temp[$label] = $area;
	}
	$areas['config']['areas'] = $temp;
	unset($temp);

	// Alter the SimplePortal area to reflect the subforum specified:
	$context['req_forumid'] = (int) (empty($_REQUEST['sub']) ? $forumid : $_REQUEST['sub']);
	if (isset($areas['portal']))
	{
		$portal = &$areas['portal']['areas']['subsections'];
		$portal['add']['url'] = $scripturl . '?action=admin;area=portalblocks;sa=add;sub=' . $context['req_forumid'];
		$portal['header']['url'] = $scripturl . '?action=admin;area=portalblocks;sa=header;sub=' . $context['req_forumid'];
		$portal['left']['url'] = $scripturl . '?action=admin;area=portalblocks;sa=left;sub=' . $context['req_forumid'];
		$portal['top']['url'] = $scripturl . '?action=admin;area=portalblocks;sa=top;sub=' . $context['req_forumid'];
		$portal['bottom']['url'] = $scripturl . '?action=admin;area=portalblocks;sa=bottom;sub=' . $context['req_forumid'];
		$portal['right']['url'] = $scripturl . '?action=admin;area=portalblocks;sa=right;sub=' . $context['req_forumid'];
		$portal['footer']['url'] = $scripturl . '?action=admin;area=portalblocks;sa=footer;sub=' . $context['req_forumid'];
	}

	// Subforum means no package manager and server settings access
	if ($forumid <> 0)
	{
		unset($areas['forum']['areas']['packages']);
		unset($areas['layout']['areas']['subforums']['subsections']['newsub']);
		unset($areas['layout']['areas']['subforums']['subsections']['settings']);
		unset($areas['config']['areas']['serversettings']);
	}
}

/**********************************************************************************
* Split Forum permissions hook
**********************************************************************************/
function SplitForum_Permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $txt, $subforum_tree, $forumid, $modSettings;
	
	// Add the subforum names to the permissions list:
	if (!empty($forumid))
		$subforum_tree = array($forumid => $subforum_tree[$forumid]);
	foreach ($subforum_tree as $id => $subforum)
	{
		$permissionList['membergroup']['deny_subforum' . $id ] = array(false, 'access_subforums', 'access_subforums');
		if (empty($modSettings['subforum_settings_permission_access']))
			$hiddenPermissions[] = 'deny_subforum' . $id;
		$txt['permissionname_deny_subforum' . $id] = $txt['subforum_deny'] . ' "' . $subforum['boardname'] . '"';
	}
}

?>