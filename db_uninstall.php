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

$tblchk = $smcFunc['db_query']('', 'show tables like "%sp_blocks"', array());
while ($row = $smcFunc['db_fetch_row']($tblchk))
{
	if (str_replace('sp_blocks', '', $row[0]) <> $db_prefix)
		continue;
	
	$smcFunc['db_query']('', 'UPDATE {db_prefix}sp_blocks SET state = 0 WHERE forum > 0', array());
	break;
}

// Echo that we are done if necessary:
if ($SSI_INSTALL)
	echo 'DB Changes should be made now...';
?>