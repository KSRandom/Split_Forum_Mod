[hr]
[center][color=red][size=16pt][b]SPLIT FORUM MOD v1.6[/b][/size][/color]
[url=http://www.simplemachines.org/community/index.php?action=profile;u=253913][b]By Dougiefresh[/b][/url] -> [url=http://custom.simplemachines.org/mods/index.php?mod=3730]Link to Mod[/url]
[/center]
[hr]

[color=blue][b][size=12pt][u]Introduction[/u][/size][/b][/color]
This modification allows you to divide your categories into subforums, a pseudo-forum located on a different directory, subdomain, or domain than the "primary forum".  The primary forum is any forum that has this modification installed.

[color=blue][b][size=12pt][u]What It Does[/u][/size][/b][/color]
[quote author=Terry at Moke link=topic=523055.msg3713355#msg3713355 date=1403833435]
It is a mod for the User side of things, but obviously it needs to be managed in the Admin pages to create the subforums, and this mod allows you to create Categories and Boards that appear to the end user as a separate Forum, either via the URL or even the domain name, and these separate Forums can have their own Themes as well. However it all shares the same database as your original forum so usernames, passwords, profiles etc are all the same so there is less for you and the end users to manage.

Without this mod if you wanted to limit access to particular Boards based you could set up a membergroup and just give them access to the Board(s) required and then they would see the extra Board(s) as part of the main Forum page, however they would still see the rest of the Forum as it is with the same theme etc. Yes you can change the theme for a Board but that would be a bit abrupt in most cases. 

With this mod you are essentially doing the same thing with the Membergroup and Board(s) regarding settng up the access but they get to see the Boards as a separate Forum, [u]without[/u] all the other boards they might have access too and with a completely different theme if you wish. In the subForum they see the same stats, who is online, News, menu options etc. relative to the membergroup access they have, but with just the Boards you have chosen for them to see.
[/quote]

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
o Themes that are restricted to a single subforum
o Additional permissions for "subforum admins" and the like
o Theme settings restricted to members registered on a subforum
o Language settings restricted to members registered on a subforum

[color=blue][b][size=12pt][u]Compatibility Notes[/u][/size][/b][/color]
This mod was tested on SMF 2.0.8, but should work on earlier versions.  SMF 1.x is not and will not be supported.

[url=http://custom.simplemachines.org/mods/index.php?mod=1104]Simple Portal v2.3.5[/url] should be installed before this mod if you want subforum support for blocks within Simple Portal.  Earlier versions of Simple Portal have not been tested for compatibility with this mod.

[color=blue][b][size=12pt][u]Upgrade from Previous Version(s)[/u][/size][/b][/color]
Upgrade is possible from [b]v1.2 thru v1.5[/b] to [b]v1.6[/b].

[color=blue][b][size=12pt][u]Changelog[/u][/size][/b][/color]
[quote]
[b][u]v1.6 - Auguest 11th, 2014[/u][/b]
o Modified Create Subforum functions to properly create new subforums without conflicts
o Modified Manage Boards UI so that when changing subforum and/or category, category and board list now changes.
o Fixed browser compatibility issue with revised Manage Category UI.
o Addition of a tabbed interface for Simple Portal block listing template.
o Rewrite of Simple Portal template modifications introduced in version 1.5.
o Added tab system to Blocks listing UI to make it easier to put seperate blocks in subforums.
o Modified several SSI functions so that they return results for only that subforum.

[b][u]v1.5 - Auguest 3rd, 2014[/u][/b]
o Fixed the subforum URL detection code that resulted in board doesn't exist errors....
o Fixed Manage Boards UI so that boards aren't hidden when entering Manage Boards UI...
o Updated [b]package-info.xml[/b] so that it redirects to clears the SMF file cache
o Modified Simple Portal code to support subforums selection for blocks.

[b][u]v1.4 - July 26th, 2014[/u][/b]
o While changing the subforum a category is assigned to, the category order list now changes.
o Added the ability to move boards between subforums
o Fixed multiple Manage Boards UI bugs, resulting from subforum ID not being passed
o Fixed an undeclared array element error found in [b]Load.php[/b]
o Modified tab system so that it works better with revised move boards ability
o Modified [b]db_install.php[/b] so that it doesn't overwrite previous subforum settings when installing
o Removed code from [b]uninstall.php[/b] that automatically removes subforum files...

[b][u]v1.3 - July 4th, 2014[/u][/b]
o Changed name of new subforums to "SubForum # [n]" (where [n] is the new subforum ID)
o Total rewrite of [b]edit_db.php[/b], now renamed to [b]db_install.php[/b]....
o Added support for listing undefined subforum IDs present in the system.
o Changed Database calls upon loading and subforum management to use arrays.
o Seperated English and English-UTF8 language strings into their own file.
o Added code to prevent global subforum administration from subforums.

[b][u]v1.2 - June 25th, 2014[/u][/b]
o Fixed REALLY bad problem in version 1.1 resulting in errors in admin screen!

[b][u]v1.1 - June 23th, 2014[/u][/b]
o Added English UTF8 language strings
o Changed the method that subforum boards and categories are listed to a tabbed system
o [b]edit_db.php[/b] modification to attempt to eliminate some weird error...

[b][u]v1.0 - May 17th, 2013[/u][/b]
o Initial Release
[/quote]

[hr]
[url=http://creativecommons.org/licenses/by/3.0][img]http://i.creativecommons.org/l/by/3.0/80x15.png[/img][/url]
This work is licensed under a [url=http://creativecommons.org/licenses/by/3.0]Creative Commons Attribution 3.0 Unported License[/url]
