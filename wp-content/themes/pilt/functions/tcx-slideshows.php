<?php function tcx_slideshows() { 
if (!isset($_REQUEST['settings-updated'])) {$_REQUEST['settings-updated'] = false;}

// Deleting Slideshow
if (isset($_GET['delete_slideshow'])) {

}

// Find current Slideshow
$slideshow_general = get_option('tcx_slideshow_general');
if ($_REQUEST['slideshow_id']) {
	$slideshow_id = $_REQUEST['slideshow_id'];
} else {
	$slideshow_id = $slideshow_general['last_active'];
}

// Go to Slideshow if preforming deletion
if ($slideshow_id == 1) {
	$slideshow_goto_ondelete = '1';
} else if ($slideshow_id == $slideshow_general['last_active']) {
	$slideshow_goto_ondelete = intval($slideshow_general['last_active'])-1;
} else {
	$slideshow_goto_ondelete = $slideshow_general['last_active'];
}

// Enable Media Library
if (function_exists('wp_enqueue_media')){
	wp_enqueue_script('media-upload');
	wp_enqueue_media();
}

/* debug
echo 'Last Active: ' . $slideshow_general['last_active'];
echo '<br/>Viewing: ' . $slideshow_id;
echo '<br/>Top ID: ' . $slideshow_general['top_id'];
echo '<br/>Goto if deleting: ' . $slideshow_goto_ondelete;*/
?>

<div class="wrap" id="tcx-slideshow">
	<div class="tcx-slideshow-inter-nav">
		<a href="admin.php?page=tcx_slideshows&amp;slideshow_id=<?php echo intval($slideshow_general['top_id'])+1?>" class="button-secondary">New Slideshow</a>
		<select id="tcx-slideshow-select" name="tcx-slideshow-select">
			<optgroup label="Edit Slideshow">
				<?php for ($i=1; $i<=$slideshow_general['top_id']; $i++) {
					$title = get_option('tcx_slideshow_'.$i);
					if(!empty($title)) {
						if ($i == $slideshow_id) {$selected = ' selected="selected"';} else {$selected = '';}
						echo '<option value="admin.php?page=tcx_slideshows&amp;slideshow_id=' . $i . '"' . $selected . '>' . $title['slideshow_name'] . '</option>';
					}
				}?>
			</optgroup>
		</select>
	</div>
<div id="icon-themes" class="icon32"></div>
<h2>Slideshow Management</h2>
<noscript class="error">Canopy Slideshow requires Javascript.</noscript>
<?php 
// Slideshow Deleted Notice
if ($_GET['delete_slideshow']) {
	echo '<div class="updated fade"><p><strong>Slideshow deleted</strong></p></div>';
}

// Slideshow Updated Notice
if (false !== $_REQUEST['settings-updated']) {
	echo '<div class="updated fade"><p><strong>Slideshow saved</strong></p></div>';
}

// Slideshow Compat Notice
if(!function_exists('wp_enqueue_media')) {
	echo '<div class="error">Canopy Slideshow requires Wordpress 3.5 or higher.</div>';
}

/* TODO: this notice, without flashing it to viewers
<div class="updated" id="js-compat">Canopy Slideshow requires a Javascript-capable browser.</div>*/?>

<br class="clear" />
	<form method="post" action="options.php">
		<?php $options = get_option('tcx_slideshow_'.$slideshow_id, tcx_slideshow_demo());
		settings_fields('tcx_slideshow_'.$slideshow_id); ?>
		<input name="slideshow_id" id="slideshow_id" type="hidden" value="<?php echo $slideshow_id;?>" />
		<input name="tcx_slideshow_<?php echo $slideshow_id;?>[top_id]" id="top_id" type="hidden" value="<?php echo $options[top_id];?>" />
		<div class="widget-liquid-left">

			<div id="widgets-left">
				<div id="titlediv">
			<input type="text" name="tcx_slideshow_<?php echo $slideshow_id;?>[slideshow_name]" id="title" value="<?php esc_attr_e($options['slideshow_name']); ?>">
		</div>
				<ul class="all-slides" id="slideshow-config">
					<?php
					if ($options['slides']) {
						foreach ($options[slides] as $slide) {
							echo '<li id="order_' . $slide['id'] . '" class="menu-item-handle tcx-sortable">
									<div class="expander"></div>
									<div class="gripper"></div>
									<img class="thumbnail" src="' . $slide['thumbnail'] . '" alt="" />
									<input name="tcx_slideshow_'.$slideshow_id.'[slides]['. $slide['id'] .'][id]" class="ready" type="hidden" value="' . $slide['id'] . '" />
									
									<input name="tcx_slideshow_'.$slideshow_id.'[slides]['. $slide['id'] .'][thumbnail]" class="ready thumbnail-url" type="hidden" value="' . $slide['thumbnail'] . '" />
									<input name="tcx_slideshow_'.$slideshow_id.'[slides]['. $slide['id'] .'][full_image]" class="ready full-image" type="hidden" value="' . $slide['full_image'] . '" />
									<a href="#" class="button-secondary tcx-slideshow-upload-button">Upload/Edit Image</a>
									<div class="delete"><a href="#" class="submitdelete deletion">Delete Slide</a> <div class="deletehider"><a href="#" class="confirmdelete">Confirm</a></div></div>
									<div class="tcx-slide-fields">
										<label for="slide_title"> Title</label> <input type="text" class="form-field title-field title-display" value="' . $slide['title'] . '" disabled="disabled" /><br/>
										<input name="tcx_slideshow_'.$slideshow_id.'[slides]['. $slide['id'] .'][title]" class="ready title-submit" type="hidden" value="' . $slide['title'] . '" />
										<label for="slide_description"> Description</label> <textarea rows="4" cols="40" name="tcx_slideshow_'.$slideshow_id.'[slides]['. $slide['id'] .'][description]" class="ready tcx-slide-description">' . $slide['description'] . '</textarea><br/>
										<div class="link-fields"><label for="slide_link"> Slide Link<span class="lightbox-option"><input type="checkbox" name="tcx_slideshow_'.$slideshow_id.'[slides]['. $slide['id'] .'][lightbox]" value="on"'.(($slide[lightbox]=="on")?'checked="checked"':"").'> Lightbox</span></label> <input type="text" name="tcx_slideshow_'.$slideshow_id.'[slides]['. $slide['id'] .'][link]" class="form-field link-field link" value="' . $slide['link'] . '" /></div>
									</div>
								</li>';
						}
					} ?>
				</ul>
				<br class="clear" />
				<a href="#" class="button-secondary" id="add_new_slide">Add New Slide</a>
				<br class="clear" />
				<h3>Inactive Slides</h3>
				<ul id="inactive-slides" class="all-slides">
					<?php
					if ($options['inactive_slides']) {
						foreach ($options['inactive_slides'] as $slide) {
							echo '<li id="order_' . $slide['id'] . '" class="menu-item-handle tcx-sortable">
									<div class="expander"></div>
									<div class="gripper"></div>
									<img class="thumbnail" src="' . $slide['thumbnail'] . '" alt="" />
									<input name="tcx_slideshow_'.$slideshow_id.'[inactive_slides]['. $slide['id'] .'][id]" class="ready" type="hidden" value="' . $slide['id'] . '" />
									
									<input name="tcx_slideshow_'.$slideshow_id.'[inactive_slides]['. $slide['id'] .'][thumbnail]" class="ready thumbnail-url" type="hidden" value="' . $slide['thumbnail'] . '" />
									<input name="tcx_slideshow_'.$slideshow_id.'[inactive_slides]['. $slide['id'] .'][full_image]" class="ready full-image" type="hidden" value="' . $slide['full_image'] . '" />
									<a href="#" class="button-secondary tcx-slideshow-upload-button">Upload/Edit Image</a>
									<div class="delete"><a href="#" class="submitdelete deletion">Delete Slide</a> <div class="deletehider"><a href="#" class="confirmdelete">Confirm</a></div></div>
									<div class="tcx-slide-fields">
										<label for="slide_title"> Title</label> <input type="text" class="form-field title-field title-display" value="' . $slide['title'] . '" disabled="disabled" /><br/>
										<input name="tcx_slideshow_'.$slideshow_id.'[inactive_slides]['. $slide['id'] .'][title]" class="ready title-submit" type="hidden" value="' . $slide['title'] . '" />
										<label for="slide_description"> Description</label> <textarea rows="4" cols="40" name="tcx_slideshow_'.$slideshow_id.'[inactive_slides]['. $slide['id'] .'][description]" class="ready tcx-slide-description">' . $slide['description'] . '</textarea><br/>
										<div class="link-fields"><label for="slide_link"> Slide Link<span class="lightbox-option"><input type="checkbox" name="tcx_slideshow_'.$slideshow_id.'[inactive_slides]['. $slide['id'] .'][lightbox]" value="on"'.(($slide[lightbox]=="on")?'checked="checked"':"").'> Lightbox</span></label> <input type="text" name="tcx_slideshow_'.$slideshow_id.'[inactive_slides]['. $slide['id'] .'][link]" class="form-field link-field link" value="' . $slide['link'] . '" /></div>
									</div>
								</li>';
						}
					} ?>
				</ul>
			</div>
		</div>
		<div class="widget-liquid-right">
			<div id="widgets-right">
				<div class="sidebar-name">
					<h3>Publish</h3>
				</div>
				<div class="widget-holder widgets-sortables submitbox">
					<div class="id">Embed Code: <code>[tcx_slideshow id="<strong><?php echo $slideshow_id;?></strong>"]</code></div>
					<input class="button-primary" type="submit" name="Save" value="Save Slideshow" id="submitbutton" />
					<div class="delete"><a href="#" class="slideshow-delete submitdelete deletion">Delete</a> <div class="slideshow-deletehider"><a href="admin.php?page=tcx_slideshows&amp;delete_slideshow=<?php echo $slideshow_id; ?>&amp;slideshow_id=<?php echo $slideshow_goto_ondelete; ?>" class="confirm-slideshow-delete submitdelete">Confirm</a></div></div>
					<br class="clear" />
				</div>
				<br class="clear" />

				<div class="sidebar-name">
					<h3>Appearance</h3>
				</div>
				<div class="widget-holder widgets-sortables postbox">
					<div class="inside">
						<div class="float-left">
						<label for="tcx-slideshow-mode">Mode</label><br/>
							<select id="tcx-slideshow-mode" name="tcx_slideshow_<?php echo $slideshow_id;?>[mode]">
								<option value="responsive"<?php if($options['mode'] == "responsive") {echo ' selected="selected"';}?>>Responsive</option>
								<option value="fixed"<?php if($options['mode'] == "fixed") {echo ' selected="selected"';}?>>Fixed Size</option>
							</select>
						</div>
						<div class="float-right" id="fixed-width-fields">
							<div class="float-left">
								<label for="width">Width</label><br/>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[width]" id="width" type="text" class="small-text px" value="<?php esc_attr_e($options['width']); ?>" /><span class="by">x</span>
							</div>
							<div class="float-left">
								<label for="height">Height</label><br/>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[height]" id="height" type="text" class="small-text px" value="<?php esc_attr_e($options['height']); ?>" />
							</div>
						</div>
						<div class="float-right" id="max-size-fields" style="display: none;">
							<div class="float-left">
								<label for="max_width">Width</label><br/>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[max_width]" id="max_width" type="text" class="small-text px" value="auto" disabled="disabled" /><span class="by">x</span>
							</div>
							<div class="float-left">
								<label for="max_height">Max Height</label><br/>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[max_height]" id="max_height" type="text" class="small-text px" value="<?php esc_attr_e($options['max_height']); ?>" />
							</div>
						</div>
						<br class="clear" />
						<label for="tcx-slideshow-slidelinks" class="slide-link-label">Slide Links</label>
						<br class="clear" />
							<select id="tcx-slideshow-slidelinks" name="tcx_slideshow_<?php echo $slideshow_id;?>[slidelinks]">
								<option value="off"<?php if($options['slidelinks'] == "off") {echo ' selected="selected"';}?>>No Links</option>
								<option value="lightbox"<?php if($options['slidelinks'] == "lightbox") {echo ' selected="selected"';}?>>Image Lightbox</option>
								<option value="custom"<?php if($options['slidelinks'] == "custom") {echo ' selected="selected"';}?>>Custom Links</option>
							</select>
					</div>
					<div class="tcx-slideshow-subsection-expander" style="margin-top: 20px"><em>Controller</em></div>
					<ul class="tcx-slideshow-subsection">
						<li>
							<label for="tcx-slideshow-controller">Pager</label>
							<select id="tcx-slideshow-controller" name="tcx_slideshow_<?php echo $slideshow_id;?>[controller]">
								<option value="dots"<?php if($options['controller'] == "dots") {echo ' selected="selected"';}?>>On</option>
								<?php /*<option value="numbers"<?php if($options['controller'] == "numbers") {echo ' selected="selected"';}?>>Numbers</option>
								<option value="thumbnails"<?php if($options['controller'] == "numbers") {echo ' selected="selected"';}?>>Thumbnails</option>*/ ?>
								<option value="off"<?php if($options['controller'] == "off") {echo ' selected="selected"';}?>>Off</option>
							</select>
						</li>
						<li>
							<label for="tcx-slideshow-arrows">Navigation Arrows</label>
							<select id="tcx-slideshow-arrows" name="tcx_slideshow_<?php echo $slideshow_id;?>[arrows]">
								<option value="on"<?php if($options['arrows'] == "on") {echo ' selected="selected"';}?>>On</option>
								<option value="off"<?php if($options['arrows'] == "off") {echo ' selected="selected"';}?>>Off</option>
							</select>
						</li>
					</ul>

					<div class="tcx-slideshow-subsection-expander"><em>Titles</em></div>
					<ul class="tcx-slideshow-subsection">
						<li>
							<label for="tcx-slideshow-title_display">Visibility</label>
							<select id="tcx-slideshow-title_display" name="tcx_slideshow_<?php echo $slideshow_id;?>[title_display]">
								<option value="on"<?php if($options['title_display'] == "on") {echo ' selected="selected"';}?>>On</option>
								<option value="off"<?php if($options['title_display'] == "off") {echo ' selected="selected"';}?>>Off</option>
							</select>
						</li>
						<li>
							<label for="tcx-slideshow-title_tag">HTML Tag</label>
							<select id="tcx-slideshow-title_tag" name="tcx_slideshow_<?php echo $slideshow_id;?>[title_tag]">
								<option value="h2"<?php if($options['title_tag'] == "h2") {echo ' selected="selected"';}?>>Heading 2</option>
								<option value="h3"<?php if($options['title_tag'] == "h3") {echo ' selected="selected"';}?>>Heading 3</option>
								<option value="h4"<?php if($options['title_tag'] == "h4") {echo ' selected="selected"';}?>>Heading 4</option>
								<option value="h5"<?php if($options['title_tag'] == "h5") {echo ' selected="selected"';}?>>Heading 5</option>
								<option value="span"<?php if($options['title_tag'] == "span") {echo ' selected="selected"';}?>>Span</option>
							</select>
						</li>
						<li>
							<label for="title_class">CSS Class</label>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[title_class]" id="title_class" type="text" value="<?php esc_attr_e($options['title_class']); ?>" />
						</li>
					</ul>

					<div class="tcx-slideshow-subsection-expander"><em>Description</em></div>
					<ul class="tcx-slideshow-subsection">
						<li>
							<label for="tcx-slideshow-description_display">Visibility</label>
							<select id="tcx-slideshow-description_display" name="tcx_slideshow_<?php echo $slideshow_id;?>[description_display]">
								<option value="on"<?php if($options['description_display'] == "on") {echo ' selected="selected"';}?>>On</option>
								<option value="off"<?php if($options['description_display'] == "off") {echo ' selected="selected"';}?>>Off</option>
							</select>
						</li>
						<li>
							<label for="tcx-slideshow-description_tag">HTML Tag</label>
							<select id="tcx-slideshow-description_tag" name="tcx_slideshow_<?php echo $slideshow_id;?>[description_tag]">
								<option value="p"<?php if($options['description_tag'] == "p") {echo ' selected="selected"';}?>>Paragraph</option>
								<option value="span"<?php if($options['description_tag'] == "span") {echo ' selected="selected"';}?>>Span</option>
								<option value="h2"<?php if($options['description_tag'] == "h2") {echo ' selected="selected"';}?>>H2</option>
							</select>
						</li>
						<li>
							<label for="title_class">CSS Class</label>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[description_class]" id="description_class" type="text" value="<?php esc_attr_e($options['description_class']); ?>" />
						</li>
					</ul>
				</div>

				<div class="sidebar-name">
					<h3>Animation</h3>
				</div>
				<div class="widget-holder widgets-sortables postbox tcx-slideshow-animation">
					<div class="inside">
						<div class="float-left">
							<label for="tcx-slideshow-transition">Transition</label><br/>
							<select id="tcx-slideshow-transition" name="tcx_slideshow_<?php echo $slideshow_id;?>[transition]">
								<option value="fade"<?php if($options['transition'] == "fade") {echo ' selected="selected"';}?>>Fade</option>
								<option value="slide"<?php if($options['transition'] == "slide") {echo ' selected="selected"';}?>>Slide</option>
								<option value="carousel"<?php if($options['transition'] == "carousel") {echo ' selected="selected"';}?>>Carousel</option>
							</select>
						</div>
						<div class="float-right" style="margin-left: 10px;">
							<label for="tcx-slideshow-delay">Delay</label><br/>
							<input id="tcx-slideshow-delay" name="tcx_slideshow_<?php echo $slideshow_id;?>[delay]" type="text" class="small-text ms" value="<?php esc_attr_e($options['delay']); ?>" />
						</div>
						<div class="float-right">
							<label for="tcx-slideshow-speed">Speed</label><br/>
							<input id="tcx-slideshow-speed" name="tcx_slideshow_<?php echo $slideshow_id;?>[speed]" type="text" class="small-text ms" value="<?php esc_attr_e($options['speed']); ?>" />
						</div>
						<br class="clear" />
					</div>	
						<div class="tcx-slideshow-subsection-expander" id="carousel-options-button"><em>Carousel Options</em></div>
						<ul class="tcx-slideshow-subsection" id="carousel-options-panel">
							<li>
								<label for="tcx-slideshow-carousel-itemwidth">Item Width</label>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[carousel_itemwidth]" id="tcx-slideshow-carousel_itemwidth" type="text" class="small-text px" value="<?php esc_attr_e($options['carousel_itemwidth']); ?>" />
							</li>
							<li>
								<label for="tcx-slideshow-carousel_min">Minimum Slides Visible</label>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[carousel_min]" id="tcx-slideshow-carousel_min" type="number" step="1" min="1" class="small-text" value="<?php esc_attr_e($options['carousel_min']); ?>" />
							</li>
							<li>
								<label for="tcx-slideshow-carousel_max">Maximum Slides Visible</label>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[carousel_max]" id="tcx-slideshow-carousel_max" type="number" step="1" min="1" class="small-text" value="<?php esc_attr_e($options['carousel_max']); ?>" />
							</li>
							<li>
								<label for="tcx-slideshow-carousel_move">Slides Per Move</label>
								<input name="tcx_slideshow_<?php echo $slideshow_id;?>[carousel_move]" id="tcx-slideshow-carousel_move" type="number" step="1" min="1" class="small-text" value="<?php esc_attr_e($options['carousel_move']); ?>" />
							</li>
						</ul>
						<div class="tcx-slideshow-subsection-expander"><em>Advanced</em></div>
						<ul class="tcx-slideshow-subsection">
							<li>
								<label for="tcx-slideshow-autoplay">Autoplay</label>
								<select id="tcx-slideshow-autoplay" name="tcx_slideshow_<?php echo $slideshow_id;?>[autoplay]">
									<option value="on"<?php if($options['autoplay'] == "on") {echo ' selected="selected"';}?>>On</option>
									<option value="off"<?php if($options['autoplay'] == "off") {echo ' selected="selected"';}?>>Off</option>
								</select>
							</li>
							<li>
								<label for="tcx-slideshow-easing">Easing</label>
								<select id="tcx-slideshow-easing" name="tcx_slideshow_<?php echo $slideshow_id;?>[easing]">
									<option value="swing"<?php if($options['easing'] == "swing") {echo ' selected="selected"';}?>>Swing</option>
									<option value="linear"<?php if($options['easing'] == "linear") {echo ' selected="selected"';}?>>Linear</option>
								</select>
							</li>
							<li>
								<label for="tcx-slideshow-hoverpause">Pause on hover</label>
								<select id="tcx-slideshow-hoverpause" name="tcx_slideshow_<?php echo $slideshow_id;?>[hoverpause]">
									<option value="on"<?php if($options['hoverpause'] == "on") {echo ' selected="selected"';}?>>On</option>
									<option value="off"<?php if($options['hoverpause'] == "off") {echo ' selected="selected"';}?>>Off</option>
								</select>
							</li>
							<li>
								<label for="tcx-slideshow-random">Randomize Slides</label>
								<select id="tcx-slideshow-random" name="tcx_slideshow_<?php echo $slideshow_id;?>[random]">
									<option value="on"<?php if($options['random'] == "on") {echo ' selected="selected"';}?>>On</option>
									<option value="off"<?php if($options['random'] == "off") {echo ' selected="selected"';}?>>Off</option>
								</select>
							</li>
							<li>
								<label for="tcx-slideshow-initial_delay">Initial Delay</label>
								<input id="tcx-slideshow-initial_delay" name="tcx_slideshow_<?php echo $slideshow_id;?>[initial_delay]" type="text" class="small-text ms" value="<?php esc_attr_e($options['initial_delay']); ?>" />
							</li>
						</ul>    
						<br class="clear" />
				</div>
				<br class="clear" />

			</div>
		</div>
		
	</form>
	
</div>

<?php } ?>