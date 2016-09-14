<?php
/**********************************************************************************
* ManageSplitForum.english.php - English language file of the Split Forum Mod
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/
if (!defined('SMF')) 
	die('Hacking attempt...');

$txt['favicon'] = 'Website Icon URL';
$txt['sp-subforums'] = 'Subforum';

$txt['forumid_title'] = 'Forum Parent';
$txt['forumid_desc'] = 'The sub-forum this category is usable on';

$txt['subforums_list'] = 'SubForums';
$txt['subforums_list_title'] = 'Manage SubForums and Settings';
$txt['subforums_list_desc'] = 'Edit your Subforums here.  To create a new SubForum, click the Add SubForum button.';
$txt['subforums_list_header'] = 'Manage SubForums';
$txt['subforums_list_forumid'] = 'SubForum ID';
$txt['subforums_list_boardname'] = 'SubForum Name';
$txt['subforums_list_add'] = 'Create New SubForum';
$txt['subforums_list_boards'] = 'boards';
$txt['subforums_list_delete'] = 'delete';
$txt['subforums_list_prefix'] = 'SubForum';

$txt['subforum_modify_header'] = 'SubForum Settings';
$txt['subforum_modify_boardname'] = 'SubForum Board Name:';
$txt['subforum_modify_boardurl'] = 'SubForum Web Address (URL):';
$txt['subforum_modify_boardurl_small'] = 'Full address must include "http://"';
$txt['subforum_modify_favicon'] = 'Path to Site icon for SubForum:';
$txt['subforum_modify_subtheme'] = 'Default SubForum theme:';
$txt['subforum_modify_language'] = 'Default SubForum language:';
$txt['subforum_modify_primary_membergroup'] = 'Primary Membergroup for New Users:';
$txt['subforum_modify_dontchange'] = 'DON\'T change these settings unless you know what you are doing!!';
$txt['subforum_modify_forumid'] = 'SubForum ID number:';
$txt['subforum_modify_forumdir'] = 'Path to SubForum folder:';
$txt['subforum_modify_cookiename'] = 'Cookie Name:';
$txt['subforum_modify_confirm'] = 'Are you sure you want to delete this SubForum?';
$txt['subforum_modify_news'] = 'Copy News from Primary Forum?';

$txt['subforum_modify_prettyURL_title'] = 'Pretty URLs';
$txt['subforum_modify_prettyURL_enable'] = 'Enable Pretty URLs on this subforum:';

$txt['subforum_modify_sp_title'] = 'Simple Portal Blocks';
$txt['subforum_modify_sp_blocks'] = 'Copy blocks from Subforum';
$txt['subforum_modify_sp_blocks_nothing'] = '[ Do Nothing ]';
$txt['subforum_modify_sp_blocks_default'] = '[ Use Default Blocks ]';
$txt['subforum_modify_sp_blocks_remove'] = '[ Remove Blocks ]';

$txt['subforum_modify_ez_title'] = 'EzPortal Blocks';
$txt['subforum_modify_ez_portal_enable'] = 'Enable EzPortal HomePage?';
$txt['subforum_modify_ez_homepage_title'] = 'EzPortal HomePage Title';
$txt['subforum_modify_ez_shoutbox'] = 'Enable EzPortal ShoutBox on Subforum?';
$txt['subforum_modify_ez_blocks'] = $txt['subforum_modify_sp_blocks'];

$txt['subforum_server_url'] = 'Root webserver URL for new subforums';
$txt['subforum_server_root'] = 'Root server path for new subforums';
$txt['subforum_redirect_wrong'] = 'Redirect board to correct SubForum instead of error message';
$txt['subforum_sister_sites_title'] = 'Sister Sites menu title';
$txt['subforum_settings_topmenu'] = 'Show Sister Sites in top menu';
$txt['subforum_settings_topmenu_admin_only'] = 'Show Sister Sites in top menu to admin only';
$txt['subforum_settings_topmenu_under_home'] = 'Sister Sites under Home?';
$txt['subforum_settings_topmenu_include_this'] = 'Include this Subforum in Sister Sites?';
$txt['subforum_sister_sites'] = 'Sister Sites';
$txt['subforum_settings_register_at_primary'] = 'Redirect registration to Primary Forum:';
$txt['subforum_settings_show_who_in_subforum'] = 'Who\'s Online restricted to subforum?';

$txt['subforum_delete_title'] = 'Delete SubForum';
$txt['subforum_delete_line'] = 'Deleting this subforum will also delete the below categories and boards, including all topics, posts and attachments within each board:';
$txt['subforum_delete_what_to_do'] = 'Please select what you would like to do with these categories &amp; boards';
$txt['subforum_delete_option1'] = 'Delete subforum, along with all categories and boards contained within.';
$txt['subforum_delete_option2'] = 'Delete subforum and move all categories contained within to';

$txt['subforum_error_no_url'] = 'No web address has been specified for this forum.';
$txt['subforum_error_dup_id'] = 'The specified subforum ID has already been used.  Please select another ID number.';

$txt['no_pack_in_subforum'] = 'You cannot access the Packages page through a subforum!';

$txt['subforum_mod_update'] = 'Split Forum Mod version %s is available for download!';
$txt['subforum_no_mod_update'] = 'Your install of Split Forum Mod is up to date!';

$txt['subforum_no_categories'] = 'No Categories Defined';

$txt['subforum_deny'] = 'Deny access to';
$txt['permissiongroup_access_subforums'] = 'Subforum Access';
$txt['subforum_settings_permission_access'] = 'Restrict subforum access using permissions?';
$txt['subforum_settings_permission_access_log'] = 'Log users who access restricted subforums?';
$txt['subforum_access_denied'] = 'Access to this subforum has been denied.';

?>