<?php /* Template Name: Top Businesses */ get_header(); ?>
<div class="container">
<div class="page-header">
    <h1>Top Rated Businesses</h1>
    <p>Discover the highest-rated businesses in our community</p>
    <div class="header-actions">
        <a href="<?php echo home_url(); ?>" class="btn">View All Businesses</a>
        <a href="<?php echo bizshare_get_page_url('search-business'); ?>" class="btn">Search Businesses</a>
        <a href="<?php echo admin_url('post-new.php?post_type=business'); ?>" class="btn btn-add">Add Your Business</a>
    </div>
</div>

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$q = new WP_Query([
    'post_type'=>'business',
    'meta_key'=>'_business_rating',
    'orderby'=>'meta_value_num',
    'order'=>'DESC',
    'posts_per_page'=>12,
    'paged'=>$paged,
    'meta_query' => [
        [
            'key' => '_business_rating',
            'value' => 0,
            'compare' => '>'
        ]
    ]
]);

if($q->have_posts()){ 
    echo '<div class="business-grid">'; 
    while($q->have_posts()): $q->the_post(); 
    $rating = get_post_meta(get_the_ID(), '_business_rating', true);
    ?>
    <article class="business-card">
        <?php if (has_post_thumbnail()) {
            the_post_thumbnail('medium');
        } else { ?>
            <div style="background: #f0f0f0; height: 180px; display: flex; align-items: center; justify-content: center; color: #666;">
                🏢 Business Image
            </div>
        <?php } ?>
        <h3><?php the_title(); ?></h3>
        <div class="rating-display">
            <?php echo bizshare_get_star_rating($rating); ?> 
            <strong><?php echo $rating; ?>/5</strong>
        </div>
        <?php if(has_excerpt()): ?>
        <p class="card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 12); ?></p>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
    </article>
    <?php endwhile; echo '</div>';
    
    bizshare_pagination($q);
    
    wp_reset_postdata(); 
} else { 
    echo '<div class="no-results-wrapper">';
    echo '<p class="no-results">No rated businesses yet.</p>';
    echo '<a href="' . admin_url('post-new.php?post_type=business') . '" class="btn btn-add">Be the first to add a business!</a>';
    echo '</div>'; 
} ?>
</div>
<?php get_footer(); ?>