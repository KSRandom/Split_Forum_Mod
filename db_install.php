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

// Define the subforum_tree variable's default content:
require_once($sourcedir.'/Subs-Admin.php');
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

// Insert the current subforum information settings into the Settings.php file:
updateSettingsFile(array('subforum_tree' => "unserialize('" . serialize($subforum_tree) . "')", 'forumid' => 0));

// Insert the current board path as the default server path for subforums:
require_once($sourcedir.'/Subs-Admin.php');
updateSettings(array('subforum_server_url' => $boardurl, 'subforum_server_root' => $boarddir ));

// Echo that we are done if necessary:
if ($SSI_INSTALL)
	echo 'DB Changes should be made now...';
?>