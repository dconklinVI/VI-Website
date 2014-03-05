=== ClickTale ===
Contributors: ClickTale
Donate link: http://www.clicktale.com
Tags: clicktale, analytics
Requires at least: 2.3.1
Tested up to: 3.6
Stable tag: trunk

This plugin allows easy integration of ClickTale script with Wordpress.

== Description ==

This is a plugin for self hosted Wordpress blogs. This plugin allows easy addition and change
of the ClickTale script. <br>It also automatically tags the recordings based on actions a user
makes.
<br>These actions are:
<ul><li>Searches using the "Search" button.</li>
<li>Additions of comments.</li>
<li>Clicks on the RSS or Comments-RSS link.</li>
</ul>
<br>This plugin has been tested on all versions of Wordpress up to 3.6. It will
probably work on all versions, if you find it not compatible with your version of wordpress,
please let us know about it.
<br>Please, send comments, bugs, feature requests and ideas for new auto-tags through the <a href="http://forums.clicktale.com/viewtopic.php?f=5&t=317" target=_blank>ClickTale forum</a>.
<br>If you do not have a wordpresss blog, you can get one from http://www.wordpress.org

== Installation ==

How to install:  
	<ol><li>Download the plugin from Wordpress plugin repository.</li>
	<li>Upload to your /wp-contents/plugins/ directory.</li>
	<li>Go to the admin interface in your WordPress web site (~/wp-admin/) and choose the Plugins tab. </li>
	<li>You should see ClickTale Plugin there, activate it. </li>
	<li>A new sub-tab called "ClickTale Options" will appear, click on it.</li>
	<li>Paste your top and bottom tracking scripts in the appropriate places, then press "Update Options" button (the tracking scripts are available in your ClickTale account).</li>
	<li>That's all! You have set up ClickTale on your Wordpress blog.</li>
</ol>
== Frequently Asked Questions ==

= Where can I find more information about this plugin =

You can visit <a href="http://wiki.clicktale.com/Article/WordPress_integration" target=_blank>ClickTale Wordpress Plugin Wiki</a> page to find more information about the plugin

= Why am I getting 404 error when going to ClickTale Options page =

This error is usually caused by one of the following issues:

    * Some errors occur as a result of misplaced files. Please make sure that all the files that were in the plugin zip file were placed in the correct path, which is "<wordpress root>/wp-content/plugins/clickTale" (please note this is case sensitive). 

If one of these is causing the problem, deactivate the ClickTale plugin on your "Manage Plugins" page. Then delete the ClickTale plugin files via WordPress or FTP and then reinstall according to instructions above. 

== Screenshots ==

1. There are no screenshots attached to this module.

== Changelog ==

= 1.10 =
* Fixed bottom script parsing in the options page
* Updated to use new yui 2.8.0

= 1.9.3 =
* Fixed security alerts when browsing through HTTPS protocol.

= 1.9.2 =
* Fixed XHTML validation issues.

= 1.9 =
* Fixed issues with case sensitivity of the ClickTale folder in which the plugin was installed.

= 1.8 =
* License updated to GPL version 2

== Upgrade Notice ==

= 1.9.2 =
Upgrade if you are using Wordpress under SSL.

= 1.9 =
Upgrade if you need your source to be XHTML valid.

= 1.8 =
The fix is essential if you are having trouble with the plugin options page. The fix does not affect the plugin functionality.

= 1.7 =
This version only updates the license, not necessary to upgrade.


== A brief Markdown Example ==

Copyright (c) 2013, ClickTale Ltd.