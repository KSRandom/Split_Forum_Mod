<?php
global $smcFunc;

$SSI_INSTALL = false;
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$SSI_INSTALL = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');

// Per SMF mod-creation guidelines, the directories that the mod has created need to be removed:
foreach ($subforum_tree as $id => $row)
{
	// Remove the index.php file we placed in the specified folder:
	unlink($row['forumdir'] . '/index.php');
	// Remove the specified folder....  Will fail if not empty:
	rmdir($row['forumdir']);
	// Remove the agreement.forum[n].txt file in the main forum folder:
	unlink($boarddir . '/agreement.forum' . $row['forumid'] . '.txt');
}
	
// Order the categories according to the forum id, then category order:
$request = $smcFunc['db_query']('', '
	SELECT id_cat
	FROM {db_prefix}categories
	ORDER BY forumid ASC, cat_order ASC, id_cat ASC',
	array()
);
$order = 0;
while ($row = $smcFunc['db_fetch_assoc']($request))
	$cat[$row['id_cat']] = $order++;
	
// Rewrite the category order so that the categories are in order from primary to last subforum:
foreach ($cat as $catid => $order)
{
	$request = $smcFunc['db_query']('', '
		UPDATE {db_prefix}categories
		SET cat_order = {int:order}
		WHERE id_cat = {int:id}',
		array(
			'order' => $order,
			'id' => $catid,
		)
	);
}

// Echo that we are done if necessary:
if ($SSI_INSTALL)
	echo 'DB Changes should be made now...';
?>