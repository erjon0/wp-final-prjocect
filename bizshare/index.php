<?php get_header(); ?>
<div class="container">
<div class="page-header">
    <h1>Latest Businesses</h1>
    <p>Explore recently added businesses in our directory</p>
</div>

<?php
$query = new WP_Query(['post_type'=>'business','posts_per_page'=>9]);
if($query->have_posts()){ 
    echo '<div class="business-grid">';
    while($query->have_posts()): $query->the_post(); ?>
    <article class="business-card">
        <?php the_post_thumbnail('medium'); ?>
        <h3><?php the_title(); ?></h3>
        <?php if(has_excerpt()): ?>
        <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
    </article>
    <?php endwhile; 
    echo '</div>'; 
    wp_reset_postdata(); 
} else { 
    echo '<p class="no-results">No businesses found.</p>'; 
}
?>
</div>
<?php get_footer(); ?>
