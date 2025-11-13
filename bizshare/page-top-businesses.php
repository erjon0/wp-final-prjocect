<?php /* Template Name: Top Businesses */ get_header(); ?>
<div class="container">
<div class="page-header">
    <h1>Top Rated Businesses</h1>
    <p>Discover the highest-rated businesses in our community</p>
</div>

<?php
$q = new WP_Query(['post_type'=>'business','meta_key'=>'business_rating','orderby'=>'meta_value_num','order'=>'DESC','posts_per_page'=>10]);
if($q->have_posts()){ echo '<div class="business-grid">'; while($q->have_posts()): $q->the_post(); ?>
<article class="business-card">
    <?php the_post_thumbnail('medium'); ?>
    <h3><?php the_title(); ?></h3>
    <p class="rating-display">⭐ <?php echo get_post_meta(get_the_ID(),'business_rating',true); ?>/5</p>
    <?php if(has_excerpt()): ?>
    <p class="card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 12); ?></p>
    <?php endif; ?>
    <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
</article>
<?php endwhile; echo '</div>'; wp_reset_postdata(); } else { 
    echo '<p class="no-results">No rated businesses yet.</p>'; 
} ?>
</div>
<?php get_footer(); ?>
