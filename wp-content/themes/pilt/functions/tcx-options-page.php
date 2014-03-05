<?php function tcx_options_page() {
if (!isset($_REQUEST['settings-updated'])) {$_REQUEST['settings-updated'] = false;}
?>

<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h2>Theme Settings</h2>
	<?php if (false !== $_REQUEST['settings-updated']) { ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php } ?>

	<form method="post" action="options.php">
		<?php $options = get_option('tcx_options', tcx_default_options());
		settings_fields('tcx_theme_options'); ?>
		
		<h3 class="title">Display Settings</h3>
		<table class="form-table">
			<tr valign="top"><th scope="row">Site-wide Settings</th>
				<td>
				<label for="admin_bar"><input id="admin_bar" name="tcx_options[admin_bar]" type="checkbox" value="1" <?php checked(true, $options['admin_bar']); ?> /> Hide the Wordpress admin toolbar on the front-end of the site from logged-in users.</label><br/>
				<label for="breadcrumb" class="disabled"><input id="breadcrumb" name="tcx_options[breadcrumb]" type="checkbox" value="1" <?php checked(true, $options['breadcrumb']); ?> disabled="disabled" /> Display breadcrumb trail at the top of content area, excluding the home page.</label><br/>
				<?php /* Removed until WP function human_time_diff() improves/prints more than just "days ago"
				<label for="human_readable_time"><input id="human_readable_time" name="tcx_options[human_readable_time]" type="checkbox" value="1" <?php checked(true, $options['human_readable_time']); ?> /> Use relative timestamps (eg. Posted 4 days ago) instead of dates.</label><br/>*/?>
				</td>
			</tr>
			<tr valign="top"><th scope="row">On Blog and Archive Pages</th>
				<td>
					<label for="blog_content">Previews display the post's <select id="blog_content" name="tcx_options[blog_content]">
						<option value="excerpt"<?php if($options['blog_content'] == "excerpt") {echo ' selected="selected"';}?>>excerpt</option>
						<option value="full"<?php if($options['blog_content'] == "full") {echo ' selected="selected"';}?>>full content</option></select></label><br/>
					<label for="pagination">When paginating, use a <select id="pagination" name="tcx_options[pagination]">
						<option value="full"<?php if($options['pagination'] == "full") {echo ' selected="selected"';}?>>full (numerical)</option>
						<option value="simple"<?php if($options['pagination'] == "simple") {echo ' selected="selected"';}?>>simple (previous/next)</option>
					</select> menu to navigate between pages.</label><br/>
					<label for="blog_featured_images"><input id="blog_featured_images" name="tcx_options[blog_featured_images]" type="checkbox" value="1" <?php checked(true, $options['blog_featured_images']); ?> /> Display featured image thumbnail, when available, for each post.</label><br/>
					<label for="blog_postdate"><input id="blog_postdate" name="tcx_options[blog_postdate]" type="checkbox" value="1" <?php checked(true, $options['blog_postdate']); ?> /> Display posted date under title.</label><br/>
					<label for="blog_postmeta"><input id="blog_postmeta" name="tcx_options[blog_postmeta]" type="checkbox" value="1" <?php checked(true, $options['blog_postmeta']); ?> /> Display post meta</label> <select id="blog_postmeta_position" name="tcx_options[blog_postmeta_position]">
						<option value="before"<?php if($options['blog_postmeta_position'] == "before") {echo ' selected="selected"';}?>>before</option>
						<option value="after"<?php if($options['blog_postmeta_position'] == "after") {echo ' selected="selected"';}?>>after</option> 
					</select> <label for="blog_postmeta_position">each post.</label><br/>
					<label for="blog_separators"><input id="blog_separators" name="tcx_options[blog_separators]" type="checkbox" value="1" <?php checked(true, $options['blog_separators']); ?> /> Display separators between each post.</label><br/>
				</td>
			</tr>
			<tr valign="top"><th scope="row">On a Single Post Page</th>
				<td>
					<label for="single_navigation"><input id="single_navigation" name="tcx_options[single_navigation]" type="checkbox" value="1" <?php checked(true, $options['single_navigation']); ?> /> Display previous and next navigation links</label> <select id="single_navigation_position" name="tcx_options[single_navigation_position]">
					<option value="before"<?php if($options['single_navigation_position'] == "before") {echo ' selected="selected"';}?>>before</option>
					<option value="after"<?php if($options['single_navigation_position'] == "after") {echo ' selected="selected"';}?>>after</option>
					<option value="both"<?php if($options['single_navigation_position'] == "both") {echo ' selected="selected"';}?>>before and after</option> 
				</select> <label for="single_navigation_position">the post.</label><br/>
				<label for="single_postmeta"><input id="single_postmeta" name="tcx_options[single_postmeta]" type="checkbox" value="1" <?php checked(true, $options['single_postmeta']); ?> /> Display post meta</label> <select id="single_postmeta_position" name="tcx_options[single_postmeta_position]">
					<option value="before"<?php if($options['single_postmeta_position'] == "before") {echo ' selected="selected"';}?>>before</option>
					<option value="after"<?php if($options['single_postmeta_position'] == "after") {echo ' selected="selected"';}?>>after</option>
				</select> <label for="single_postmeta_position">the post.</label>
				</td>
			</tr>
			<tr valign="top"><th scope="row">Excerpts</th>
				<td>
					<label for="excerpt_length">Excerpts show <input id="excerpt_length" name="tcx_options[excerpt_length]" type="text" class="small-text" value="<?php esc_attr_e($options['excerpt_length']); ?>" /> words and a</label> 
						<select id="excerpt_style" name="tcx_options[excerpt_style]">
						<option value="link"<?php if($options['excerpt_style'] == "link") {echo ' selected="selected"';}?>>link</option>
						<option value="button"<?php if($options['excerpt_style'] == "button") {echo ' selected="selected"';}?>>button</option>
					</select><label for="excerpt_style"> to the full post.</label><br/>
				</td>
			</tr>
			<tr valign="top"><th scope="row">Post Meta</th>
				<td>
					<label for="postmeta_author"><input id="postmeta_author" name="tcx_options[postmeta_author]" type="checkbox" value="1" <?php checked(true, $options['postmeta_author']); ?> /> Include post author</label><br/>
					<label for="postmeta_date"><input id="postmeta_date" name="tcx_options[postmeta_date]" type="checkbox" value="1" <?php checked(true, $options['postmeta_date']); ?> /> Include post date</label><br/>
					<label for="postmeta_cats"><input id="postmeta_cats" name="tcx_options[postmeta_cats]" type="checkbox" value="1" <?php checked(true, $options['postmeta_cats']); ?> /> Include categories</label><br/>
					<label for="postmeta_tags"><input id="postmeta_tags" name="tcx_options[postmeta_tags]" type="checkbox" value="1" <?php checked(true, $options['postmeta_tags']); ?>  /> Include tags, if the post has any assigned.</label>
				</td>
			</tr>
		</table>
		<h3 class="title">Social Settings</h3>
		<table class="form-table">
			<tr valign="top"><th scope="row">Share Bar</th>
				<td>
					<div class="share-bar-options">
				<label for="share_bar"><input id="share_bar" name="tcx_options[share_bar]" type="checkbox" value="1" <?php checked(true, $options['share_bar']); ?> /> Enable Share Bar</label>
				<div class="share-bar-theme-selector"><label for="share_bar_theme">Theme: </label><select id="share_bar_theme" name="tcx_options[share_bar_theme]">
						<option value="light"<?php if($options['share_bar_theme'] == "light") {echo ' selected="selected"';}?>>Light</option>
						<option value="dark"<?php if($options['share_bar_theme'] == "dark") {echo ' selected="selected"';}?>>Dark</option>
					</select></div></div><br/>
					<ul id="social-config">
						<?php
						parse_str($options[social_order], $social_order);
						foreach ($social_order['order'] as $order) {
							echo '<li id="order_' . $order . '" class="menu-item-handle tcx-sortable"><div class="gripper"></div><input name="tcx_options[tcx_social_'.$order.']" id="tcx_social_'.$order.'" type="checkbox" value="1"' . checked('1', $options['tcx_social_'.$order], false) . '/> <label for="tcx_social_'.$order.'"> '.ucfirst($order).'</label></li>';
						}
						?>
					</ul>
					<input name="tcx_options[social_order]" id="social_order" type="hidden" value="<?php echo $options[social_order];?>" />
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>

<?php } ?>