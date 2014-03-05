<?php
/**
	Copyright (C) 2013  ClickTale Ltd.

    Author: ClickTale 
	URI: http://www.clicktale.com/
	Description: Administrative options for ClickTale plugin.
	Copyright: 2013 ClickTale Ltd.
	
	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 
 */
?>
<?php 

if (!class_exists("ClickTalePlugin")) {
	class ClickTalePlugin {
		function ClickTalePlugin() { //constructor	
			$this->add_actions();
		}

		function add_actions()
		{
			add_action('admin_menu', array(&$this, 'clicktale_add_options_page'));
			add_action('wp_head', array(&$this, 'add_YUI_libs'));
			add_action('wp_footer', array(&$this, 'put_code_before_clicktale_script'));

			// insert ClickTale script (top and bottom scripts)
			if ((get_option('ct_cookie_name') == null) || ($_COOKIE[get_option('ct_cookie_name')] != get_option('ct_cookie_value'))) {
				add_action('wp_head', array(&$this, 'put_top_script'));
				add_action('wp_footer', array(&$this, 'put_bottom_script'));
				add_action('wp_footer', array(&$this, 'addAllTags'));
			}

			add_action('wp_footer', array(&$this, 'put_code_after_clicktale_script'));
		} 		

		function add_YUI_libs() {
			if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off")
			{
				echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script>';
			}
			else
			{
				echo '<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script>';
			}
		}

		function put_top_script() {
			echo get_option('ct_top');
		}

		function put_bottom_script() {
			echo get_option('ct_bottom');
		}

		function clicktale_add_options_page()
		{ 
			$dirname = basename(dirname(__FILE__));
			add_submenu_page('plugins.php', __('ClickTale Options'), __('ClickTale Options'), 'manage_options', $dirname.'/options.php');
		}

		function put_code_before_clicktale_script() {
			echo get_option('ct_code');
		}

		function put_code_after_clicktale_script() {
			echo get_option('ct_code_after_clicktale');
		}

		// These functions assign an event to an element. Each function has it's own tag name.
		// The elemnets are searched using the attribute name and the regex of the value they should have. 

		// $sTagName - Name of the tag that will be assigned in clicktale (do not confuse with html tag).
		// $sEvent - The event to hook
		// $sAttributeName - According to this attribute an element is searched.
		// $rgxAttributeVal - The search parameter.

		function addTagToForm($sTagName, $sEvent, $sAttributeName, $rgxAttributeVal) {
			echo "
			<script type=\"text/javascript\">
			/*<![CDATA[*//*---->*/
			for (var i in document.forms) {
				if (typeof(document.forms[i].$sAttributeName) != 'unknown' && document.forms[i].$sAttributeName != null && document.forms[i].$sAttributeName.search($rgxAttributeVal) != -1) { 
					var oElement = document.forms[i];
					function fn(e) { ClickTaleTag(\"$sTagName\");}
					YAHOO.util.Event.addListener(oElement, \"$sEvent\", fn);  
				}
			}
			/*--*//*]]>*/
			</script>
			";
		}

		function addTagToLink($sTagName, $sEvent, $sAttributeName, $rgxAttributeVal) {
			echo "
			<script type=\"text/javascript\">
			/*<![CDATA[*//*---->*/
			for (var i in document.links) {
				if (typeof(document.links[i].$sAttributeName) != 'unknown' && document.links[i].$sAttributeName != null && document.links[i].$sAttributeName.search($rgxAttributeVal) != -1) {
					var oElement = document.links[i];
					function fn(e) { ClickTaleTag(\"$sTagName\"); }
					YAHOO.util.Event.addListener(oElement, \"$sEvent\", fn);  
				}
			} 
			/*--*//*]]>*/
			</script>
			";
		}

		function addTagToButton($sTagName, $sEvent, $sAttributeName, $rgxAttributeVal) {
			echo "
			<script type=\"text/javascript\">
			/*<![CDATA[*//*---->*/
			inputs = document.getElementsByTagName(\"input\");
			for (var i in inputs) {
				if (typeof(inputs[i].$sAttributeName) != 'unknown' && inputs[i].$sAttributeName != null && (inputs[i].type == \"button\" || inputs[i].type == \"submit\") && inputs[i].$sAttributeName.search($rgxAttributeVal) != -1) {
					var oElement = inputs[i];
					function fn(e) { ClickTaleTag(\"$sTagName\"); }
					YAHOO.util.Event.addListener(oElement, \"$sEvent\", fn);  
				}
			} 
			/*--*//*]]>*/
			</script>
			";
		}

		function addAllTags()
		{
			$this->addTagToButton(get_option('ct_search_tg'), "click", "value", "/^Search\$/");
			$this->addTagToForm(get_option('ct_comment_tg'), "submit", "action", "/\\/wp-comments-post\\.php\$/");
			$this->addTagToLink(get_option('ct_rss_tg'), "click", "href", "/(feed=rss2\$)|(feed\$)|(feed\/\$)/");
			$this->addTagToLink(get_option('ct_cmntrss_tg'), "click", "href", "/(feed=comments-rss2\$)|(comments\/feed\$)|(comments\/feed\/\$)/");
			$this->addTagToLink(get_option('ct_rssrt_tg'), "contextmenu", "href", "/(feed=rss2\$)|(feed\$)|(feed\/\$)/");
			$this->addTagToLink(get_option('ct_cmntrssrt_tg'), "contextmenu", "href", "/(feed=comments-rss2\$)|(comments\/feed\$)|(comments\/feed\/\$)/");
		}		
	}
}

if (class_exists("ClickTalePlugin")) {
	$clicktalePlugin = new ClickTalePlugin();
}

?>
