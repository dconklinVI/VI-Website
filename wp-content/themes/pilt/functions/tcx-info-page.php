<?php function tcx_info_page() { ?>

<div class="wrap">
<h2>Theme Info</h2>
	
	<h3 class="title">Shortcodes</h3>
	<table class="form-table">
	
		<tr valign="top">
		<th scope="row"><img src="/wp-content/themes/the-beck/images/admin-tutorial-button.png" alt=""/></th>
		<td valign="middle">
			<strong>[button link="/about-us/" color="orange" title="About Us"]</strong>
			<br/>This shortcode will generate a button. Available color options are <span class="blue">blue</span>, <span class="orange">orange</span>, and <span class="green">green</span>.</td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><img src="/wp-content/themes/the-beck/images/admin-tutorial-lightbox.png" alt=""/></th>
		<td valign="middle">
			<strong>[lightbox link="/about-us/" color="blue" title="Contact Us"]</strong>
			<br/>This shortcode will generate a button, whose link will open in a lightbox. Available color options are <span class="blue">blue</span>, <span class="orange">orange</span>, and <span class="green">green</span>.</td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><img src="/wp-content/themes/the-beck/images/admin-tutorial-separator.png" alt=""/></th>
		<td valign="middle">
			<strong>[separator]</strong>
			<br/>This shortcode will create a content separator.</td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><img src="/wp-content/themes/the-beck/images/admin-tutorial-accordion.png" alt=""/></th>
		<td valign="middle">
			<strong>[accordion title="Click for more info"]Lorem ipsum dolor sit amet...[/accordion]</strong>
			<br/>This shortcode will create an "accordion". Accordions are used to hide and show content blocks.</td>
		</tr>
	</table>
	
	
	<h3 class="title">Styles</h3>
	<table class="form-table" id="demo">
	
		<tr valign="top">
		<th scope="row" width="220"><h2>Header&nbsp;2</h2></th>
		<td valign="middle">
			<strong>Header 2</strong>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row" width="220"><h3>Header&nbsp;3</h3></th>
		<td valign="middle">
			<strong>Header 3</strong>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row" width="220"><h4>Header&nbsp;4</h4></th>
		<td valign="middle">
			<strong>Header 4</strong>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row" width="220" class="example"><p>Paragraphs</p></th>
		<td valign="middle">
			<strong>Paragraphs</strong>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row" width="220" class="example"><a href="#">Links</a></th>
		<td valign="middle">
			<strong>Links</strong>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row" width="220" class="example"><ul><li>List Items</li><li>List Items</li></ul></th>
		<td valign="middle">
			<strong>List Items</strong>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row" width="220" class="example"><strong>Strong</strong></th>
		<td valign="middle">
			<strong>Strong</strong>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row" width="220" class="example"><em>Emphasis</em></th>
		<td valign="middle">
			<strong>Emphasis</strong>
		</td>
		</tr>
		
	</table>
		
</div>

<?php } ?>