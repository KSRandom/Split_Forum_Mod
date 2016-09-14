<?php
/**********************************************************************************
* ManageSplitForums.php - PHP implementation of the Split Forum Mod
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE .
*********************************************************************************
* This work is licensed under a Creative Commons Attribution 3.0 Unported License
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

function ManageSplitForums()
{
	global $context, $txt, $sourcedir, $forumid;

	// Everything's gonna need this:
	isAllowedTo('admin_forum');
	require_once($sourcedir . '/ManageServer.php');
	require_once($sourcedir . '/Subs-Boards.php');
	require_once($sourcedir . '/Subs-ManageSplitForums.php');
	loadLanguage('Profile');
	loadLanguage('ManageBoards');
	loadTemplate('ManageSplitForums');

	// Create the tabs for the template .
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['subforums_list_title'],
		'description' => $txt['subforums_list_desc'],
		'tabs' => array(
			'main' => array(
			),
			'newsub' => array(
			),
			'settings' => array(
			),
		),
	);

	// Format: 'sub-action' => 'function'
	$subActions = array(
		'edit'   => 'EditSubForum',
		'edit2'  => 'SaveSubForum',
		'list'   => ($forumid == 0 ? 'ListSubForums' : 'EditSubForum'),
		'newsub' => ($forumid == 0 ? 'EditSubForum' : 'EditSubForum'),
		'delete' => ($forumid == 0 ? 'DeleteSubForum' : 'EditSubForum'),
		'settings' => ($forumid == 0 ? 'SubForumSettings' : 'EditSubForum'),
	);

	// Make sure that the subforum number is a valid one to edit:
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : ($forumid == 0 ? 'list' : 'edit');
	$sub = ($forumid == 0 && isset($_REQUEST['sub']) ? (int) $_REQUEST['sub'] : $forumid);
	$subActions[$_REQUEST['sa']]($sub);
}

Function ListSubForums($sub)
{
	global $context, $txt, $scripturl;

	isAllowedTo('admin_forum');
	$context['sub_template'] = 'subforums_list';
	$context['post_url'] = $scripturl . '?action=admin;area=subforums';
	$context['page_title'] = $txt['subforums_list_title'];
}

Function EditSubForum($sub)
{
	global $context, $sourcedir, $txt, $scripturl, $modSettings, $forumid;
	global $subforum_tree, $language, $smcFunc, $db_prefix;

	// Make sure that the subforum number is a valid one to edit:
	isAllowedTo('admin_forum');
	if ($forumid != 0 && ($_REQUEST['sa'] == 'newsub' || $forumid != $sub))
		redirectexit('action=admin;area=subforums');
	if ($_REQUEST['sa'] != 'newsub' && !isset($subforum_tree[$sub]))
		redirectexit('action=admin;area=subforums');

	// Is Simple Portal installed?  We can manipulate those tables if necessary:
	$request = $smcFunc['db_query']('', 'show tables like "%sp_blocks"', array());
	while ($row = $smcFunc['db_fetch_row']($request))
	{
		if (str_replace('sp_blocks', '', $row[0]) <> $db_prefix)
			continue;
		$context['sp_blocks_enabled'] = file_exists($sourcedir . '/PortalAdminBlocks.php');
	}
	$smcFunc['db_free_result']($request);

	// Get the names of all installed themes available on this install:
	$installed_themes = get_installed_themes();

	// Build an array for the membergroup list:
	$primary = get_membergroups();

	// Get a list of all available languages on this install:
	$languages = array();
	if (empty($context['languages']))
		getLanguages();
	foreach ($context['languages'] as $name => $lang)
		$languages[$name] = $lang['name'];

	// If we are creating a new subforum, populate the fields with some defaults:
	if ($_REQUEST['sa'] == 'newsub')
	{
		// Figure out what the largest forum ID in the database is:
		$sub = get_subforum_max() + 1;
		$addition = (!strpos($modSettings['subforum_server_root'], "\\") ? '/forum' : '\\forum') . $sub;

		// Populate a new entry for the template:
		$subforum_tree[$sub]['forumid'] = $sub;
		$subforum_tree[$sub]['boardname'] = $txt['subforums_list_prefix'] . ' # ' . $sub;
		$subforum_tree[$sub]['boardurl'] = $modSettings['subforum_server_url'] . '/forum' . $sub;
		$subforum_tree[$sub]['forumdir'] = $modSettings['subforum_server_root'] . $addition;
		$subforum_tree[$sub]['subtheme'] = 0;
		$subforum_tree[$sub]['language'] = $language;
	}

	// Here and the board settings...
	$config_vars = array(
		array('text', 'subforum_modify_boardname', 'size' => 40),
		array('text', 'subforum_modify_boardurl', 'size' => 40),
		array('text', 'subforum_modify_favicon', 'size' => 40),
		array('select', 'subforum_modify_subtheme', $installed_themes),
		array('select', 'subforum_modify_language', $languages),
		//'',
		array('title', 'subforum_modify_dontchange'),
		array('text', 'subforum_modify_forumid', 'size' => 40,
			'javascript' => (($forumid != 0 || ($_REQUEST['sa'] != 'newsub' && $sub == 0)) ? ' disabled="disabled"' : '')),
		array('text', 'subforum_modify_forumdir', 'size' => 40,
			'javascript' => (($forumid != 0 || ($_REQUEST['sa'] != 'newsub' && $sub == 0)) ? ' disabled="disabled"' : '')),
		array('select', 'subforum_modify_primary', $primary),
	);
	if ($sub != 0 || $_REQUEST['sa'] == 'newsub')
		$config_vars[] = array('check', 'subforum_modify_news');
	foreach ($subforum_tree[$sub] as $var => $val)
		$modSettings['subforum_modify_' . $var] = $val;
	$modSettings['subforum_modify_news'] = ($_REQUEST['sa'] == 'newsub');

	// Populate everything needed for Simple Portal support:
	if (isset($context['sp_blocks_enabled']) && $context['sp_blocks_enabled'])
	{
		$options = array(-1 => $txt['subforum_modify_sp_blocks_nothing']);
		foreach ($subforum_tree as $subforum)
		{
			if ($subforum['forumid'] <> $sub)
				$options[$subforum['forumid']] = $subforum['boardname'];
		}
		$options[-2] = $txt['subforum_modify_sp_blocks_default'];
		$options[-3] = $txt['subforum_modify_sp_blocks_remove'];
		$config_vars = array_merge($config_vars, array(
			array('title', 'subforum_modify_sp_title'),
			array('select', 'subforum_modify_sp_portal', explode('|', $txt['sp_portal_mode_options']),
				'javascript' => 'onchange="SetPortalType(this.options[this.selectedIndex].value); return false;"'),
			array('text', 'subforum_modify_sp_standalone',
				'javascript' => ($subforum_tree[$sub]['sp_portal'] != 3 ? ' disabled="disabled"' : ''),
			),
			array('select', 'subforum_modify_sp_blocks', $options),
		));
		if (!isset($modSettings['subforum_modify_sp_portal']))
			$modSettings['subforum_modify_sp_portal'] = $modSettings['sp_portal_mode'];
		if (!isset($modSettings['subforum_modify_sp_standalone']))
			$modSettings['subforum_modify_sp_standalone'] = $modSettings['sp_standalone_url'];
		$modSettings['subforum_modify_sp_blocks'] = ($_REQUEST['sa'] == 'newsub' ? -2 : -1);
		$txt['subforum_modify_sp_portal'] = $txt['sp_portal_mode'];
		$txt['subforum_modify_sp_standalone'] = $txt['sp_standalone_url'];
		$context['html_headers'] .= '
	<script type="text/javascript">
		function SetPortalType($ptype)
		{
			document.getElementById("subforum_modify_sp_standalone").disabled = ($ptype <> 3);
		}
	</script>';
	}

	// Needed for the settings template
	require_once($sourcedir . '/ManageServer.php');
	$context['sub_template'] = 'show_settings';
	$context['page_title'] = $txt['subforum_modify_header'];
	$context['post_url'] = $scripturl . '?action=admin;area=subforums;sa=edit2;' . ($_REQUEST['sa'] == 'newsub' ? 'newsub;' : '') . 'sub=' . $sub;
	$context['permissions_excluded'] = array(-1);
	$context['settings_title'] = $txt['subforum_modify_header'];

	// Prepare the settings...
	prepareDBSettingContext($config_vars);
}

function SaveSubForum($sub)
{
	global $context, $sourcedir, $txt, $scripturl, $modSettings, $settings, $forumid;
	global $modSettings, $subforum_tree, $smcFunc, $boarddir;

	// Load the variables from the form:
	checkSession();
	isAllowedTo('admin_forum');

	// Filter all the information passed to this function, putting all the information into the array:
	$arr['boardurl'] = str_replace('http://http://', 'http://', 'http://' . (isset($_POST['subforum_modify_boardurl']) ?  $_POST['subforum_modify_boardurl']  : '') );
	$arr['boardname'] = (isset($_POST['subforum_modify_boardname']) ?  $_POST['subforum_modify_boardname']  : '');
	$arr['subtheme'] = (int) (isset($_POST['subforum_modify_subtheme']) ? $_POST['subforum_modify_subtheme'] : 0);
	$arr['language'] = (isset($_POST['subforum_modify_language']) ?  $_POST['subforum_modify_language']  : '');
	$arr['favicon'] = str_replace('http://http://', 'http://', 'http://' . (isset($_POST['subforum_modify_favicon']) ?  $_POST['subforum_modify_favicon']  : '') );
	$arr['primary_membergroup'] = (int) (isset($_POST['subforum_modify_primary']) ? $_POST['subforum_modify_primary'] : '');
	$arr['forumid'] = (int) (isset($_POST['subforum_modify_forumid']) ? $_POST['subforum_modify_forumid'] : $forumid);
	$arr['forumdir'] = ($sub <> 0 ? str_replace('http://', '', str_replace('//', '/', (isset($_POST['subforum_modify_forumdir']) ?  $_POST['subforum_modify_forumdir'] : ''))) : $boarddir);
	$arr['sp_portal'] = (isset($_POST['subforum_modify_sp_portal']) ? (int) $_POST['subforum_modify_sp_portal'] : 0);
	$arr['sp_standalone'] = (isset($_POST['subforum_modify_sp_standalone']) ? $_POST['subforum_modify_sp_standalone'] : '');

	// Correct variables as necessary, throwing errors only when necessary:
	if (substr($arr['boardurl'], strlen($arr['boardurl']) - 1, 1) == '/')
		$arr['boardurl'] = substr($arr['boardurl'], 0, strlen($arr['boardurl']) - 1);
	if ($arr['boardurl'] == 'http://')
		fatal_lang_error('subforum_error_no_url', false);
	if ($arr['favicon'] == 'http://')
		$arr['favicon'] = '';
	if (substr($arr['favicon'], strlen($arr['favicon']) - 1, 1) == '/')
		$arr['favicon'] = substr($arr['favicon'], 0, strlen($arr['favicon']) - 1);
	if (substr($arr['forumdir'], strlen($arr['forumdir']) - 1, 1) == '/')
		$arr['forumdir'] = substr($arr['forumdir'], 0, strlen($arr['forumdir']) - 1);

	// Throw an error if the ID is already taken and 1) user is creating a new subforum OR 2) changing the forum ID:
	if ((isset($_GET['newsub']) && isset($subforum_tree[$arr['forumid']])) ||
		(!isset($_GET['newsub']) && $sub != $arr['forumid'] && isset($subforum_tree[$arr['forumid']])))
		fatal_lang_error('subforum_error_dup_id', false);

	// Throw an error if user is editing a subforum and original ID doesn't exist yet:
	if (isset($_GET['newsub']) && isset($subforum_tree[$sub]))
		fatal_lang_error('subforum_error_issub', false);

	// Make sure another subforum with this path doesn't already exist:
	$host = parse_url($arr['boardurl']);
	$uri = str_replace('/index.php', '', substr($host['path'], 0, strrpos($host['path'], '/')));
	$host = $host['url'];
	foreach ($subforum_tree as $row)
	{
		if ($row['forumid'] == $sub)
			continue;
		$url = parse_url($row['boardurl']);
		$test1 = strtolower($url['host']);
		$test2 = strtolower(str_replace('www.', '', $test1));
		$check = (($test1 == $host || $test2 == $host) && $url['path'] == $uri);
		if (!empty($row['sp_standalone']))
		{
			$url = parse_url($row['sp_standalone']);
			$test1 = strtolower($url['host']);
			$test2 = strtolower(str_replace('www.', '', $test1));
			$check = $check || (($test1 == $host || $test2 == $host) && $url['path'] == $uri);
		}
		if ($check || $row['boardurl'] == $arr['boardurl'])
			fatal_lang_error('subforum_error_dup_path', false);
	}

	// Insert the information into the database table:
	delete_subforum($sub, false);
	add_subforum($arr);

	// Populate the Simple Portal blocks for this subforum:
	if (isset($_POST['subforum_modify_sp_blocks']) && $_POST['subforum_modify_sp_blocks'] == -3)
		delete_sp_blocks($arr['forumid']);
	elseif (isset($_POST['subforum_modify_sp_blocks']) && $_POST['subforum_modify_sp_blocks'] == -2)
	{
		delete_sp_blocks($arr['forumid']);
		use_default_sp_blocks($arr['forumid']);
	}
	elseif (isset($_POST['subforum_modify_sp_blocks']) && $_POST['subforum_modify_sp_blocks'] > -1)
	{
		delete_sp_blocks($arr['forumid']);
		copy_sp_blocks($_POST['subforum_modify_sp_blocks'], $arr['forumid']);
	}

	// Do this for all subforums, but NOT the primary forum!
	if ($arr['forumid'] != 0)
	{
		@mkdir($arr['forumdir']);
		@chmod($arr['forumdir'], 0755);
		if (is_dir($arr['forumdir']))
		{
			// Create the new forum folder and write "index.php" for the subforum:
			if ($handle = fopen($arr['forumdir'] . '/index.php', 'w'))
			{
				fwrite($handle, "<" . "?php" . "\n" . "require_once('" . $boarddir . "/index.php');" . "\n" . "?" . ">");
				fclose($handle);
				@chmod($arr['forumdir'] . '/index.php', 0755);
			}

			// Write out the new ".htaccess" file for the subforum:
			$path = relativePath($arr['forumdir'], $boarddir);
			if (substr($path, -1) == '\\')
				$path = substr($path, 0, strlen($path) - 1) . '/';
			if ($handle = fopen($arr['forumdir'] . '/.htaccess', 'w'))
			{
				fwrite($handle, "Options +FollowSymlinks\nRewriteEngine on\nRewriteRule (.*)/(.*) " . $path . "$1/$2");
				fclose($handle);
				@chmod($arr['forumdir'] . '/.htaccess', 0755);
			}
		}
		
		// If requested, copy the news settings to the subforum:
		if (!empty($_POST['subforum_modify_news']))
			updateSettings(array('news' . $sub => $modSettings['news']));
	}

	// Let's call hook function(s) to handle subdomain addition:
	$arr['action'] = 'add';
	call_integration_hook('integrate_subforum_subdomain', array(&$arr));

	// Create the registration agreement files for the new forum if they don't exist:
	if ($arr['forumid'] != 0 && !file_exists($boarddir . '/agreement.forum' . $arr['forumid'] . '.*'))
	{
		foreach(glob($boarddir . '/agreement.*') as $file)
		{
			if (strpos(str_replace($boarddir, '', $file), '.forum') === false)
				copy($file, str_replace('agreement', 'agreement.forum' . $arr['forumid'], $file));
		}
	}

	// Return to the Subforum Settings page:
	redirectexit('action=admin;area=subforums');
}

function DeleteSubForum($sub)
{
	global $context, $sourcedir, $txt, $scripturl, $modSettings, $settings, $forumid, $smcFunc;
	global $cat_tree, $boards, $boardList, $subforum_tree;

	// Assuming session checks out, begin loading up the variables:
	isAllowedTo('admin_forum');
	require_once($sourcedir . '/Subs-Categories.php');
	getBoardTree($sub);

	// If the category tree for that subforum is empty, just remove the subforum:
	if (empty($cat_tree))
	{
		delete_subforum($sub);
		redirectexit('action=admin;area=subforums');
	}

	// Let's call hook function(s) to handle subdomain/domain removal:
	$arr['action'] = 'delete';
	call_integration_hook('integrate_subforum_subdomain', array(&$arr));

	// Let user decide what to do with the contents:
	$context['sub_template'] = 'subforums_delete';
	$context['page_title'] = $txt['subforum_delete_title'];
	$context['post_url'] = $scripturl . '?action=admin;area=subforums;sa=delete;sub=' . $sub;
	$context['permissions_excluded'] = array(-1);
	$context['subforumID'] = $sub;

	// Create a category/board tree within the context variable:
	$context['categories'] = array();
	foreach ($cat_tree as $catid => $tree)
	{
		$context['categories'][$catid] = array(
			'name' => &$tree['node']['name'],
			'id' => &$tree['node']['id'],
			'boards' => array()
		);
		$move_cat = !empty($context['move_board']) && $boards[$context['move_board']]['category'] == $catid;
		foreach ($boardList[$catid] as $boardid)
		{
			$context['categories'][$catid]['boards'][$boardid] = array(
				'id' => &$boards[$boardid]['id'],
				'name' => &$boards[$boardid]['name'],
				'description' => &$boards[$boardid]['description'],
				'admin1' => &$boards[$boardid]['admin1'],
				'child_level' => &$boards[$boardid]['level'],
				'move' => $move_cat && ($boardid == $context['move_board'] || isChildOf($boardid, $context['move_board'])),
				'permission_profile' => &$boards[$boardid]['profile'],
			);
		}
	}

	// If we are getting instructions on what to do, then do what we are told:
	if (isset($_POST['delete']))
	{
		checkSession();
		if (isset($_POST['delete_action']) && $_POST['delete_action'] == 1)
		{
			foreach ($context['categories'] as $catid => $category)
				move_attached_categories($sub, $_POST['forum_to']);
		} else {
			foreach ($context['categories'] as $catid => $category)
				deleteCategories(array($catid));
		}
		delete_subforum($sub);
		redirectexit('action=admin;area=subforums');
	}
	if (isset($_POST['cancel']))
		redirectexit('action=admin;area=subforums');
}

function SubForumSettings($return_config = false)
{
	global $forumid, $context, $txt, $modSettings, $scripturl, $smcFunc, $sourcedir, $forumid;

	// Here and the board settings...
	isAllowedTo('admin_forum');
	$config_vars = array(
		array('text', 'subforum_server_url', 'size' => 40),
		array('text', 'subforum_server_root', 'size' => 40),
		'',
		array('check', 'subforum_redirect_wrong'),
		'',
		array('text', 'subforum_sister_sites_title', 'size' => 40),
		array('check', 'subforum_settings_topmenu'),
		array('check', 'subforum_settings_topmenu_admin_only'),
	);
	if ($return_config)
		return $config_vars;

	// Needed for the settings template
	require_once($sourcedir . '/ManageServer.php');
	if (empty($modSettings['subforum_sister_sites_title']))
		$modSettings['subforum_sister_sites_title'] = $txt['subforum_sister_sites'];
	$context['sub_template'] = 'show_settings';
	$context['page_title'] = $txt['subforums_list_title'] . ' - ' . $txt['settings'];
	$context['post_url'] = $scripturl . '?action=admin;area=subforums;sa=settings;save';
	$context['permissions_excluded'] = array(-1);
	$context['settings_title'] = $txt['subforums_list_title'];

	// Doing a save?
	if (isset($_GET['save']))
	{
		checkSession();
		saveDBSettings($config_vars);
		redirectexit('action=admin;area=subforums;sa=settings');
	}

	// Prepare the settings...
	prepareDBSettingContext($config_vars);
}

?>