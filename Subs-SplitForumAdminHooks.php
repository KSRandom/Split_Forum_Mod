<?php
/**********************************************************************************
* Subs-SplitForumAdminHooks.php - Hooks for the Split Forum mod
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE,
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

/**********************************************************************************
* Split Forum admin menu hook
**********************************************************************************/
function SplitForum_Admin_Menu(&$areas)
{
	global $txt, $scripturl, $subforum_tree, $modSettings, $context, $forumid;

	// Load some stuff:
	loadLanguage('ManageSplitForums');
	loadTemplate('Admin', 'splitforum');

	// Insert the Subforums area into the admin menu:
	$temp = array();
	foreach ($areas['layout']['areas'] as $label => $area)
	{
		$temp[$label] = $area;
		if ($label == 'manageboards')
			$temp['subforums'] = array(
				'label' => ($forumid != 0 ? $txt['subforum_modify_header'] : $txt['subforums_list']),
				'file' => 'ManageSplitForums.php',
				'function' => 'ManageSplitForums',
				'icon' => 'server.gif',
				'subsections' => array(
					'main' => array($forumid != 0 ? $txt['subforum_modify_header'] : $txt['subforums_list'], 'admin_forum'),
					'newsub' => array($txt['subforums_list_add'], 'admin_forum'),
					'settings' => array($txt['settings'], 'admin_forum'),
				),
			);
	}
	$areas['layout']['areas'] = $temp;
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

?>