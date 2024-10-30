<?php

/*
Template Name: CCG Client Portal
*/
	get_header();
?>

	<div  class="ccgclient_portal" style="padding: 3% 0px;margin: 0 auto;">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div><!-- .ccgclient_portal -->


<?php get_footer(); ?>

