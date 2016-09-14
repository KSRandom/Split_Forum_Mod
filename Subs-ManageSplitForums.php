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
	global $smcFunc;
	
	$request = $smcFunc['db_query']('', '
		SELECT MAX(forumid) as max_id
		FROM {db_prefix}subforums'
	);
	$row = $smcFunc['db_fetch_assoc']($request);
	$smcFunc['db_free_result']($request);
	return $row['max_id'];
}

function add_subforum($sub, &$row)
{
	global $smcFunc;
	
	isAllowedTo('admin_forum');

	// Insert the information into the database table:
	$smcFunc['db_insert']('replace',
		'{db_prefix}subforums',
		array(
			'forumid' => 'int', 'cookiename' => 'text', 'boardurl' => 'text', 'boardname' => 'text', 
			'subtheme' => 'int', 'language' => 'text', 'forumdir' => 'text', 'favicon' => 'text',
			'primary_membergroup' => 'int',
		),
		array(
			(int) $sub, $row['cookiename'], $row['boardurl'], $row['boardname'], (int) $row['subtheme'], 
			$row['language'], $row['forumdir'], $row['favicon'], (int) $row['primary_membergroup'],
		),
		array('forumid', 'cookiename', 'boardurl', 'boardname', 'subtheme', 'language', 'forumdir', 'favicon', 'primary_membergroup')
	);
}

function delete_subforum($sub)
{
	global $smcFunc;
	
	isAllowedTo('admin_forum');
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}subforums
		WHERE forumid = {int:forumid}',
		array(
			'forumid' => $sub
		)
	);
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