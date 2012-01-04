<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="page_main" role="main">
  <header <?php post_class('protean_banner') ?> id="post-banner-<?php the_ID(); ?>">
    <h1 class="protean_banner_title"><?php the_title(); ?></h1>
    <p class="protean_banner_subtitle"><time datetime="<?php the_time('Y-m-d'); ?>">[<?php the_time('F j, Y'); ?>]</time> <?php echo get_the_excerpt(); ?></p>
  </header>
	<div  class="full_content">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</div>
	<hr/>
	<?php comments_template(); ?>

	<?php endwhile; endif; ?>
	</div><!-- page_main -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>