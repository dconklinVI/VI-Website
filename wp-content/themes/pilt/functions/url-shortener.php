<?php 
// Based on La Petite URL: http://wordpress.org/extend/plugins/le-petite-url/
// Version 2.1.3

global $wpdb;
global $petite_table;

$petite_table = "short_urls";

add_option("le_petite_url_version", "2.1.4");
add_option("le_petite_url_use_mobile_style", "yes");
add_option("le_petite_url_link_text", "petite url");
add_option("le_petite_url_permalink_prefix", "default");
add_option("le_petite_url_permalink_custom", "/a/");
add_option("le_petite_url_use_lowercase", "yes");
add_option("le_petite_url_use_uppercase", "no");
add_option("le_petite_url_use_numbers", "no");
add_option("le_petite_url_length", "5");
add_option("le_petite_use_short_url", "yes");
add_option("le_petite_use_shortlink", "yes");
add_option("le_petite_url_permalink_domain", "default");
add_option("le_petite_url_domain_custom", "");
add_option("le_petite_url_use_url_as_link_text","yes");
add_option("le_petite_url_add_to_rss","yes");
add_option("le_petite_url_add_to_rss_text","If you require a short URL to link to this article, please use %%link%%");

function le_petite_url_check_url($the_petite)
{
	global $wpdb;
	global $petite_table;

	$post_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE petite_url = '".$the_petite."'");
	if(count($post_query) > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function le_petite_url_generate_string()
{
	$n = get_option('le_petite_url_length');
	$le_petite_url_chars = "";

	if(get_option('le_petite_url_use_lowercase') == "yes")
	{
		$le_petite_url_chars = $le_petite_url_chars . "abcdefghijklmnopqrstuvwxyz";
	}
	if(get_option('le_petite_url_use_uppercase') == "yes")
	{
		$le_petite_url_chars = $le_petite_url_chars . "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	}
	if(get_option('le_petite_url_use_numbers') == "yes")
	{
		$le_petite_url_chars = $le_petite_url_chars . "0123456789";
	}
	
	for ($s = '', $i = 0, $z = strlen($a = $le_petite_url_chars)-1; $i != $n; $x = rand(0,$z), $s .= $a{$x}, $i++);
	return $s;
}

function le_petite_url_make_url($post)
{
	if($post != "")
	{
		global $wpdb;
		global $petite_table;
		
		try 
		{
			$post_parent = $wpdb->get_var("SELECT post_parent FROM ".$wpdb->posts." WHERE ID = ".$post."");
		}
		catch (Exception $e)
		{
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		$post_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE post_id = ".$post."");
		$post_parent_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE post_id = ".$post_parent."");
		
		if(count($post_query) == 0 && count($post_parent_query) == 0 && $post != "")
		{
			$good_url = "no";
			while($good_url == "no")
			{
				$string = le_petite_url_generate_string();
				$post_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE petite_url = '".$string."'");
				if(count($post_query) == 0)
				{
					$good_url = "yes";
					try {
						if($post_parent != '0' && $post_parent != "")
						{
							$wpdb->query("INSERT INTO ".$wpdb->prefix. $petite_table ." VALUES($post_parent,'".mysql_real_escape_string($string)."')");
						}
						else
						{
							$wpdb->query("INSERT INTO ".$wpdb->prefix. $petite_table ." VALUES($post,'".mysql_real_escape_string($string)."')");
						}
					}
					catch(Exception $e)
					{
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}
				}
			}
		}
	}
}

function la_petite_get_host($address) { 
	// Thanks to http://stackoverflow.com/questions/276516/parsing-domain-from-url-in-php/1974047#1974047
	$parseUrl = parse_url(trim($address)); 
	return trim($parseUrl['host'] ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2))); 
} 

function le_petite_url_do_redirect()
{
	global $wpdb;
	global $petite_table;
	
	$request = $_SERVER['REQUEST_URI'];
	$the_petite = trim($request);
	$the_petite = trim($the_petite,"/");
	$referer = $_SERVER['HTTP_REFERER'];
	
	
	
	$le_petite_url_split = spliti('/',$the_petite);
	
	$le_petite_url_use = count($le_petite_url_split) - 1;
	
	if(le_petite_url_check_url($le_petite_url_split[$le_petite_url_use]))
	{
		
		$post_id = $wpdb->get_var("SELECT post_id FROM $wpdb->prefix".$petite_table." WHERE petite_url = '".$le_petite_url_split[$le_petite_url_use]."'");
		
		$permalink = get_permalink($post_id);
		$self_ref = 0;
		
		$page_title = get_the_title($post_id);
		
		if(la_petite_get_host($permalink) == la_petite_get_host(home_url()))
		{
			$self_ref = 1;
		}
		
		$expires = date('D, d M Y G:i:s T',strtotime("+1 week"));

		header("Expires: ".$expires);
		header('Location: '.$permalink, true, 302);
		exit;
	}
	else
	{
		// do stuff like normal
	}
}

function le_petite_url_install()
{
	global $wpdb;
	global $petite_table;
	
	$url_table = $wpdb->prefix . $petite_table;
	
	if($wpdb->get_var("SHOW TABLES LIKE '$url_table'") != $url_table) 
	{
		$sql = "CREATE TABLE  `" . $url_table . "` (
				`post_id` INT NOT NULL ,
				`petite_url` VARCHAR( 255 ) NOT NULL ,
				PRIMARY KEY (  `post_id` )
				);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	update_option('le_petite_url_version','2.1');

}

function get_la_petite_url_permalink($post_id)
{

	$le_petite_url_permalink_domain = get_option('le_petite_url_permalink_domain');
	$le_petite_url_domain_custom = get_option('le_petite_url_domain_custom');

	if($le_petite_url_permalink_domain != "custom" )
	{
		$blogurl = get_bloginfo('siteurl');
	}
	else
	{
		$blogurl = 'http://'.$le_petite_url_domain_custom;
	}

  $petite_url = get_le_petite_url($post_id);

	if($petite_url != "")
	{
		$le_petite_url_permalink = $blogurl;
		if(get_option('le_petite_url_permalink_prefix') == "custom")
		{
			$le_petite_url_permalink .= get_option('le_petite_url_permalink_custom');
		}
		else
		{
			$le_petite_url_permalink .= "/";
		}
		$le_petite_url_permalink .= $petite_url;

        return $le_petite_url_permalink;
	}
	
	return false;
}

function the_petite_url()
{
	global $wp_query;
	global $wpdb;

	$post_id = $wp_query->post->ID;
	
	$petite_url = get_le_petite_url($post_id);
	if($petite_url != "")
	{
		echo $petite_url;
	}
}

function get_le_petite_url($post_id)
{
	if($post_id != "")
	{
		global $wp_query;
		global $wpdb;
		global $petite_table;
		
		$url_table = $wpdb->prefix . $petite_table;
	
		$petite_url = $wpdb->get_var("SELECT petite_url FROM ".$url_table." WHERE post_id = ".$post_id."");
		if($petite_url != "")
		{
			return $petite_url;
		}
		else
		{
	
			le_petite_url_make_url($post_id);
			
			$petite_url = $wpdb->get_var("SELECT petite_url FROM ".$url_table." WHERE post_id = ".$post_id."");
			if($petite_url != "")
			{
				return $petite_url;
			}
		
		}
	}
}

function the_petite_url_link()
{
	global $wp_query;
	global $wpdb;

	$post_id = $wp_query->post->ID;
	$petite_url = get_le_petite_url($post_id);
	
	if($petite_url != "")
	{
		$le_petite_url_permalink = get_la_petite_url_permalink($post_id);
			
		if(get_option('le_petite_url_use_url_as_link_text') == "yes")
		{
			$anchor_text = $le_petite_url_permalink;
		}
		else
		{
			$anchor_text = get_option('le_petite_url_link_text');
		}
		
		echo '<a href="'.$le_petite_url_permalink.'" class="le_petite_url" rel="nofollow" title="shortened permalink for this page">'.htmlspecialchars($anchor_text, ENT_QUOTES, 'UTF-8').'</a>';
	}
}

function the_full_petite_url()
{
	global $wp_query;
	global $wpdb;

	$post_id = $wp_query->post->ID;

	$petite_url = get_le_petite_url($post_id);
	if($petite_url != "")
	{
		$le_petite_url_permalink = get_la_petite_url_permalink($post_id);
		
		echo $le_petite_url_permalink;
	}
}

function le_petite_url_short_url_header()
{
	if(is_page() || is_single()) {
		global $post;
	
		global $wp_query;
		global $wpdb;

		$post_id = $wp_query->post->ID;
	
		$petite_url = get_le_petite_url($post_id);
		if($petite_url != "")
		{
			$le_petite_url_permalink = get_la_petite_url_permalink($post_id);
            echo "<!-- la petite url version ".get_option('le_petite_url_version')." -->\n";
			echo "<link rel='shorturl' href='".$le_petite_url_permalink."' />\n";
		}
	
	}
}

function le_petite_url_shortlink_header()
{
	if(is_page() || is_single()) {
		global $post;

		global $wp_query;
		global $wpdb;

		$post_id = $wp_query->post->ID;

		$petite_url = get_le_petite_url($post_id);
		if($petite_url != "")
		{
			$le_petite_url_permalink = get_la_petite_url_permalink($post_id);
            echo "<!-- la petite url version ".get_option('le_petite_url_version')." -->\n";
			echo "<link rel='shortlink' href='".$le_petite_url_permalink."' />\n";
		}

	}
}

// function adapted from http://www.webcheatsheet.com/PHP/get_current_page_url.php

function le_petite_url_current_page()
{
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

add_action('switch_theme', 'le_petite_url_install');

add_action('init','le_petite_url_do_redirect');
add_action('save_post','le_petite_url_make_url');
add_action('wp_head', 'le_petite_url_short_url_header');

/* Hook into new WP 3.0 Shortlink filter */

function la_petite_get_shortlink($link, $id, $context)
{
	return get_la_petite_url_permalink($id);
}

add_filter('get_shortlink','la_petite_get_shortlink',10,3);

function get_la_petite_url_from_long_url($long)
{
	global $wpdb;
	global $petite_table;
	
	$post_id = url_to_postid($long);
	
	return get_la_petite_url_permalink($post_id);

}

?>