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