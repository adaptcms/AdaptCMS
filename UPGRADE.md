3.0.2 - 3.0.3
-------------

####Step 1

After you are logged in as an admin, access the installer at <strong>http://yoursite.com/install</strong> and upload the following directories/files to your site and overwriting what is on your server currently:

* lib/
* vendors/
* app/Config/routes.php
* app/Config/bootstrap.php
* app/Config/core.php
* app/Routing/
* app/Service/
* app/Model/
* app/Controller/
* app/View_Docs/
* app/webroot/installer/
* app/webroot/js/
* app/webroot/css/
* app/webroot/fonts/
* app/webroot/img/
* app/webroot/libraries/
* app/webroot/angular/
* app/View/AdaptcmsView.php
* app/View/Elements/
* app/View/Helper/
* app/View/Admin/
* app/View/Frontend/Media/
* app/View/Frontend/Messages/
* app/View/Frontend/Search/
* app/View/Frontend/Users/

At this point you can also update your plugins that reside in <strong>app/Plugin</strong> and <strong>app/Old_Plugins</strong>. Make sure to bring in the contents of each plugins
folder, except the <strong>Config/config.php</strong> file, so that your configuration settings (if the plugin has them) are not reset. You can also overwrite <strong>app/View/Themed</strong> and <strong>app/View/Old_Themed</strong> if you haven't made changes to your themes.

Then refresh the install page and click on "Upgrade from 3.0.2".

####Step 2

After SQL is inserted, go into your admin panel and clear the cache. You should be set!

####Issues?

If you encounter any issues, such as a blank white page or an internal server error - please submit a ticket:

http://www.adaptcms.com/support

3.0.1 - 3.0.2
-------------

####Step 1

After you are logged in as an admin, access the installer at <strong>http://yoursite.com/install</strong> and upload the following directories/files to your site and overwriting what is on your sever currently:

* lib/
* vendors/
* app/Config/routes.php
* app/Config/core.php
* app/Config/configuration.php
* app/Config/bootstrap.php
* app/Routing/
* app/Model/
* app/Controller/
* app/View_Docs/
* app/webroot/installer/
* app/webroot/js/
* app/webroot/css/
* app/webroot/font/
* app/webroot/img/
* app/webroot/libraries/
* app/View/Elements/
* app/View/Helper/
* app/View/Admin/
* app/View/Frontend/

At this point you can also update your plugins that reside in <strong>app/Plugin</strong> and <strong>app/Old_Plugins</strong>. Make sure to bring in the contents of each plugins
folder, except the <strong>Config</strong> folder, so that your configuration settings (if the plugin has them) are not reset. You can also overwrite <strong>app/View/Themed</strong> and <strong>app/View/Old_Themed</strong> if you haven't made changes to your themes.

Then refresh the install page and click on "Upgrade from 3.0.1".

####Step 2

After SQL is inserted, the CMS will move files and folders over - including installed and non-installed themes. If you are running a theme, please check the documentation for the new folder/file structure - otherwise, the default theme will be automatically used. That's it! Please keep in mind a few template changing notes:

* Your layout is now located at - app/View/Frontend/layout.ctp
* For category view templates, the variable $article for a list of articles has changed to $articles

3.0 - 3.0.2
-----------

####Step 1

First, update the <strong>app/Config/core.php</strong> file from 3.0.1 to your server. This include a new session system, so then proceed to login as an admin to your site.

####Step 2

After you are logged in as an admin, access the installer at <strong>http://yoursite.com/install</strong> and upload the following directories/files to your site - overwriting the files on
your server currently:

* lib/
* vendors/
* app/Config/routes.php
* app/Config/configuration.php
* app/Model/
* app/Controller/
* app/View_Docs/
* app/webroot/installer/
* app/webroot/js/
* app/webroot/css/
* app/webroot/font/
* app/webroot/img/
* app/webroot/folder_upload/
* app/webroot/libraries/

####Step 3

Click on <strong>Upgrade from 3.0</strong> and continue through the process. Once the SQL has been updated, bring over the rest of the folders inside <strong>app/View</strong> EXCEPT:

* app/View/Articles/
* app/View/Categories/
* app/View/Layouts/
* app/View/Pages/

At this point you can also update your plugins that reside in <strong>app/Plugin</strong> and <strong>app/Old_Plugins</strong>. Make sure to bring in the contents of each plugins
folder, except the <strong>Config</strong> folder, so that your configuration settings (if the plugin has them) are not reset. Then from the folders above, bring only the
following files:

* app/View/Articles/admin_index.ctp
* app/View/Articles/admin_add.ctp
* app/View/Articles/admin_edit.ctp
* app/View/Categories/admin_index.ctp
* app/View/Categories/admin_add.ctp
* app/View/Categories/admin_edit.ctp
* app/View/Pages/admin.ctp
* app/View/Pages/admin_index.ctp
* app/View/Pages/admin_add.ctp
* app/View/Pages/admin_edit.ctp
* app/View/Layouts/admin.ctp

####Step 4 (optional)

If you see an Internal Error when visiting your homepage, that means that you have the Polls and/or the Links plugin installed and they need to be updated. Go into the admin and click on
<strong>Manage Plugins</strong>, then click on Upgrade from the actions dropdown by Polls and Links. Run through the upgrader for both and you should be all set.

Now follow the steps above.