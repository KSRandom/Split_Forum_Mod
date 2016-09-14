<?php
global $db_prefix, $smcFunc, $sourcedir;
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

// Insert forumid column into categories table to associate each category with a particular forum:
$smcFunc['db_add_column']('{db_prefix}categories', array('name' => 'forumid', 'size' => 4, 'type' => 'int', 'null' => false, 'default' => 0));
//$smcFunc['db_query']('', "ALTER TABLE {db_prefix}categories ADD `forumid` INT( 4 ) NOT NULL DEFAULT '0'");

// Build the subforums table:
$columns = array(
	array(
		'name' => 'forumid',
		'type' => 'TINYINT',
		'size' => 4,
		'unsigned' => true,
		'default' => 0,
	),
	array(
		'name' => 'boardurl',
		'type' => 'varchar',
		'size' => 100,
	),
	array(
		'name' => 'cookiename',
		'type' => 'varchar',
		'size' => 100,
	),
	array(
		'name' => 'boardname',
		'type' => 'varchar',
		'size' => 100,
	),
	array(
		'name' => 'subtheme',
		'type' => 'TINYINT',
		'size' => 4,
		'unsigned' => true,
		'default' => 0,
	),
	array(
		'name' => 'language',
		'type' => 'varchar',
		'size' => 100,
	),
	array(
		'name' => 'forumdir',
		'type' => 'varchar',
		'size' => 100,
	),
	array(
		'name' => 'favicon',
		'type' => 'varchar',
		'size' => 100,
	),
	array(
		'name' => 'primary_membergroup',
		'type' => 'TINYINT',
		'size' => 4,
		'unsigned' => true,
		'default' => 0,
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('forumid')
	),
	array(
		'columns' => array('forumid')
	),
);
$smcFunc['db_create_table']('{db_prefix}subforums', $columns, $indexes, array(), 'update_remove');

// Insert the information about the primary forum into the database:
$smcFunc['db_insert']('replace',
	'{db_prefix}subforums',
	array(
		'cookiename' => 'text', 'boardurl' => 'text', 'boardname' => 'text', 
		'language' => 'text', 'forumdir' => 'text', 'favicon' => 'text',
	),
	array( $cookiename, $boardurl, $mbname, $language, $boarddir, '' ),
	array( 'cookiename', 'boardurl', 'boardname', 'language', 'forumdir', 'favicon' )
);
        
// Make sure that the settings file has the forumid number set by default:
require($sourcedir.'/Subs-Admin.php');
updateSettingsFile(array('forumid' => 0));

// Insert the current board path as the default server path for subforums:
updateSettings(array('subforum_server_url' => $boardurl, 'subforum_server_root' => $boarddir ));

// Echo that we are done if necessary:
if ($SSI_INSTALL)
	echo 'DB Changes should be made now...';
?>