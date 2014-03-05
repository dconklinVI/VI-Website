<?php
/*
Template Name: PDF Download
*/
?>

<?php get_header(); ?>
<div class="container">
	
	<div id="content" class="pdfdownload"> 
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="entrytext">
				<?php the_content(); ?>
				<a href="<?php echo $_GET['pdfdownload'];?>" class="tcx_button">Download PDF</a>
			</div>
			<?php edit_post_link('Edit this page', '<p>', '</p>'); ?>
		</div>

	<?php endwhile; endif; ?>

	</div>

</div>

<?php get_footer(); ?>