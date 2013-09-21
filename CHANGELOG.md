* Removed references to fixPagination JS function (search.js, media_modal.ctp element)
* Changed core adaptcms version to 3.0.1
* Updated to cakephp 2.3.8
* Changed appcontroller component/helpers to load from configure, added configuration file and updated installer to update the file instead of the json stuff.
* Fixed save draft bug in articles, media modal js overlap.
* Active and last_run field added for cron, along with updating permissions for the cron index and adding cron admin_test permission. Last run is updated whenever a cron runs fine. If a cron is unable to run, it sets itself to inactive and won't run unless someone runs a test and it works now or the active flag is set. A test method is also in. Fixed on the cron admin index dropdowns and text updated.
* Added vendor for parsing markdown, used in available data for templates and possibly other things such as install docs.
* Documentation functionality for templates. Looks under view_docs folder for specified file. (ex. /app/View_Docs/Articles/view.md - will load into tab the contents of this) Applied to Templates (edit) and Pages (add/edit).
* Fixed bug with theme refresh. (added blank template if array was empty - no new templates)
* Did template documentation on articles (view/tag), categories (view), pages (home/others), layouts (default)
* Updated install sql to not set default time for created/modified
* Updated all appropriate views to not render created/modified hidden input.
* Admin > Templates > List of Themes :: fixed
* Admin > Templates > No uninstall link if theme has no API data :: fixed
* Upgrade Plugin/Upgrade Theme now looks for an array of versions inside the upgrade key in their respective json files. Then inside that you can specify text and/or sql files. This allows upgrading of multiple versions, easily. Docs on adaptcms.com also updated. (not pushed yet)
* sluggable behavior and applied to all applicable models
* comments cleaned up in controllers
* paginate deprecated - replaced with Paginator->settings (for defining conditions) and Paginator->paginate('model') in place of paginate('model')
* defining properties in comments, defined variables in appcontroller
* plugin settings check if value is more than 20 characters and has a space in it, shows textbox
* test fixtures for permissions, users, start on links controller unit test
* plugin > links > submit link (permissions, action, view, sql and testing)
* plugin > links > model validation updated
* plugin > analytics > component caching issues fixed
* plugin > analytics > tracking status check done
* Minor bug fixes, UsersController
* profile > articles (says newest, but was ordering by default id asc, changed to created desc)
* converted default style into bootstrap 3.0
* created united theme
* polls list (permissions, action, view)
* updated polls model and refactored 'canVote' and poll model
* created helper 'View', first function is 'pluginExists' for an easy way to see if a plugin exists and is installed.
* refactored relations for polls model, renamed fields to remove 'plugin_' prefix
* refactored afterFind for polls model, so it calculates percentage in there instead of view
* updated admin css and updated left menu to get it working with new accordion in bootstrap (now 'collapse')
* polls admin edit poll - vote breakdown/stats
* plugin > support tickets > fixed bug with ticket model
* articles fixed route (so if only slug applied, will work - not recommended for duplicates)
* fixed links on main admin page for articles/users
* admin > comments > index/edit/delete/restore done
* comments - author name, email + website fields added (already had them in mysql). session is set if you use them/update them also.
* fixed components plugin install bug
* created sample plugin, repo and added to api website
* fixed bug on theme install, field_type was not updated and broke gaming theme
* updated categories view to match homepage
* created sample theme, repo and added to api website
* font awesome upgraded from 3.1 - 3.2.1
* article admin add/edit, if image field, modal images sorted by created descending
* admin > files > import folder
* admin > files > edit - bug fixed in js
* admin > files > upload - changed thumb size to match media frontend, 390 x 230added a zoom crop for the top left
* admin > files > upload - added libraries when uploading file
* media > index - now shows newest file in library
* admin > files > upload - for adding folder, multiple, single or edit :: can adjust zoom level for thumbnail
* view comment - edit/delete link and icons, updated permissions so it can do permission check on actions
* custom fields for comments - affects article view page, comment post and comment edit in admin
* cut user profile page queries by a good 20-30%
* update custom fields for users - uses field types
* updated field types - file (ouputs full url) and added multi-dropdown (does list - ex. PC, 360, Wii)
* admin > fields > import - bug fixed with field type/field options populating
* Ajax return element helper in appcontroller
* removed ajax_order method in fields controller, replacing with no ajax and a model function to update order
* fixed description popover on field order
* delete behavior sets model order to created descending if not set
* removed slug method from appcontroller (method exists in appmodel and behavior)
* When editing role, permission categories display plugin name if applicable
* CakePHP upgraded to 2.4.0
* Model > Roles - Set dependent to true on users/permissions, so associated records are removed upon deletion of a role.
* Model > Users - Fixed relationship with message, will delete messages related to deleted user record correctly.
* Admin > Users > Change User - Refactored to use new ajax response method, switched name to have admin_ prefix, cleaned up/refactoring and fixed some bugs with the JS.
* Users > Ajax Check User - Refactored with new ajax response method.
* Users > Ajax Quick Search - Refactored with new ajax response method, added permissions.
* Blocks > Admin Ajax Get Model - Refactored with new ajax response method.
* Comments > AJAX Post - Refactored with new ajax response method.
* Delete behavior part 1 - articles, comments, pages, fields, articles, blocks, categories, users, roles
* Delete behavior part 2 - cron, files, field types, media, menus, forum categories (adaptbb plugin), forums (adaptbb plugin), google maps (plugin), links (plugin), ticket categories/tickets (support ticket plugin), settings/setting values, templates, themes
* More Ajax Response update - adaptbb forum categories/posts/forums, polls ajax actions, template actions
* Updated AJAX Order for fields, adaptbb forums, adaptbb forum categories - removed AJAX functionality. (unnecessary)
* Plugin > Links > Model Validation :: Fixed validation so image url doesn't have to be entered in.
* Admin > Themes - Add :: Fixed a few errors when adding a theme.
* Admin > Themes - Actions :: Fixed bug when after adding a theme, didn't show edit/delete. (showed uninstall and edit)
* Model > Templates :: getFolders function, was returning files on first level - this is fixed.
* Admin > Templates - Add/Edit :: If no label entered, title (besides extension) is used.
* Admin > Templates - Add/Edit :: If .ctp is not entered, is automatically added to end of template title.
* Admin > Templates - Index :: Update view for templates/themes so you can see trash items for both at same time.
* Admin > Notify user of impending session expire, open link to prolong without losing data. (using noty jquery
* Core - Changed sessions from php to cake (this enabled continued sessions...if session time is 30 minutes long and you go to a page at 6:00 pm and 6:15 pm, your session won't expire until 6:45 pm instead of 6:30 pm)
* Users > Login :: Redirect to homepage with flash error if you are already logged in.
* Admin > Notify Login Ajax :: Started on ajax that fires off request to login and if it doesn't find a login form, fires off notify msg saying access denied. Otherwise, it shows login form. If you login successfully, success message shown and a new token is fetched for the field you are on. (done)
* Installer updated
* tinyMCE upgraded from 4.0.1 to 4.0.6
* jquery-ui upgraded from 1.9.2 to 1.10.3
* fancybox upgraded from 2.1.3 to 2.1.5
* Media > View :: Updated fancybox with new look, added thumbnail support to popup. (looks better overall)
* Codemirror upgraded from 3.0.2 to 3.16. Used their compressor so 238kb down to 130kb.
* Appletouch icon added