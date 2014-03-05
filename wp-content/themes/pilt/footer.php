	<div class="clear"></div>
</div> <?php //#page ?>

<?php if (is_page('13') || is_page('31')) {
	echo '<div id="quotes">' . do_shortcode('[tcx_slideshow id="2"]') . '</div>';
} ?>

<?php if(is_front_page()) { ?>
<div id="location-info" class="more-info">
	<div class="container">
		<?php if (!dynamic_sidebar('home-more-info')) : endif; ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>

<div id="footer">
	<div class="container">
		<?php if (!dynamic_sidebar('footer')) : endif; ?>
		<div class="clear"></div>
	</div>
</div>

<?php wp_footer(); ?>

</body>
</html>