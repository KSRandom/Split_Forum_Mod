<?php
global $boardurl, $mbname, $language, $boarddir, $subforum_tree;

// If we have found SSI.php and we are outside of SMF, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');
require_once($sourcedir.'/Subs-Admin.php');

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
}

// Rearrange the subforum tree array so that the forumid is also the array index:
$tree = array();
foreach ($subforum_tree as $subforum)
{
	unset($subforum['cookiename']);
	$tree[$subforum['forumid']] = $subforum;
}
$subforum_tree = $tree;
updateSettingsFile(array('subforum_tree' => "unserialize('" . serialize($subforum_tree) . "')", 'forumid' => 0));

// Capture mod version number during the run of this script:
$contents = file( dirname(__FILE__) . '/package-info.xml' );
$mod_version = '';
if (preg_match('#\<version\>(.+?)\</version\>#i', implode('', $contents), $version))
	$mod_version = $version[0];

// Insert the current board path as the default server path for subforums:
updateSettings(
	array(
		'subforum_server_url' => $boardurl, 
		'subforum_server_root' => $boarddir,
		'subforum_sister_site_title' => '',
		'subforum_mod_version' => $mod_version,
	)
);

foreach ($subforum_tree as $subforum)
{
	// Rewrite .htaccess for Pretty URLs only if Pretty URLs is installed:
	if (file_exists($sourcedir . '/Subs-PrettyUrls.php'))
	{
		require_once($sourcedir . '/Subs-PrettyUrls.php');
		$old_boarddir = $boarddir;
		$boarddir = (isset($subforum['forumdir']) ? $subforum['forumdir'] : $old_boarddir);
		$old_boardurl = $boardurl;
		$boardurl = (isset($subforum['boardurl']) ? $subforum['boardurl'] : $old_boardurl);
		pretty_update_filters(false, $subforum['boardurl']);
		$boarddir = $old_boarddir;
		$boardurl = $old_boardurl;
	}

	// Skip the rest of the this if this is the primary forum:
	if ($subforum['forumid'] == 0)
		continue;
	
	// Attach some other crap for subforums to prevent 404 errors:
	$path = relativePath($subforum['forumdir'], $boarddir);
	$oldHtaccess = file_get_contents($subforum['forumdir'] . '/.htaccess');
	$insert = "\n\n# SUBFORUM MOD BEGINS\nRewriteEngine on\nOptions +FollowSymlinks\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteRule (.*)/(.*) " . $path . "$1/$2\n# SUBFORUM MOD ENDS";
	$oldHtaccess = str_replace($insert, '', $oldHtaccess) . $insert;
	if ($handle = fopen($subforum['forumdir'] . '/.htaccess', 'w'))
	{
		fwrite($handle, $oldHtaccess);
		fclose($handle);
		@chmod($subforum['forumdir'] . '/.htaccess', 0755);
	}
}

if (SMF == 'SSI')
	echo 'Congratulations! You have successfully installed the settings for this mod!';

/******************************************************************************/
function relativePath($from, $to, $ps = DIRECTORY_SEPARATOR)
{
	$arFrom = explode($ps, rtrim($from, $ps));
	$arTo = explode($ps, rtrim($to, $ps));
	while(count($arFrom) && count($arTo) && ($arFrom[0] == $arTo[0]))
	{
		array_shift($arFrom);
		array_shift($arTo);
	}
	$base = str_pad("", count($arFrom) * 3, '..' . $ps) . implode($ps, $arTo);
	return str_replace(DIRECTORY_SEPARATOR, '/', $base);
}

?>