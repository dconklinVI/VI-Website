<?php function tcx_slideshows() { ?>

<div class="wrap">
<h2>Home Page</h2>

<form method="post" action="options.php">
	
	<?php settings_fields('settings-group'); ?>
	<h3 class="title">Slideshow</h3>
	<table class="form-table">
	
		<tr valign="top">
			<td colspan="2">Header slideshow images should be exactly <span class="green">845 pixels in width</span> and <span class="orange">276 pixels in height</span>.</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><strong>Slide 1</strong><br/><img src="<?php echo get_option('home_image1_url'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_image1" type="text" size="36" name="home_image1_url" value="<?php echo get_option('home_image1_url'); ?>" />
			<input id="upload_image_button1" type="button" value="Upload Image" /><br/>
			<input name="home_image1_title" value="<?php echo get_option('home_image1_title'); ?>" type="text" class="regular-text" /><br/><textarea rows="5" cols="40" name="home_image1_desc"><?php echo get_option('home_image1_desc'); ?></textarea>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><strong>Slide 2</strong><br/><img src="<?php echo get_option('home_image2_url'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_image2" type="text" size="36" name="home_image2_url" value="<?php echo get_option('home_image2_url'); ?>" />
			<input id="upload_image_button2" type="button" value="Upload Image" /><br/>
			<input name="home_image2_title" value="<?php echo get_option('home_image2_title'); ?>" type="text" class="regular-text" /><br/><textarea rows="5" cols="40" name="home_image2_desc"><?php echo get_option('home_image2_desc'); ?></textarea>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><strong>Slide 3</strong><br/><img src="<?php echo get_option('home_image3_url'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_image3" type="text" size="36" name="home_image3_url" value="<?php echo get_option('home_image3_url'); ?>" />
			<input id="upload_image_button3" type="button" value="Upload Image" /><br/>
			<input name="home_image3_title" value="<?php echo get_option('home_image3_title'); ?>" type="text" class="regular-text" /><br/><textarea rows="5" cols="40" name="home_image3_desc"><?php echo get_option('home_image3_desc'); ?></textarea>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><strong>Slide 3</strong><br/><img src="<?php echo get_option('home_image3_url'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_image3" type="text" size="36" name="home_image3_url" value="<?php if(get_option('home_image3_url')) { echo get_option('home_image3_url'); } else { echo 'Image URL'; } ?>" />
			<input id="upload_image_button3" type="button" value="Upload Image" /><br/>
			<input name="home_image3_title" value="<?php if(get_option('home_image3_title')) { echo get_option('home_image3_title'); } else { echo 'Title'; } ?>" type="text" class="regular-text" /><br/><textarea rows="5" cols="40" name="home_image3_desc"><?php if(get_option('home_image3_desc')) { echo get_option('home_image3_desc'); } else { echo "Description"; } ?></textarea>
			</td>
		</tr>
	
	</table>
	
	<h3 class="title">Promotion Box</h3>
	<table class="form-table">
		<tr valign="top">
			<td colspan="2">The images used in promo boxes are limited to <span class="green">190 pixels in width</span> and <span class="orange">220 pixels in height</span>.<br/>Larger images will be cropped; smaller images will be centered in the available space.</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><strong>Promo 1</strong><br/><img src="<?php echo get_option('home_promo1_image'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_promo1" type="text" size="36" name="home_promo1_image" value="<?php echo get_option('home_promo1_image'); ?>" />
			<input id="upload_promo1_button" type="button" value="Upload Image" /><br/>
			<select name="home_promo1_url" onchange="savePromo1(this.options[this.selectedIndex].text)"> 
				<option value=""><?php echo attribute_escape(__('Select page')); ?></option>
				<optgroup label="Events">
				<?php 
				query_posts('post_type=Events&post_status=publish&posts_per_page=100');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo1_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Pages">
				<?php 
				query_posts('post_type=Page&post_status=publish&posts_per_page=100&orderby=title&order=ASC');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo1_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Posts">
				<?php 
				query_posts('post_type=post&post_status=publish&posts_per_page=20');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo1_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
			</select>
			<br/><textarea rows="5" cols="40" name="home_promo1_desc"><?php echo get_option('home_promo1_desc'); ?></textarea>
			<input type="hidden" id="home_promo1_title" name="home_promo1_title" value="<?php echo get_option('home_promo1_title'); ?>" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><strong>Promo 2</strong><br/><img src="<?php echo get_option('home_promo2_image'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_promo2" type="text" size="36" name="home_promo2_image" value="<?php echo get_option('home_promo2_image'); ?>" />
			<input id="upload_promo2_button" type="button" value="Upload Image" /><br/>
			<select name="home_promo2_url" onchange="savePromo2(this.options[this.selectedIndex].text)"> 
				<option value=""><?php echo attribute_escape(__('Select page')); ?></option>
				<optgroup label="Events">
				<?php 
				query_posts('post_type=Events&post_status=publish&posts_per_page=100');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo2_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Pages">
				<?php 
				query_posts('post_type=Page&post_status=publish&posts_per_page=100&orderby=title&order=ASC');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo2_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Posts">
				<?php 
				query_posts('post_type=post&post_status=publish&posts_per_page=20');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo2_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
			</select>
			<br/><textarea rows="5" cols="40" name="home_promo2_desc"><?php echo get_option('home_promo2_desc'); ?></textarea>
			<input type="hidden" id="home_promo2_title" name="home_promo2_title" value="<?php echo get_option('home_promo2_title'); ?>" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><strong>Promo 3</strong><br/><img src="<?php echo get_option('home_promo3_image'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_promo3" type="text" size="36" name="home_promo3_image" value="<?php echo get_option('home_promo3_image'); ?>" />
			<input id="upload_promo3_button" type="button" value="Upload Image" /><br/>
			<select name="home_promo3_url" onchange="savePromo3(this.options[this.selectedIndex].text)"> 
				<option value=""><?php echo attribute_escape(__('Select page')); ?></option>
				<optgroup label="Events">
				<?php 
				query_posts('post_type=Events&post_status=publish&posts_per_page=100');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo3_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Pages">
				<?php 
				query_posts('post_type=Page&post_status=publish&posts_per_page=100&orderby=title&order=ASC');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo3_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Posts">
				<?php 
				query_posts('post_type=post&post_status=publish&posts_per_page=20');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo3_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
			</select>
			<br/><textarea rows="5" cols="40" name="home_promo3_desc"><?php echo get_option('home_promo3_desc'); ?></textarea>
			<input type="hidden" id="home_promo3_title" name="home_promo3_title" value="<?php echo get_option('home_promo3_title'); ?>" />
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><strong>Promo 4</strong><br/><img src="<?php echo get_option('home_promo4_image'); ?>" alt="" width="200" /></th>
			<td>
			<input id="upload_promo4" type="text" size="36" name="home_promo4_image" value="<?php echo get_option('home_promo4_image'); ?>" />
			<input id="upload_promo4_button" type="button" value="Upload Image" /><br/>
			<select name="home_promo4_url" onchange="savePromo4(this.options[this.selectedIndex].text)"> 
				<option value=""><?php echo attribute_escape(__('Select page')); ?></option>
				<optgroup label="Events">
				<?php 
				query_posts('post_type=Events&post_status=publish&posts_per_page=100');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo4_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Pages">
				<?php 
				query_posts('post_type=Page&post_status=publish&posts_per_page=100&orderby=title&order=ASC');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo4_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
				<optgroup label="Posts">
				<?php 
				query_posts('post_type=post&post_status=publish&posts_per_page=20');
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					$tehtitle = get_the_title();
					$tehurl = get_permalink();
					$option = '<option value="' . $tehurl . '"';
					if ($tehurl == get_option('home_promo4_url')) { $option .= " selected"; }
					$option .= '>';
					$option .= $tehtitle;
					$option .= '</option>';
					echo $option;
				endwhile; else:
				endif;
				wp_reset_query();
				?>
				</optgroup>
			</select>
			<br/><textarea rows="5" cols="40" name="home_promo4_desc"><?php echo get_option('home_promo4_desc'); ?></textarea>
			<input type="hidden" id="home_promo4_title" name="home_promo4_title" value="<?php echo get_option('home_promo4_title'); ?>" />
			</td>
		</tr>
		
	</table>
		
	
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>

</form>
</div>
<?php } ?>