* Bug :: Admin > Categories > Edit > Related Fields - Not showing field type.
* Bug :: Admin > Templates > Default Theme - Setting ID instead of name, fixed.
* New Template Engine
* Template Re-Organization
* Search re-code in Angular
* Elements (most) switched to new template engine
* Finished plugin frontend templates/elements transferred to new engine
* Routes List in Admin Tools
* Admin Ajax Related add changed to update. (handles adding and removing single related articles) JS side of things updated so as soon as you click on one or click to delete one, the article is updated.
* Added disabled status for plugin index - easier to see what are active and what aren’t based on name of plugin.
* Refresh Themes callback after installing/un-installing plugin.
* New Plugin > Contact Form
* New Plugin > Article Ratings
* Bug :: Plugin permissions > not saving correctly
* Bug :: Plugin - Polls > Can vote method should not include user_id where condition if user is not logged in.
* Bugs :: Misc admin add/edit block fixes, removed old code.
* Blocks > Order By Options > New functionality to have a “getBlockOrderByOptions” in your model and return a key/value pair of order by options.
* Bug :: Get Block Data - needed proper setter/getters, was running too many queries.
* Plugin > Article Ratings - Added “Your Rating” functionality.
* Plugin > Polls - Start/end date.
* Plugin > Polls - Attach to article. Now inserted in main article data on article view page. (just call $article[‘Polls’] and loop through the polls or if there is one poll attached, call - {{ article['Polls'][0] }} to render the form/results)
* Model > Article > Bug Fix
* Search > XSS Security Bug fixed - http://packetstormsecurity.com/files/123585/adaptcms301-xss.txt
* View Docs > Renamed *.md to glob.md, due to windows issue.
* Tools > OneCMS Converter
* Upgrade After Method option (3.0.2 moves non-theme templates)
* CakePHP, TinyMCE, CodeMirror, jQuery BlockUI, html5, jQuery Smooth Scroll updated to newest versions.
* Upgrade After Method for 3.0.2 - now moves files/folders for installed/non-installed themes as well.
* Bug :: 5 min/2min timer - when i click on 5 min timer notice, it should also reset 2 min timer.
* Model > Behavior > Delete Behavior - Updated beforeFind to exclude Comment model when deleting, was throwing error.
* Admin Bug Hunting - Categories, Fields, Articles, Comments, Pages, Blocks
* Frontend Bug Hunting - Edit Profile, View Profile, Homepage, Category View, Article View, Comment Post/View, View Page
* Caching Views - The cached views from cake are not rendering the tags from adaptcmsview - added cachedispatcher and cachehelper to get block data and parse for tags, before writing cache.
* Events - Bug fixed in ArticleRatings/Polls, have to make sure plugin is loaded before attaching event.
* Templates - Fixed issue when deleting a template for a theme. (path was wrong)
* Uninstall Plugin - Templates are removed now upon uninstalling a plugin.
* Bug Fix :: Flash message response when refreshing theme or updating default.
* Fix :: Adjusted redirect, model update.
* Fix :: When uninstalling theme, it checks to see if the current theme is the same and if so, re-sets back to default.
* Theme Update :: Gaming layout updated to new template system/routing.
* BUG FIX :: Flash messages - not appearing in admin with cache enabled.
* BUG FIX :: Flash messages not appearing on frontend, nocache areas being cached. (session flash problem was strictly with logout, fixed...fixed view helper not getting routes)
* DONE :: Frontend Bug Hunting - Registration/login/logout
* DONE :: Plugin Bug Hunting (add/edit/delete/view asset, install/uninstall all plugins, settings, permissions)
* DONE :: Theme Bug Hunting - 
* DONE :: Admin Bug Hunting - Templates (mess around with adding/editing templates to default theme and custom theme), Themes (add/edit/delete theme files for custom and created themes)
* DONE :: Documentation - Plugins (creating a plugin, advanced configuration, basic management), Roles, Users, System (dev setting in core - miscellaneous, events)
* DONE :: Theme Bug Hunting - Test installation/uninstall of custom themes and any areas they affect, are working correctly. (already know templates are deleted upon uninstall correctly)
* DONE :: View Docs - Update with new template system tags.
* Bug :: Major caching issue, block data was being cached (for everyone).