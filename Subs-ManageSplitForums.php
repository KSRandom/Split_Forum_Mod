<?php
/**********************************************************************************
* Subs-ManageSubForums.php - PHP implementation of the Split Forum Mod
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE .
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

function get_installed_themes()
{
	global $smcFunc, $txt, $modSettings;
	
	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}themes
		WHERE variable = {string:name}
			AND id_member = 0
			AND id_theme IN ({raw:known})',
		array(
			'name' => 'name', 
			'known' => $modSettings['knownThemes']
		)
	);
	$themes = array($txt['mboards_theme_default']);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$themes[$row['id_theme']] = $row['value'];
	$smcFunc['db_free_result']($request);
	return $themes;
}

function get_membergroups()
{
	global $smcFunc, $txt;
	
	$primary = array(
		0 => $txt['no_primary_membergroup']
	);
	$request = $smcFunc['db_query']('', '
		SELECT group_name, id_group, min_posts
		FROM {db_prefix}membergroups
		WHERE id_group > {int:moderator_group} AND min_posts = -1
		ORDER BY id_group',
		array(
			'moderator_group' => 3,
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		if ($row['min_posts'] != -1) 
			$primary[$row['id_group']] = $row['group_name'];
	$smcFunc['db_free_result']($request);
	return $primary;
}
	
function get_subforum_count()
{
	global $subforum_tree, $forumid;
	return ($forumid == 0 ? count($subforum_tree) : 1);
}

function add_subforum(&$row)
{
	global $subforum_tree, $forumid, $sourcedir;
	
	isAllowedTo('admin_forum');
	
	// Define the contents of the array element:
	$subforum_tree[(int) isset($row['forumid']) ? $row['forumid'] : $forumid] = array(
		'forumid' => (int) (isset($row['forumid']) ? $row['forumid'] : $forumid),
		'cookiename' => (isset($row['cookiename']) ? $row['cookiename'] : ''),
		'boardurl' => (isset($row['boardurl']) ? $row['boardurl'] : ''),
		'boardname' => (isset($row['boardname']) ? $row['boardname'] : ''),
		'subtheme' => (int) (isset($row['subtheme']) ? $row['subtheme'] : 0),
		'language' => (isset($row['language']) ? $row['language'] : ''),
		'forumdir' => (isset($row['forumdir']) ? $row['forumdir'] : ''),
		'favicon' => (isset($row['favicon']) ? $row['favicon'] : ''),
		'primary_membergroup' => (isset($row['primary_membergroup']) ? (int) $row['primary_membergroup'] : 0),
	);
	asort($subforum_tree);
	
	// Set the variable "subforum_tree" in the forum's Settings.php:
	require_once($sourcedir.'/Subs-Admin.php');
	updateSettingsFile(array('subforum_tree' => str_replace("\n", "", var_export($subforum_tree, true))));
}

function delete_subforum($sub, $write_settings = true)
{
	global $subforum_tree, $sourcedir;

	isAllowedTo('admin_forum');
	if ($sub == 0) return;
	require_once($sourcedir.'/Subs-Admin.php');
	unset($subforum_tree[(int) $sub]);
	if ($write_settings)
		updateSettingsFile(array('subforum_tree' => str_replace("\n", "", var_export($subforum_tree, true))));
}

function move_attached_boards($from, $dest)
{
	global $smcFunc;
	
	isAllowedTo('admin_forum');
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}categories
		SET forumid = {int:newid}
		WHERE forumid = {int:forumid}',
		array(
			'newid' => (int) $dest,
			'forumid' => (int) $from,
		)
	);
}

?>