<?php
global $db_prefix, $smcFunc, $sourcedir, $subforum_tree;
global $boardurl, $cookiename, $mbname, $language, $boarddir;

$SSI_INSTALL = false;
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$SSI_INSTALL = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');
db_extend('packages');
require_once($sourcedir.'/Subs-Admin.php');

// Insert forumid column into categories table to associate each category with a particular forum:
$smcFunc['db_add_column'](
	'{db_prefix}categories', 
	array(
		'name' => 'forumid', 
		'size' => 4, 
		'type' => 'int', 
		'null' => false, 
		'default' => 0
	)
);

// If we have one or no subforums defined as an array, check the table (if it exists):
if (count($subforum_tree) < 2)
{
	// Define the subforum_tree variable's default content:
	$subforum_tree = array(
		0 => array(		// Primary subforum
			'forumid' => 0,
			'cookiename' => $cookiename,
			'boardurl' => $boardurl,
			'boardname' => $mbname,
			'language' => $language,
			'forumdir' => $boarddir,
			'favicon' => '',
			'primary_membergroup' => 0,
			'subtheme' => 0,
		),
	);

	// If the subforum table exists, get all the information from it:
	$tblchk = $smcFunc['db_query']('', 'SHOW TABLES LIKE "' . $db_prefix . 'subforums"', array());
	while ($row = $smcFunc['db_fetch_row']($tblchk))
	{
		// Gather information about all the subforums and put them in the $subforum_tree array:
		$request = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}subforums
			ORDER BY forumid ASC',
			array()
		);
		$subforum_tree = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$subforum_tree[$row['forumid']] = $row;
		$smcFunc['db_free_result']($request);
		
		// Drop the subforum table from the database:
		$smcFunc['db_query']('', '
			DROP TABLE {db_prefix}subforums'
		);
	}
	$smcFunc['db_free_result']($tblchk);

	// Insert the current subforum information settings into the Settings.php file:
	if (empty($subforum_tree))
		updateSettingsFile(array('subforum_tree' => "unserialize('" . serialize($subforum_tree) . "')", 'forumid' => 0));
}

// Rearrange the subforum tree array so that the forumid is also the array index:
$tree = array();
foreach ($subforum_tree as $subforum)
	$tree[$subforum['forumid']] = $subforum;
$subforum_tree = $tree;

// Insert the current board path as the default server path for subforums:
require_once($sourcedir.'/Subs-Admin.php');
updateSettings(
	array(
		'subforum_server_url' => $boardurl, 
		'subforum_server_root' => $boarddir,
		'subforum_sister_site_title' => '',
	)
);

// Figure out where the attachment path(s) are:
$tblchk = $smcFunc['db_query']('', 'show tables like "' . $db_prefix . 'sp_blocks"', array());
while ($row = $smcFunc['db_fetch_row']($tblchk))
{
	if (str_replace('sp_blocks', '', $row[0]) <> $db_prefix)
		continue;

	// Insert forumid column into categories table to associate each category with a particular forum:
	$smcFunc['db_add_column'](
		'{db_prefix}sp_blocks',
		array(
			'name' => 'forum',
			'type' => 'varchar',
			'size' => 64,
			'default' => '0'
		)
	);
	$smcFunc['db_remove_column']('{db_prefix}sp_blocks', 'forums');

	// Make sure all subforum Simple Portal blocks are active:
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}sp_blocks
		SET state = 1
		WHERE forum > 0',
		array());
	break;
}
$smcFunc['db_free_result']($tblchk);

// Echo that we are done if necessary:
if ($SSI_INSTALL)
	echo 'DB Changes should be made now...';
?>