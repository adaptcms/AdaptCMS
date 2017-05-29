# Installation for AdaptCMS

Newest version of installation documentation can be found below:

[https://adaptcms.gitbooks.io/adaptcms/content/Getting-Started/installation.html](https://adaptcms.gitbooks.io/adaptcms/content/Getting-Started/installation.html)

## Getting the Files

### With SSH Access \(Cloud Server/SSH for Shared Hositng\)

There's a few options here. First, you can always use [**Composer**](https://getcomposer.org/download/) via the command line:

```
composer require adaptcms/adaptcms
```

You can also grab the latest version, at any time below:

```
wget https://s3.amazonaws.com/adaptcms/latest.zip && unzip latest.zip
```

Or if you would like to get the newest stable version:

```
wget https://s3.amazonaws.com/adaptcms/stable.zip && unzip latest.zip
```

### No SSH Access \(Shared Hosting\)

So, assuming your PHP version is newer than 5.6.4 you can simply download one of the ZIP files below:

**Stable**

[https://s3.amazonaws.com/adaptcms/stable.zip](https://s3.amazonaws.com/adaptcms/stable.zip)

**Latest**

[https://s3.amazonaws.com/adaptcms/latest.zip](https://s3.amazonaws.com/adaptcms/latest.zip)

Then simply unzip the contents of the file locally. For the next step, you need a FTP client. We recommend:

[![](/assets/rsz_filezilla-logo.png)](https://filezilla-project.org/download.php?type=client)

Simply upload the contents of the ZIP file, which is a mix of folders and files, to your web host.

No SSH access and your PHP version isn't new enough? We highly recommend the below cloud server host. While it might be intimidating, we have an easy guide on setting up your first cloud server on [**DigitalOcean**](https://m.do.co/c/083895eaa907).

[![](/assets/rsz_do_logo_horizontal_blue-3db19536.png)](https://m.do.co/c/083895eaa907)

## Permissions

Permissions are one of the most important things for the install to go right.

### SSH Access

Please run the following commands on the directory you've installed AdaptCMS to. This will open the permissions for the CMS to be able to write/read files, while still not allowing public users to do anything nasty to your server.

```
chown www-data:www-data  -R . # Let the web server be the owner
find . -type d -exec chmod 755 {} \;  # Change directory permissions
find . -type f -exec chmod 644 {} \;  # Change file permissions
```

Now, if you installed through [**Github**](https://github.com/adaptcms/adaptcms) or [**BitBucket**](https://bitbucket.org/charliepage7/adaptcms), you'll want to run this command so that pull requests are still tied to the main user account on the server:

```
chown root:root  -R . # Let the web server be the owner
```

If your account is something different, such as ubuntu if you're on AWS, replace that with root. You should see the username in the path, but if you're unsure just run this:

```
whoami
```

### No SSH Access



## Running the Installer

Let's just say your site is **example.com** for simplicity sake from here on out. So you've uploaded all the files and set the permissions, you're ready for the next step. Go to the below URL, replacing your website domain of course:

```
http://www.example.com
```

If all goes well, you should see an installation setup guide that will take you through the different installation steps.

If you encounter any problems, whether it's getting the CMS to install or while using it, please feel free to reach out to us:

* [**Community Forums**](https://www.adaptcms.com/community)
* [**Slack**](mailto:charliepage88@gmail.com?subject=Add me to AdaptCMS Slack)
* [**Twitter**](https://twitter.com/adaptcms)
* [**GitHub Issues**](https://github.com/adaptcms/AdaptCMS/issues)
* [**Facebook**](https://www.facebook.com/AdaptCMS-104913829614704/)
* [**AdaptCMS.com**](https://www.adaptcms.com)
