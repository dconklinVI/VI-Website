<?php
/* Copyright 2011 Ryan Nutt - Aelora Web Services LLC
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public Licese, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation,Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

/*
  Plugin Name:  Just A Tweet
  Plugin URI:   http://www.nutt.net/tag/just-a-tweet/
  Description:  Adds a function to display the last Tweet from a user
  Version:      0.3.1
  Author:       Ryan Nutt
  Author URI:   http://www.nutt.net
  License: 	GPL2
 */

add_shortcode('just_a_tweet', 'just_a_tweet_shortcode'); 

/**
 * Output the latest tweet from the user passed
 * @param String $twitterUser
 * @param int $cacheAge     Number of minutes before the cache is considered stale
 * @param boolean $forceRefresh 
 */
function just_a_tweet($twitterUser, $cacheAge=5, $forceRefresh=false, $echo=true) {
    $opts = get_option('just_a_tweet', array());
    $twitterUser = strtolower($twitterUser); 
    
    
    /* Check if we should use the cache */
    if ($forceRefresh === false 
            && isset($opts['cache'][$twitterUser]['date']) 
            && $opts['cache'][$twitterUser]['date'] > date('U') - ($cacheAge * 60)  ) {
    
        if ($echo) {
            echo $opts['cache'][$twitterUser]['data'];
        }
        return $opts['cache'][$twitterUser]['data'];
        
    }
    $url = 'http://api.twitter.com/1.1/statuses/user_timeline/'.$twitterUser.'.json?count=1&include_rts=true';
    if (!class_exists('WP_Http')) {
        include_once(ABSPATH . WPINC . '/class-http.php'); 
    }
    
    $req = new WP_Http;
    $result = $req->request($url); 
    
    if (!isset($result['response']['code']) || $result['response']['code'] != '200') {
        $return = '<!-- Error: just_a_tweet could not retrieve from Twitter -->';
    }
    else {  
        $json = json_decode($result['body'], true);
        
        if (!$json || !is_array($json)) { 
            $return = '<!-- Error: just_a_tweet could not parse json -->'; 
        }
        else if (count($json) < 1) {
            $return = '<!-- Error: just_a_tweet - no records in Twitter -->';
        }
        else {
            $cacheData = '<span class="jat_wrapper">';
            
            $tweet = $json[0]['text'];
            
            $tweet = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", "<a href=\"http://$2$3\">$1$2$4</a>", $tweet);

    $tweet = preg_replace("/@(\w+)/", "<a href=\"http://twitter.com/\\1\">@\\1</a>", $tweet);

    $tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\">#\\1</a>", $tweet);
            
            $cacheData .= $tweet; 
            $cacheData .= '</span>';
            $return = $cacheData; 
            
            $opts['cache'][$twitterUser] = array(
                'data' => $cacheData,
                'date' => date('U')
            );
            
            update_option('just_a_tweet', $opts); 
            
        }
    }
    if ($echo) {
        echo $return;
    }
    return $return; 
}

/**
 * Shortcode handler
 * 
 * Thanks to Chaim @ http://chaimpeck.com for the suggestion and the code
 */
function just_a_tweet_shortcode($atts) {
    extract(shortcode_atts(array('twitteruser' => 'reliti'), $atts));
    return just_a_tweet($twitteruser, 5, false, false); 
}

?>