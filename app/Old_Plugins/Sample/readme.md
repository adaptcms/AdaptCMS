# Sample

This is a sample plugin that developers can use as a base to create their own AdaptCMS plugin. We have included a dummy component, helper, model and basic crud actions with permissions. You will also find a frontend index (yoursite.com/sample) and a view for individual sample 'items'. Additionally, this plugin adds block support.

Keep in mind we are including pretty much all the types of things you can do with a plugin, you can easily drop these items. If you wish to delete the component, be sure to first uninstall and remove the components array in the plugin.json. (same with the helper and for block support)

## Installation

To install, first download the zip and unpack the folder 'Sample' into your 'app/Old_Plugins' folder - be sure to chmod this new folder to 755. Then login into your admin panel and click on 'Manage Plugins'. You should see this plugin listed and an option to install on the right side.
Simply click that and click continue on the install. After that, you can mess around with the plugin and see what kind of plugin you would want to make.

Checkout the resources below to get all the information you need on how to make a plugin in cakePHP/AdaptCMS.

### More Resources

* [AdaptCMS.com 'How to make a Plugin' documentation](http://www.adaptcms.com/pages/api-documentation#plugins-how)
* [cakePHP Plugins Documentation](http://book.cakephp.org/2.0/en/plugins.html)