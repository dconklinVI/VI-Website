=== Just A Tweet ===
Contributors: RyanNutt
Tags: twitter, tweet
Requires at least: 2.7
Tested up to: 3.1
Stable tag: 0.3.1

Add a function to get the most recent Tweet from a feed

== Description ==

Adds a `just_a_tweet` function and short code that allows you to display only 
the most recent Tweet from a Twitter feed.

This came about because I wanted a way to display my most recent tweet in the 
header of my site. Looking through dozens of Twitter plugins I couldn't find
one that just got the most recent tweet without too much extraneous HTML and
also cached the request. This function does both.

For more info, visit <a href="http://www.nutt.net/tag/just-a-tweet/">Nutt.net</a> or
my <a href="http://twitter.com/RyanNutt">Twitter feed</a>.

== Installation ==

1. Upload `just-a-tweet` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php just_a_tweet('username'); ?>` in your templates

The function `just_a_tweet` takes up to 4 parameters

$twitterUser - Required - The name of the Twitter user you want to request

$cacheAge - Optional - Number of minutes after which the cache is considered stale. Defaults to 5

$forceRefresh - Optional - If true then the cache is not used, although it is
still filled. Defaults to false

$echo - Optional - Whether the function should also echo out the HTML built. Either
way the HTML is returned. Defaults to true

= Using the shortcode = 
There is also a shortcode just_a_tweet that you can use inside your posts and pages.

[ just_a_tweet twitteruser=yourtwitter ] will pull the most recent tweet from 
whatever Twitter account you pass as `yourtwitter`. 

== Frequently Asked Questions ==
None yet


== Changelog ==

= 0.3.1 =
* No real change, just updating to switch to a new site

= 0.3 =
* Switched to using a newer Twitter API call
* Wasn't working if latest tweet was a retweet. Would cause an error on the first
page view and then a JSON error after it was pulled from cache. 

= 0.2 = 
* Added just_a_tweet short code so you can use this inside a post or page without
having to enable PHP code.

= 0.1 =
* Initial Release

== Upgrade Notice ==

= 0.2 = 
Nothing to upgrade, just added shortcode

= 0.1 =
First release, nothing to upgrade