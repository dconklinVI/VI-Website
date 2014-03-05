<!DOCTYPE html>
<html lang="en-us">

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	
	<title><?php wp_title(); ?></title>
	<meta name="apple-mobile-web-app-title" content="VI">
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/reset.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/jquery.fancybox.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/flexslider.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	
	<?php wp_get_archives('type=monthly&format=link'); ?>
	
	<?php wp_head(); ?>

	<!--[if lte IE 8]>
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie.css" type="text/css" media="all" />
	<![endif]-->
	
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/jquery-migrate-1.1.1.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/jquery.fancybox-media.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/jquery.flexslider-2.2.0.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/jquery.sharrre-1.3.4.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/jquery.customSelect.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/respond.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/script/script.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40438091-1', 'vimarketingandbranding.com');
  ga('require', 'linkid', 'linkid.js');
  ga('send', 'pageview');

</script>

<!-- Facebook Conversion Code for Conversion Pixel on FB - VI -->
<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = '6015130177186';
fb_param.value = '0.00';
fb_param.currency = 'USD';
(function(){
  var fpw = document.createElement('script');
  fpw.async = true;
  fpw.src = '//connect.facebook.net/en_US/fp.js';
  var ref = document.getElementsByTagName('script')[0];
  ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6015130177186&amp;value=0&amp;currency=USD" /></noscript>
	
</head>
<body<?php if(is_front_page()) {echo ' class="home"';} elseif (is_singular('case_studies')) {echo ' class="case-study interior"';} elseif (is_singular('work')) {echo ' class="work-single interior"';} elseif (is_singular('event')) {echo ' class="event-single interior"';} elseif (is_singular('career')) {echo ' class="career-single interior"';} elseif (is_singular('people')) {echo ' class="people-single interior"';} elseif (is_archive() || is_single()) {echo ' class="engage interior"';} elseif (is_search()) {echo ' class="engage search interior"';} else {echo ' class="interior"';}?>>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-MCPMCL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MCPMCL');</script>
<!-- End Google Tag Manager -->
<a name="top"></a>
<div id="header">
	<div class="container">
		<?php wp_nav_menu( array('theme_location' => 'main-menu-left', 'container' => '', 'menu_id' => 'menu-left', 'fallback_cb' => 'fallback_menu')); ?>
		<?php wp_nav_menu( array('theme_location' => 'main-menu-right', 'container' => '', 'menu_id' => 'menu-right', 'fallback_cb' => 'fallback_menu')); ?>
		<h1><a href="<?php echo get_settings('home'); ?>" class="logo"><?php bloginfo('name'); ?></a><a href="#top" class="mobile-scroll"></a></h1>
	</div>
</div>

<?php if (is_singular('case_studies')) { ?>
<div id="slideshow">
	<?php the_post_thumbnail('large'); ?>
</div>
<?php } ?>

<?php if(2==1) { ?>
	<div id="slideshow">
	<div class="tcx_slideshow slideshow-1">
		<ul class="slides">
			<li style="max-height: 100%; display: list-item; ">
				<img src="/wp-content/uploads/2013/05/placeholder.jpg" alt=""/>
			</li>
		</ul>
	</div>
<div class="clear"></div>
</div>
<?php } ?>

<?php if(is_front_page()) { ?>
	<div id="slideshow">
	<?php echo do_shortcode('[tcx_slideshow id="1"]'); ?>
	</div>
<?php } ?>

<div id="page">