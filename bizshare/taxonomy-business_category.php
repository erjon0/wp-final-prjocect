
<?php get_header(); ?>
<div class="container">
<h1><?php single_term_title(); ?></h1>
<p><?php echo term_description(); ?></p>
<?php if(have_posts()){ echo '<div class="business-grid">'; while(have_posts()): the_post(); ?>
<article class="business-card">
<?php the_post_thumbnail('medium'); ?>
<h2><?php the_title(); ?></h2>
<a href="<?php the_permalink(); ?>" class="btn">View</a>
</article>
<?php endwhile; echo '</div>'; } else { echo '<p>No businesses found.</p>'; } ?>
</div>
<?php get_footer(); ?>
