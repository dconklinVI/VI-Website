<?php
/*
Template Name: Clean
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
	<title><?php the_title(); ?></title>
	<meta name="description" content="Privacy Policy &amp; Terms of Use" />
	
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/reset.css" type="text/css" />
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/clean.css" type="text/css" />
	<?php wp_head(); ?>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/clean.js"></script>
</head>
 
<body class="clean">

	<div style="width: 100%; height: 100%; overflow: auto; -webkit-overflow-scrolling: touch;">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<?php the_content();
		
		endwhile; endif; ?>
	</div>

<?php wp_footer(); ?>
</body>
</html>