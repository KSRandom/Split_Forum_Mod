[hr]
[center][color=red][size=16pt][b]SPLIT FORUM MOD v1.2[/b][/size][/color]
[url=http://www.simplemachines.org/community/index.php?action=profile;u=253913][b]By Dougiefresh[/b][/url] -> [url=http://custom.simplemachines.org/mods/index.php?mod=3730]Link to Mod[/url]
[/center]
[hr]

[color=blue][b][size=12pt][u]Introduction[/u][/size][/b][/color]
This modification allows you to divide your categories into subforums, a pseudo-forum located on a different directory, subdomain, or domain than the "primary forum".  The primary forum is any forum that has this modification installed.

[color=blue][b][size=12pt][u]Admin Alterations[/u][/size][/b][/color]
There is a new area at [b][i]Admin[/i] -> [i]Forum[/i] -> [i]SubForums[/i][/b], which allows you to manage your subforums here.  Clicking on the board title on the left side will take you to the subforum itself.  Clicking on the [b]Boards[/b] link on the right side will take you to the [b][i]Admin[/i] -> [i]Forum[/i] -> [i]Boards[/i][/b] area, restricted to that subforum.

On the primary subforum, creating a subforum is as easy as clicking on the [b]Create New SubForum[/b] tab (and/or button) and filling out the information in order to create your new subforum.  This mod can create the folder and generate an "index.php" if required.  Subforum user registration agreements are mantained by the mod.

Clicking on [b]Modify[/b] opens a page where you can change settings for that subforum, such as:
o Changing the title of the subforum
o Changing the server URL and/or path of the subforum
o Adding a favorites icon to the subforum
o Changing the default theme of the subforum
o Changing the default language of the subforum
o Changing the subforum ID on secondary subforums
o Changing the cookie name of the subforum
o Changing the primary membergroup of new users registered to the subforum

Clicking on the [b]Delete[/b] button will prompt what to do with categories within the board and will delete once the decision has been made by the user.

On the [b][i]Admin[/i] -> [i]Forum[/i] -> [i]Boards[/i][/b] page using the primary subforum, the categories and boards are seperated by which subforum they belong to.  On secondary subforums, only those categories and boards that belong to that subforum show up.

"Package Manager" and "Server Settings" settings are not available to Subforums, as these screens contain sensitive information that affects all subforums.

All recent posts, xml-based feeds, and other forum-related functionality works for each of the individual subforums.

[color=blue][b][size=12pt][u]New Hook Added[/u][/size][/b][/color]
o [b]integrate_subforum_subdomain[/b] - Hook for creating/deleting subdomains and/or domains

[color=blue][b][size=12pt][u]To-Do List[/u][/size][/b][/color]
o While modifying a category, changing the subforum a category is assigned to does not change the board order list.
o Themes that are restricted to a single subforum
o Additional permissions for "subforum admins" and the like
o Theme settings restricted to members registered on a subforum
o Language settings restricted to members registered on a subforum

[color=blue][b][size=12pt][u]Compatibility Notes[/u][/size][/b][/color]
This mod was tested on SMF 2.0.7, but should work on earlier versions.  SMF 1.x is not and will not be supported.

This mod shares the [b]realtabs.css[/b] from [url=http://custom.simplemachines.org/mods/index.php?mod=3796]Real Tabs for Admin & Moderator Menus[/url], so you need to backup any customized [b]realtabs.css[/b] before installing the mod.

[color=blue][b][size=12pt][u]Upgrade from Previous Version(s)[/u][/size][/b][/color]
Upgrade is possible from [b]v1.0[/b] to [b]v1.1[/b].

[color=blue][b][size=12pt][u]Changelog[/u][/size][/b][/color]
[b][u]v1.2 - June 25, 2014[/u][/b]
o Fixed REALLY bad problem in version 1.1 resulting in errors in admin screen!

[b][u]v1.1 - June 23, 2014[/u][/b]
o Added English UTF8 language strings
o Changed the method that subforum boards and categories are listed to a tabbed system
o [b]edit_db.php[/b] modification to attempt to eliminate some weird error...

[b][u]v1.0 - May 17, 2013[/u][/b]
o Initial Release

[hr]
[url=http://creativecommons.org/licenses/by/3.0][img]http://i.creativecommons.org/l/by/3.0/80x15.png[/img][/url]
This work is licensed under a [url=http://creativecommons.org/licenses/by/3.0]Creative Commons Attribution 3.0 Unported License[/url]
