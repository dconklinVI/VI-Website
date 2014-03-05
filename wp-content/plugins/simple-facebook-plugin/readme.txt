=== Simple Facebook Plugin ===

Contributors: fornyhucker
Tags: social, media, facebook, like box, widget, shortcode, template tag, sidebar
Requires at least: 2.8
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2 or later

Allows you to integrate Facebook Like Box into your WordPress Site.

== Description ==

= Whats new in 1.2.1? =

Now you can hide Like Box border, just uncheck 'Show Border' option on widget setting and vuala! (If you are using shortcode or template tag set ‘border‘ parameter to ‘false‘)

If you are using shortcode or template tag set 'border' parameter to 'false'

= Description =

Simple Facebook Plugin enables Facebook Page owners to attract and gain Likes from their own WordPress blogs. The **Like Box** enables users to:

* See how many users already like this Page, and which of their friends like it too
* Read recent posts from the Page
* Like the Page with one click, without needing to visit the Page

Please visit [Plugin Home Page](http://plugins.topdevs.net/simple-facebook-plugin/ "Simple Facebook Plugin Home Page") for more info and examples.

**Supported languages**

* Czech
* Danish
* Dutch
* English (US)
* English (UK)
* Estonian
* French
* German
* Italian
* Japanese
* Korean
* Latvian
* Lithuanian
* Norwegian (bokmal)
* Polish
* Portuguese
* Romanian
* Russian
* Spanish
* Spanish (Spain)
* Spanish (Chile)
* Spanish (Colombia)
* Spanish (Mexico)
* Spanish (Venezuela)
* Swedish
* Simplified Chinese
* Traditional Chinese (Taiwan)
* Thai
* Turkish
* Ukrainian
* Klingon

== Installation ==
**Installation**

1. Upload `simple-facebook-plugin` directory to your `/wp-content/plugins` directory
1. Activate plugin in WordPress admin

**Customization**

1. In WordPress dashboard, go to **Appearance > Widgets**. 
1. Drag and Drop **SFP - Like Box** into your sidebar.
1. Click triangle near **SFP - Like Box** header.
1. Choose colorscheme, size and other options you like.
1. Enter your Facebook Page URL (not your personal page URL!).

**or**

Use `[sfp-like-box]` shortcode inside your post or page. This shortcode support all default parametrs:


* url - any Fan Page URL (not your personal page!)
* width - any number (e.g 250)
* height - any number (e.g 300)
* colorscheme - *light* or *dark*
* faces – *true* or *false*
* stream - *true* or *false*
* header - *true* or *false*
* local - valid language code (e.g. *en_US* or *es_MX*) see [.xml file](http://www.facebook.com/translations/FacebookLocales.xml "Facebook locales XML") with all Facebook locales


If you want Like Box *220 pixels width*, *dark color scheme* and *showing stream* you need to use it next way:

`[sfp-like-box width=220 colorscheme=dark stream=true]`

**or**

Use `sfp_like_box()` template tag in your theme files.

`<?php if ( function_exists("sfp_like_box") ) {
	$args = array(
		'url'			=> 'http://www.facebook.com/wordpress',
		'width'		=> '292',
		'faces'		=> false,
		'header'		=> false,
		'local'		=> 'en_US'
	);
	sfp_like_box( $args );
} ?>`

== Frequently Asked Questions ==

= I can get a box to display on the blog, but it contains the message “There was an error fetching the like box for the specified page”. What am I doing wrong? =

Like Box is only for Fan Pages and **not** for your personal page.

== Screenshots ==

1. Widget in the dashboard.
2. Widget on your site.
3. Shortcode inside your post.

== Changelog ==

= 1.2.2 =
* Option to show Like Box with no border changed to native Facebook data-show-border=false;

= 1.2.1 =
* Added option to show Like Box with no border;
* Added Norwegian(bokmal) locale to widget;

= 1.2 =
Plugin structure reorganized. Shortcode and template tag functionality added.

= 1.1 =
More than 20 Facebook Locales added.