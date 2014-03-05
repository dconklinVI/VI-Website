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
/*
Author: ClickTale 
URI: http://www.clicktale.com/
Description: Administrative options for ClickTale plugin.
Copyright: 2013 ClickTale Ltd.
*/

$location =  "?".$_SERVER["QUERY_STRING"]; //get_option('siteurl') . '/wp-admin/admin.php?page=ClickTale/options.php';

add_option('ct_top', __('', 'clicktale'));
add_option('ct_bottom', __('', 'clicktale'));
add_option('ct_cookie_name', __('', 'clicktale'));
add_option('ct_cookie_value', __('', 'clicktale'));
add_option('ct_code_before_clicktale', __('', 'clicktale'));
add_option('ct_code_after_clicktale', __('', 'clicktale'));

add_option('ct_search_tg', __('', 'clicktale'));
add_option('ct_comment_tg',__('', 'clicktale'));
add_option('ct_rssrt_tg', __('', 'clicktale'));
add_option('ct_rss_tg', __('', 'clicktale'));
add_option('ct_cmntrssrt_tg', __('', 'clicktale'));
add_option('ct_cmntrss_tg', __('', 'clicktale'));

$ct_top = get_option('ct_top');
$ct_bottom = get_option('ct_bottom');
$ct_cookie_name = get_option('ct_cookie_name');
$ct_cookie_value = get_option('ct_cookie_value');
$ct_code_before_clicktale = get_option('ct_code');
$ct_code_after_clicktale = get_option('ct_code_after_clicktale');

$ct_search_tg = get_option('ct_search_tg');
$ct_comment_tg = get_option('ct_comment_tg');
$ct_rssrt_tg = get_option('ct_rssrt_tg');
$ct_rss_tg = get_option('ct_rss_tg');
$ct_cmntrssrt_tg = get_option('ct_cmntrssrt_tg');
$ct_cmntrss_tg = get_option('ct_cmntrss_tg');

if (isset($_POST['ct_submit'])) 
{    
	$ct_top = stripslashes($_POST['ct_top']);
	$ct_bottom = stripslashes($_POST['ct_bottom']);
	$ct_cookie_name = $_POST['ct_cookie_name'];
	$ct_cookie_value = $_POST['ct_cookie_value'];
	$ct_code_before_clicktale = stripslashes($_POST['ct_code_before_clicktale']);
	$ct_code_after_clicktale = stripslashes($_POST['ct_code_after_clicktale']);

	$ct_search_tg = $_POST['ct_search_tg'];
	$ct_comment_tg = $_POST['ct_comment_tg'];
	$ct_rssrt_tg = $_POST['ct_rssrt_tg'];
	$ct_rss_tg = $_POST['ct_rss_tg'];
	$ct_cmntrssrt_tg = $_POST['ct_cmntrssrt_tg'];
	$ct_cmntrss_tg = $_POST['ct_cmntrss_tg'];
}

// Set Default Values
if ($ct_cookie_name == null) {$ct_cookie_name = "WRUID";}
if ($ct_cookie_value == null) {$ct_cookie_value = "0";}

if ($ct_search_tg == null) {$ct_search_tg = "Search";}
if ($ct_comment_tg == null) {$ct_comment_tg = "Comment";}
if ($ct_rssrt_tg == null) {$ct_rssrt_tg  = "RSS-Rightclick";}
if ($ct_rss_tg == null) {$ct_rss_tg = "RSS";}
if ($ct_cmntrssrt_tg == null) {$ct_cmntrssrt_tg = "Comments-RSS-Rightclick";}
if ($ct_cmntrss_tg == null) {$ct_cmntrss_tg = "Comments-RSS";}


update_option('ct_top', $ct_top);
update_option('ct_bottom', $ct_bottom);
update_option('ct_cookie_name', $ct_cookie_name);
update_option('ct_cookie_value', $ct_cookie_value);
update_option('ct_code', $ct_code_before_clicktale);
update_option('ct_code_after_clicktale', $ct_code_after_clicktale);

update_option('ct_search_tg', $ct_search_tg);
update_option('ct_comment_tg', $ct_comment_tg);
update_option('ct_rss_tg', $ct_rss_tg);
update_option('ct_rssrt_tg', $ct_rssrt_tg);
update_option('ct_cmntrssrt_tg', $ct_cmntrssrt_tg);
update_option('ct_cmntrss_tg', $ct_cmntrss_tg);

?>
<div class="wrap">

<?php
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off")
{
	echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/yui/2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script>';
}
else
{
	echo '<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script>';
}
?>

<h2><?php _e('ClickTale Plugin Options') ?></h2>

<p> <form name="form1" method="post" action="<?php echo $location ?>&amp;updated=true">
	<input type="submit" name="ct_submit" value="<?php _e('Update Options', 'clicktale') ?> &raquo;" /><br>

	<?php _e('Recording ratio (between 0 and 1): ') ?><input type="text" id="ct_ratio"><br>
	
	<?php _e('Top Script:') ?><br>
	<textarea name="ct_top" id="ct_top" style="width: 80%;" rows="9" wrap="virtual" cols="50"><?php echo $ct_top; ?></textarea>
	<br>
	<?php _e('Bottom Script:') ?><br>
	<textarea name="ct_bottom" id="ct_bottom" style="width: 80%;" rows="9" wrap="virtual" cols="50" ><?php echo $ct_bottom; ?></textarea><br>

	<table border=0>
	<tr>
		<td colspan="2"><h5><?php _e('Tag names:') ?></h5></td>
    <td style="padding-left: 10em;">
      <h5>
        <?php _e('Enabling/Disabling ClickTale for this user/domain:') ?>
      </h5>
    </td>
  </tr>
  <tr>
    <td>Search button clicked: </td>
    <td>
      <input type="text" id="ct_search_tg" name="ct_search_tg" value="<?php echo $ct_search_tg; ?>">
    </td>
    <td rowspan="6" valign="top" style="padding-left: 10em;">
			ClickTale uses cookies to differentiate visitors and maintain their states.  <br>
			<button onclick="WRcreateCookie('WRUID',WRGetRandToken()+'.'+WRGetRandToken(),3650);">Force recording</button> 
			<b>Force</b> yourself to be recorded on the current domain. <br>
			<button onclick="WRcreateCookie('WRUID','0',3650);">Disable for 10 years</button> 
			<b>Block</b> yourself from being recorded on the current domain.<br>
			<button onclick="WRcreateCookie('WRUID','',-1);">Reset</button> 
			<b>Reset</b> the state so that ClickTale will be forced to re-classify the user on his next recorded session.<br>
			<br>
			You might need to do this separately for each browser and domain that you wish to use.<br>
			For more information, visit our <a href="http://forums.clicktale.com/">forums</a>.
		</td>
	</tr><tr>
		<td>Add comment button clicked: </td>
		<td> <input type="text" id="ct_comment_tg" name="ct_comment_tg" value="<?php echo $ct_comment_tg; ?>"></td>
	</tr><tr>
		<td>RSS link left click: </td>
		<td> <input type="text" id="ct_rss_tg" name="ct_rss_tg" value="<?php echo $ct_rss_tg; ?>"></td>
	</tr><tr>
		<td>RSS link right click: </td>
		<td> <input type="text" id="ct_rssrt_tg" name="ct_rssrt_tg" value="<?php echo $ct_rssrt_tg; ?>"></td>
	</tr><tr>
		<td>Comments-RSS link left click: </td>
		<td> <input type="text" id="ct_cmntrss_tg" name="ct_cmntrss_tg" value="<?php echo $ct_cmntrss_tg; ?>"></td>
	</tr><tr>
		<td>Comments-RSS link right click: </td>
		<td> <input type="text" id="ct_cmntrssrt_tg" name="ct_cmntrssrt_tg" value="<?php echo $ct_cmntrssrt_tg; ?>"></td>
	</tr>
	</table>

	<h5><?php _e('Advanced features:') ?></h5>
	<?php _e('"Do not process cookie" name:') ?> <input type="text" id="ct_cookie_name" name="ct_cookie_name" value="<?php echo $ct_cookie_name ?>"><br>
	<?php _e('"Do not process cookie" value:') ?> <input type="text" id="ct_cookie_value" name="ct_cookie_value" value="<?php echo $ct_cookie_value ?>"><br>
	<br>

	<h5><?php _e('Optional features:') ?></h5>
	<a id="ct_code_before_clicktale_toggle" href="#"><?php _e('Any script code to be inserted to the blog BEFORE ClickTale bottom script:') ?></a>
	<br>
	<textarea name="ct_code_before_clicktale" id="ct_code_before_clicktale" style="width: 80%; display:none;" rows="9" wrap="virtual" cols="50" ><?php echo $ct_code_before_clicktale; ?></textarea>
	<br><br>
		
	<a id="ct_code_after_clicktale_toggle" href="#"><?php _e('Any script code to be inserted to the blog AFTER ClickTale bottom script:') ?></a>
	<br>
	<textarea name="ct_code_after_clicktale" id="ct_code_after_clicktale" style="width: 80%; display:none;" rows="9" wrap="virtual" cols="50"><?php echo $ct_code_after_clicktale; ?></textarea>
	<br><br>
	
	<input type="submit" name="ct_submit" value="<?php _e('Update Options', 'clicktale') ?> &raquo;" />
</form>
</p>

<script type="text/javascript">

(function() {

	var YDom = YAHOO.util.Dom,
		YEvent = YAHOO.util.Event;
	var $ = YDom.get;

	if ($("ct_code_before_clicktale").value != "")
		YDom.setStyle("ct_code_before_clicktale", "display", "block");
	if ($("ct_code_after_clicktale").value != "")
		YDom.setStyle("ct_code_after_clicktale", "display", "block");

	
	function ToggleCodeAreaBinding(codeID, buttonID) {
	
		function bind() {
			YEvent.addListener(buttonID, "click", function(ev) {
				YEvent.preventDefault(ev);
				var curDisplay = YDom.getStyle(codeID, "display");
				if(curDisplay == "none") {
					YDom.setStyle(codeID, "display", "");
				} else {
					YDom.setStyle(codeID, "display", "none");
				}
			});
		}
	
		YEvent.onAvailable(codeID, function() {
			YEvent.onAvailable(buttonID, bind);
		});
	}

	ToggleCodeAreaBinding("ct_code_before_clicktale", "ct_code_before_clicktale_toggle");
	ToggleCodeAreaBinding("ct_code_after_clicktale", "ct_code_after_clicktale_toggle");



})();



</script>


<script type="text/javascript"> 


// cookie stuff 
function WRcreateCookie(name,value,days) 
{ 
   // figure our the domain 
   var d=document.domain; 
   if(d.search(/www\.\w+\.\w+/i)==0) 
      d=d.substring(4, d.length); 
   else 
      d=false; 

   // write the cookie 
   if (days) 
   { 
      var date = new Date(); 
      date.setTime(date.getTime()+(days*86400000)); 
      var expires = "; expires="+date.toGMTString(); 
   } 
   else var expires = ""; 
   document.cookie = name+"="+value+expires+"; path=/;"+(d?" domain="+d+";":""); 
   window.alert('Done');
} 

function WRGetRandToken() 
{ 
   return Math.floor(Math.random()*2147483647); // stay bellow max signed long 
} 

</script> 

<script type="text/javascript">

var ct_pid = document.getElementById("ct_pid");
var ct_ratio = document.getElementById("ct_ratio");
var ct_top = document.getElementById("ct_top");
var ct_bottom = document.getElementById("ct_bottom");

createFieldSync (ct_bottom, ct_ratio, /(ClickTale\(\d+\s*,\s*)([^,\)]*)((?:,.+)?\))/gi);
//createFieldSync (ct_bottom, ct_ratio, /(ClickTale\(\d+\s*,\s*)(\d*\.?\d*)(\))/gi); // Allows only numbers (1, 0.3, 3.2 etc.) or nothing. Buggish when user accidently types a letter.



// majorField - The that field only part of it should be changed.
// minorField - The field that is being changed fully.
// rgx - The regexp that defines how these should sync. It should contain three atoms where the middle is the one that will be changed. Example: (ClickTale\(\d+\s*,\s*)(\d+)(\))/gi
function createFieldSync (majorField, minorField, rgx) {
	
	function fnUpdateMajor(e) {majorField.value = majorField.value.replace(rgx , "$1"+minorField.value+"$3");}
	function fnUpdateMinor(e) {
		var temp = rgx.exec(majorField.value);	
		if (temp != null) minorField.value = temp[0].replace(rgx, "$2");
	}
	
	YAHOO.util.Event.addListener(majorField, "keyup", fnUpdateMinor);  
	YAHOO.util.Event.onDOMReady(fnUpdateMinor);
	
	YAHOO.util.Event.addListener(minorField, "keyup", fnUpdateMajor);  
	YAHOO.util.Event.onDOMReady(fnUpdateMajor);
}
</script>
<br clear="all">
(c) 2013 ClickTale Ltd.
</div>
