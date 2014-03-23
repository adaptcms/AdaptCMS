* Admin > Media Modal - recoded into angularJS - updates to articles add/edit and media add/edit made.
* Admin > Articles > validation - model validation added for required fields.
* Admin > Articles > Revisions - Ability to save up to x amount of revisions per articles. Whenever you update an article a new revision is added (or if a max of x amount, the oldest is replaced). Additionally, there is now a “Quick Save” button on the top right nav bar and an “Auto Save” to save every 1, 2, 5 or 10 minutes. You can preview what a revision looked like (see below) and restore it.
* Admin Articles > Preview - Ability to preview the article changes, can also see what previous revisions looked like.
* Admin > Themes - When installing a theme, option to mark it as the default theme.
* Admin > Plugins - After installing a plugin, redirect to the settings page for it. (if it has settings)
* Admin > Tools - Feeds (a simple page to see a list of RSS feeds for all content and by category and/or limit)
* Admin > Fields - A few minor bugs fixed with hiding/showing elements based on field type.
* Admin > Articles > Custom Fields - Fixed an issue with checkboxes, added an afterFind json_decode so it decodes automatically.
* Admin > Users > Forgot Password - When activating forgot password, security question/answer is on the page.
* Admin > Articles > Media Libraries - Functionality to add libraries to article and retrieve on frontend. (view doc for article view pages also updated to include full usage)
* Model > Media > Optimized - Wasn’t aware I could have a MediaFile model to query off of for counts and getting newest file - queries updated to use this.
* BUG :: Media Index/Admin Media Index - Counts were incorrect if files were deleted.
* Admin - Boostrap 3 full integrated
* Plugins > Links - Updated to support new media modal.
* Admin > Fields - Filter by type bug fixed.
* Admin > Media Modal - Misc minor css fixes.
* Admin - Misc css fixes.
* Admin > Users > Add Users - If validation fails, on post, module data values are not retained.
* Admin > Users > Add Users - Module values not created correctly.
* Admin > Templates > Global Tags - Deletes cached templates and views upon update.
* Admin > Menus - Index - Updated php tag to new template format.
* Plugin > AdaptBB > Index - Reworked query so deleted forums do not show.
* CakePHP 2.4.3 - 2.4.6
* fontAwesome 4.0.3
* Bootstrap JS 3.0.3/2.x - 3.1.1
* jqueryUI 1.10.3 - 1.10.4
* tinyMCE 4.0.10 - 4.0.19
* Angular JS Core/Route/Sanitize 1.2.8 - 1.2.14
* Noty 2.1.0 - 2.2.2
* Secureimage 3.5.1 - 3.5.2
* Codemirror 3.19 - 3.22
* Markdown 1.3 - 1.4
* Firefox Bug - /adaptcms/admin/articles/edit/1 :: revisions,  json.parse error when clicking preview on a revision
* Firefox Bug - /adaptcms/admin/articles/edit/1 :: is auto save working? set it to 1 minute and it only saved once. (did this on ie and went to another page, CSRF error - add it to unlocked actions)
* Firefox Bug - Remove hover blue color from “Content”, “Users”, etc. (visible in at least firefox)
* Bug - /adaptcms/admin/users/edit/1 :: Change class of submit.
* Firefox Bug - /adaptcms/admin/roles :: Add clearfix after add role. Remove div clear before the add button.
* Firefox Bug - /adaptcms/admin/tools/create_theme#/versions :: Add margin-bottom of 5-10px between versions for some space.
* FF Bug - /adaptcms/admin/templates/global_tags :: Add clearfix after right nav items. (update/add) However, then when clicking to add it actually saves it instead - use linux ff to investigate.
* FF Bug - /adaptcms/admin/menus/add(edit) :: Instead of “Add Category”, “Add Page” - just rename to “Add”.
* FF Bug - /adaptcms/admin/settings :: clearfix
* FF Bug - /adaptcms/admin/settings/edit/1 :: class btn on update
* FF Bug - /adaptcms/admin/plugins :: clearfix
* Bug - Routes list permissions by default for admin.
* FF Bug - /adaptcms/admin/google_maps :: clearfix...also when adding/editing, move map to pull-left instead of right.
* FF Bug - /adaptcms/admin/links(polls) :: clearfix
* Bug - /adaptcms/js/admin.articles.js :: line 317 getContent() on tinyMCE
* Bug - /adaptcms/admin/fields/edit/2 :: remove hidden-xs class.
* Bug - Issues with including JS repeatedly on an angularJS admin page. Clearing cache fixes it, but need a better solution.
* Bug - Adjust datepicker so after selecting date, it hides. Good way to see how this gets annoying as is, is on the polls add page.
* Bug - If an error is found when loading plugins in bootstrap, all cache is wiped.
* Bug - Related to the previous one, major issue with opcache and multiple adapt sites - changed it so there is a prefix with a md5 hash of the script path, so cache is unique.