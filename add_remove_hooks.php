<?php
// If we have found SSI.php and we are outside of SMF, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');
if (SMF == 'SSI')
	db_extend('packages');
	
//==============================================================================
// Define the hooks
//==============================================================================
$hook_functions = array(
	// >> General hooks <<
	'integrate_pre_include' => '$sourcedir/Subs-SplitForumHooks.php',
	'integrate_menu_buttons' => 'SplitForum_Menu_Buttons',
	'integrate_actions' => 'SplitForum_Actions',
	'integrate_pre_load' => 'SplitForum_PreLoad',
	'integrate_load_theme' => 'SplitForum_LoadTheme',
	// >>  Admin hooks  <<
	'integrate_admin_include' => '$sourcedir/Subs-SplitForumAdminHooks.php',
	'integrate_admin_areas' => 'SplitForum_Admin_Menu',
	'integrate_load_permissions' => 'SplitForum_Permissions',
);

// Adding or removing them?
if (!empty($context['uninstalling']))
	$call = 'remove_integration_function';
else
	$call = 'add_integration_function';

// Do the deed
foreach ($hook_functions as $hook => $function)
	$call($hook, $function);

if (SMF == 'SSI')
   echo 'Congratulations! You have successfully installed this mod!';

?>