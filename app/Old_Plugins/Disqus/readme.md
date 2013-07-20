# Disqus

The Disqus plugin provides an easy to use helpers so users that use the commenting service can simply add a tag to their view and comments will fully go through Disqus instead of the normal commenting system.

## Installation

To install, first download the zip and unpack the folder 'Disqus' into your 'app/Old_Plugins' folder - be sure to chmod this new folder to 755. Then login into your admin panel and click on 'Manage Plugins'. You should see this plugin listed and an option to install on the right side.
Simply click that and click continue on the install and enter in your Disqus short name by going to - Admin > Manage Plugins > Disqus Settings.

Insert this code where you want comments to appear:

    <?= $this->Disqus->getComments() ?>

You can enter in a title with something like - 'title here' in between the paranthesis, or leave it blank.
When calling that, the Plugin will automatically detect the current URL and using your shortname, pull up the thread from Disqus.

### More Resources

Check out the official [API Page](http://api.adaptcms.com/plugin/disqus).