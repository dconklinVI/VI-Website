<?php 
// Based on Duplicate Post: http://wordpress.org/extend/plugins/duplicate-post
// Version 2.4.1

function duplicate_post_is_current_user_allowed_to_copy() {
	return current_user_can('copy_posts');
}

function duplicate_post_get_clone_post_link( $id = 0, $context = 'display', $draft = true ) {
	if ( !$post = &get_post( $id ) )
	return;

	if ($draft)
	$action_name = "duplicate_post_save_as_new_post_draft";
	else
	$action_name = "duplicate_post_save_as_new_post";

	if ( 'display' == $context )
	$action = '?action='.$action_name.'&amp;post='.$post->ID;
	else
	$action = '?action='.$action_name.'&post='.$post->ID;

	$post_type_object = get_post_type_object( $post->post_type );
	if ( !$post_type_object )
	return;

	return apply_filters( 'duplicate_post_get_clone_post_link', admin_url( "admin.php". $action ), $post->ID, $context );
}

function duplicate_post_clone_post_link( $link = null, $before = '', $after = '', $id = 0 ) {
	if ( !$post = &get_post( $id ) )
	return;

	if ( !$url = duplicate_post_get_clone_post_link( $post->ID ) )
	return;

	if ( null === $link )
	$link = __('Copy to a new draft');

	$post_type_obj = get_post_type_object( $post->post_type );
	$link = '<a class="post-clone-link" href="' . $url . '" title="'
	. esc_attr(__("Copy to a new draft"))
	.'">' . $link . '</a>';
	echo $before . apply_filters( 'duplicate_post_clone_post_link', $link, $post->ID ) . $after;
}

function duplicate_post_get_original($id = 0 , $output = OBJECT){
	if ( !$post = &get_post( $id ) )
	return;
	$original_ID = get_post_meta( $post->ID, '_dp_original');
	if (empty($original_ID)) return null;
	$original_post = &get_post($original_ID[0],  $output);
	return $original_post;
}

function duplicate_post_make_duplicate_link_row($actions, $post) {
	if (duplicate_post_is_current_user_allowed_to_copy()) {
		$actions['clone'] = '<a href="'.duplicate_post_get_clone_post_link( $post->ID , 'display', false).'" title="'
		. esc_attr(__("Clone this item"))
		. '">' .  __('Clone') . '</a>';
		$actions['edit_as_new_draft'] = '<a href="'. duplicate_post_get_clone_post_link( $post->ID ) .'" title="'
		. esc_attr(__('Copy to a new draft'))
		. '">' .  __('New Draft') . '</a>';
	}
	return $actions;
}

function duplicate_post_add_duplicate_post_button() {
	if ( isset( $_GET['post'] ) && duplicate_post_is_current_user_allowed_to_copy()) {
		?>
<div id="duplicate-action">
	<a class="submitduplicate duplication"
		href="<?php echo duplicate_post_get_clone_post_link( $_GET['post'] ) ?>"><?php _e('Copy to a new draft'); ?>
	</a>
</div>
		<?php
	}
}

function duplicate_post_save_as_new_post_draft(){
	duplicate_post_save_as_new_post('draft');
}

function duplicate_post_save_as_new_post($status = ''){
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'duplicate_post_save_as_new_post' == $_REQUEST['action'] ) ) ) {
		wp_die(__('No post to duplicate has been supplied!'));
	}

	// Get the original post
	$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
	$post = get_post($id);

	// Copy the post and insert it
	if (isset($post) && $post!=null) {
		$new_id = duplicate_post_create_duplicate($post, $status);

		if ($status == ''){
			// Redirect to the post list screen
			wp_redirect( admin_url( 'edit.php?post_type='.$post->post_type) );
		} else {
			// Redirect to the edit screen for the new draft post
			wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_id ) );
		}
		exit;

	} else {
		$post_type_obj = get_post_type_object( $post->post_type );
		wp_die(esc_attr(__('Copy creation failed, could not find original:')) . ' ' . $id);
	}
}

/**
 * Get the currently registered user
 */
function duplicate_post_get_current_user() {
	if (function_exists('wp_get_current_user')) {
		return wp_get_current_user();
	} else if (function_exists('get_currentuserinfo')) {
		global $userdata;
		get_currentuserinfo();
		return $userdata;
	} else {
		$user_login = $_COOKIE[USER_COOKIE];
		$current_user = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE user_login='$user_login'");
		return $current_user;
	}
}

function duplicate_post_create_duplicate($post, $status = '', $parent_id = '') {
	if ($post->post_type == 'revision') return;

	if ($post->post_type != 'attachment'){
		$prefix = get_option('duplicate_post_title_prefix');
		$suffix = get_option('duplicate_post_title_suffix');
		if (!empty($prefix)) $prefix.= " ";
		if (!empty($suffix)) $suffix = " ".$suffix;
		if (get_option('duplicate_post_copystatus') == 0) $status = 'draft';
	}
	$new_post_author = duplicate_post_get_current_user();
	
	$new_post = array(
	'menu_order' => $post->menu_order,
	'comment_status' => $post->comment_status,
	'ping_status' => $post->ping_status,
	'post_author' => $new_post_author->ID,
	'post_content' => $post->post_content,
	'post_excerpt' => $post->post_excerpt,
	'post_mime_type' => $post->post_mime_type,
	'post_parent' => $new_post_parent = empty($parent_id)? $post->post_parent : $parent_id,
	'post_password' => $post->post_password,
	'post_status' => $new_post_status = (empty($status))? $post->post_status: $status,
	'post_title' => $prefix.$post->post_title.$suffix,
	'post_type' => $post->post_type,
	);

	if(get_option('duplicate_post_copydate') == 1){
		$new_post['post_date'] = $new_post_date =  $post->post_date ;
		$new_post['post_date_gmt'] = get_gmt_from_date($new_post_date);
	}

	$new_post_id = wp_insert_post($new_post);


	// If you have written a plugin which uses non-WP database tables to save
	// information about a post you can hook this action to dupe that data.
	if ($post->post_type == 'page' || (function_exists('is_post_type_hierarchical') && is_post_type_hierarchical( $post->post_type )))
	do_action( 'dp_duplicate_page', $new_post_id, $post );
	else
	do_action( 'dp_duplicate_post', $new_post_id, $post );

	delete_post_meta($new_post_id, '_dp_original');
	add_post_meta($new_post_id, '_dp_original', $post->ID);

	// If the copy is published or scheduled, we have to set a proper slug.
	if ($new_post_status == 'publish' || $new_post_status == 'future'){
		$post_name = wp_unique_post_slug($post->post_name, $new_post_id, $new_post_status, $post->post_type, $new_post_parent);

		$new_post = array();
		$new_post['ID'] = $new_post_id;
		$new_post['post_name'] = $post_name;

		// Update the post into the database
		wp_update_post( $new_post );
	}

	return $new_post_id;
}

/**
 * Connect actions to functions
 */
add_action( 'post_submitbox_start', 'duplicate_post_add_duplicate_post_button' );
add_action('admin_action_duplicate_post_save_as_new_post', 'duplicate_post_save_as_new_post');
add_action('admin_action_duplicate_post_save_as_new_post_draft', 'duplicate_post_save_as_new_post_draft');
//add_filter('post_row_actions', 'duplicate_post_make_duplicate_link_row',10,2);
//add_filter('page_row_actions', 'duplicate_post_make_duplicate_link_row',10,2);
?>