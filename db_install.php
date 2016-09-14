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

// Capture mod version number during the run of this script:
$new = array();
$contents = file( dirname(__FILE__) . '/package-info.xml' );
if (preg_match('#\<version\>(.+?)\</version\>#i', implode('', $contents), $version))
	$mod_version = $version[0];
else
	$mod_version = '';

// Insert forumid column into categories and calendar table to associate with a particular forum:
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
$smcFunc['db_add_column'](
	'{db_prefix}calendar', 
	array(
		'name' => 'forumid', 
		'size' => 4, 
		'type' => 'int', 
		'null' => false, 
		'default' => 0
	)
);

// If we have one or no subforums defined as an array, check the table (if it exists):
if (empty($subforum_tree))
{
	// Define the subforum_tree variable's default content:
	$subforum_tree = array(
		0 => array(		// Primary subforum
			'forumid' => 0,
			'boardurl' => $boardurl,
			'boardname' => $mbname,
			'language' => $language,
			'forumdir' => $boarddir,
			'favicon' => '',
			'primary_membergroup' => 0,
			'subtheme' => 0,
		),
	);
	updateSettingsFile(array('subforum_tree' => "unserialize('" . serialize($subforum_tree) . "')", 'forumid' => 0));
}

// Rearrange the subforum tree array so that the forumid is also the array index:
$tree = array();
foreach ($subforum_tree as $subforum)
{
	unset($subforum['cookiename']);
	$tree[$subforum['forumid']] = $subforum;
}
$subforum_tree = $tree;

// Insert the current board path as the default server path for subforums:
require_once($sourcedir.'/Subs-Admin.php');
updateSettings(
	array(
		'subforum_server_url' => $boardurl, 
		'subforum_server_root' => $boarddir,
		'subforum_sister_site_title' => '',
		'subforum_mod_version' => $mod_version,
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