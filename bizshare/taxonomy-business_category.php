<?php get_header(); ?>
<div class="container">
<div class="page-header">
    <h1><?php single_term_title(); ?></h1>
    <?php if(term_description()): ?>
    <p class="category-description"><?php echo term_description(); ?></p>
    <?php endif; ?>
</div>

<?php if(have_posts()){ echo '<div class="business-grid">'; while(have_posts()): the_post(); ?>
<article class="business-card">
<?php the_post_thumbnail('medium'); ?>
<h3><?php the_title(); ?></h3>
<?php if(has_excerpt()): ?>
<p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
<?php endif; ?>
<a href="<?php the_permalink(); ?>" class="btn">View Details</a>
</article>
<?php endwhile; echo '</div>';

bizshare_pagination();

} else { echo '<p class="no-results">No businesses found in this category.</p>'; } ?>
</div>
<?php get_footer(); ?>
