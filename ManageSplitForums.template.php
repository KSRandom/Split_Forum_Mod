<?php
/**********************************************************************************
* ManageSubForums, template, php - PHP template of the Split Forum Mod
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

function template_subforums_list()
{
	global $context, $settings, $scripturl, $txt, $subforum_tree, $forum_version;

	// Flag that will be used to add SMF 2.1 elements to the template:
	$smf21 = (substr($forum_version, 0, 7) == 'SMF 2.1');

	// Let's get this template started:
	echo '
	<div id="manage_boards">
		<form action="', $context['post_url'], ';sa=newsub" method="post" accept-charset="', $context['character_set'], '">
			<div class="cat_bar">
				<h3 class="catbg">', $txt['subforums_list_header'], '</h3>
			</div>
			<div class="windowbg">
				<span class="topslice"><span></span></span>';
	if ($smf21)
		echo '
				<div class="sub_bar">
					<h3 class="subbg">
						', $txt['subforums_list'], '
					</h3>
				</div>';
	echo '
				<div class="content">
					<ul style="float:left; width:100%;">';

	// List through every subforum, printing its name and link to modify the subforum, 
	$alternate = false;
	foreach ($subforum_tree as $subforum)
	{
		$alternate = !$alternate;
		echo '
						<li class="windowbg', $alternate ? '' : '2', '" style="padding-', ($context['right_to_left'] ? 'right' : 'left'), ': 5px;">
							<span class="floatleft"><img src="', (!empty($subforum['favicon']) ? $subforum['favicon'] : $settings['actual_images_url'] . '/empty.png'), '" width=16 height=16 />&nbsp;', $subforum['forumid'], ' - <a href="', $subforum['boardurl'], '">', $subforum['boardname'], '</a></span>
							<span class="floatright">';
		if ($subforum['forumid'] != 0)
		echo '
								<span class="modify_boards"><a href="', $context['post_url'], ';sa=delete;sub=', $subforum['forumid'], '" onclick="return confirm(\'', $txt['subforum_modify_confirm'], '\');"', ($smf21 ? ' class="button"' : ''), '>', $txt['subforums_list_delete'], '</a></span>';
		echo '
								<span class="modify_boards"><a href="', $scripturl, '?action=admin;area=manageboards;sub=', $subforum['forumid'], '"', ($smf21 ? ' class="button"' : ''), '>', $txt['subforums_list_boards'], '</a></span>
								<span class="modify_boards"><a href="', $context['post_url'], ';sa=edit;sub=', $subforum['forumid'], '"', ($smf21 ? ' class="button"' : ''), '>', $txt['mboards_modify'], '</a></span>
							</span>
							<br style="clear: right;" />
						</li>';
	}

	// Let's finish this template:
	echo '
					</ul>
					<div class="righttext">
						<input type="submit" value="', $txt['subforums_list_add'], '" class="button_submit" />
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					</div>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</form>
	</div>';
}

function template_subforums_delete()
{
	global $context, $settings, $options, $scripturl, $txt, $subforum_tree, $subforum_tree;
	
	// Let's get this template started:
	echo '
	<div id="manage_boards">
		<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
			<div class="cat_bar">
				<h3 class="catbg">', $txt['subforum_delete_title'], '</h3>
			</div>
			<div class="windowbg">
				<span class="topslice"><span></span></span>
				<div class="content">
					', $txt['subforum_delete_line'], '
					<br style="clear: right;" /><br style="clear: right;" />';

	// List all categories and boards assigned to this subforum:
	$alternate = false;
	foreach ($context['categories'] as $category)
	{
		$alternate = !$alternate;
		echo '
					<div class="cat_bar">
						<h3 class="catbg">
							', $category['name'], '
						</h3>
					</div>
					<ul>';
		foreach ($category['boards'] as $board)
			echo '
						<li class="windowbg', $alternate ? '' : '2', '" style="padding-', ($context['right_to_left'] ? 'right' : 'left'), ': 5px;">
							<span class="floatleft">', $board['name'], '</span>
							<br style="clear: right;" />
						</li>';
		echo '
					</ul>';
	}

	// Let's finish this template:
	echo '
					<div class="righttext">
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					</div>
				</div>
				<span class="botslice"><span></span></span>
			</div>
			<div class="cat_bar">
				<h3 class="catbg">', $txt['subforum_delete_what_to_do'], '</h3>
			</div>
			<div class="windowbg">
				<span class="topslice"><span></span></span>
				<div class="content">
					<p>
						<label for="delete_action0"><input type="radio" id="delete_action0" name="delete_action" value="0" class="input_radio" checked="checked" />', $txt['subforum_delete_option1'], '</label><br />
						<label for="delete_action1"><input type="radio" id="delete_action1" name="delete_action" value="1" class="input_radio" />', $txt['subforum_delete_option2'], '</label>:
						<select name="forum_to">';

	foreach ($subforum_tree as $subforum)
		if ($subforum['forumid'] != $context['subforumID'])
			echo '
							<option value="', $subforum['forumid'], '">', $subforum['boardname'], '</option>';

	echo '
						</select>
					</p>
					<div class="righttext">
						<input type="submit" name="delete" value="', $txt['mboards_delete_confirm'], '" class="button_submit" />
						<input type="submit" name="cancel" value="', $txt['mboards_delete_cancel'], '" class="button_submit" />
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					</div>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</form>
	</div>';
}

?>