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

// Build the subforum tree array:
$request = $smcFunc['db_query']('', '
	SELECT * FROM {db_prefix}subforums'.($forumid <> 0 ? ' WHERE forumid = {int:forumid}' : '').' ORDER BY forumid',
	array(
		'forumid' => (int) $forumid,
	)
);
$subforum_tree = array();
while ($row = $smcFunc['db_fetch_assoc']($request))
{
	$subforum_tree[$row['forumid']] = $row;
}
$smcFunc['db_free_result']($request);

// Insert the current subforum information settings into the Settings.php file:
require_once($sourcedir.'/Subs-Admin.php');
updateSettingsFile(array('subforum_tree' => "unserialize('" . serialize($subforum_tree) . "')", 'forumid' => 0));

// Drop the subforums table from the database:
$smcFunc['db_drop_table']("{db_prefix}subforums");

// Echo that we are done if necessary:
if ($SSI_INSTALL)
	echo 'DB Changes should be made now...';
?>