[hr]
[center][color=red][size=16pt][b]SPLIT FORUM MOD v1.26[/b][/size][/color]
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
o Changing the primary membergroup of new users registered to the subforum

Clicking on the [b]Delete[/b] button will prompt what to do with categories within the board and will delete once the decision has been made by the user.

On the [b][i]Admin[/i] -> [i]Forum[/i] -> [i]Boards[/i][/b] page using the primary subforum, the categories and boards are seperated by which subforum they belong to.  On secondary subforums, only those categories and boards that belong to that subforum show up.

"Package Manager" and "Server Settings" settings are not available to Subforums, as these screens contain sensitive information that affects all subforums.

All recent posts, xml-based feeds, and other forum-related functionality works for each of the individual subforums.

[color=blue][b][size=12pt][u]New Hook Added[/u][/size][/b][/color]
o [b]integrate_subforum_subdomain[/b] - Hook for creating/deleting subdomains and/or domains

[color=blue][b][size=12pt][u]To-Do List[/u][/size][/b][/color]
o Copy a category/board from one subforum to another
o Themes that are restricted to a single subforum
o Additional permissions for "subforum admins" and the like
o Theme settings restricted to members registered on a subforum
o Language settings restricted to members registered on a subforum

[color=blue][b][size=12pt][u]Special Credits[/u][/size][/b][/color]
o [url=http://www.simplemachines.org/community/index.php?action=profile;u=394494]Greygal[/url] made the mod compatible with [url=http://custom.simplemachines.org/mods/index.php?mod=3238]Event Registration for SMF2[/url] in 3 posts starting [url=http://www.simplemachines.org/community/index.php?topic=523055.msg3767088#msg3767088]here[/url]!  Thanks, greygal!!

[color=blue][b][size=12pt][u]Compatibility Notes[/u][/size][/b][/color]
This mod was tested on SMF 2.0.10, but should work on SMF 2.1 Beta 1, as well as SMF 2.0 and up.  SMF 1.x is not and will not be supported.

[url=http://custom.simplemachines.org/mods/index.php?mod=1104]SimplePortal v2.3.6[/url] should be installed before this mod if you want subforum support for blocks within SimplePortal.  This mod may not install correctly with prior versions of SimplePortal installed in a clean install situation.

[url=http://custom.simplemachines.org/mods/index.php?mod=1024]Alias Boards v1.2+[/url] should be installed prior to this mod.  Alias Boards mod has been altered so that it works only within a single subforum, meaning board 1 from category 1 can be aliased into category 2 in subforum 1, but [b]NOT[/b] into any category in subforum 2....

[url=http://custom.simplemachines.org/mods/index.php?mod=636]Pretty URLs[/url] should be installed prior to this mod.

[color=blue][b][size=12pt][u]Upgrade from Previous Version(s)[/u][/size][/b][/color]
Upgrade is possible from previous version of this mod to this version.

[color=blue][b][size=12pt][u]Changelog[/u][/size][/b][/color]
The changelog has been removed and can be seen at [url=http://www.xptsp.com/board/index.php?topic=17.msg328#msg328]XPtsp.com[/url].

[color=blue][b][size=12pt][u]License[/u][/size][/b][/color]
Copyright (c) 2015, Douglas Orend
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
