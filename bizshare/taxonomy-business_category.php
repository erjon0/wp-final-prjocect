<?php get_header(); ?>
<div class="container">
<div class="page-header">
    <h1><?php single_term_title(); ?></h1>
    <?php if(term_description()): ?>
    <p class="category-description"><?php echo term_description(); ?></p>
    <?php endif; ?>
</div>

<?php if(have_posts()){ echo '<div class="business-grid">'; while(have_posts()): the_post(); 
$rating = get_post_meta(get_the_ID(), '_business_rating', true);
?>
<article class="business-card">
    <?php if (has_post_thumbnail()) {
        the_post_thumbnail('medium');
    } else { ?>
        <div class="business-placeholder">
            🏢 Business Image
        </div>
    <?php } ?>
    <div class="business-card-content">
        <h3><?php the_title(); ?></h3>
        
        <?php if ($rating): ?>
            <div class="rating-display">
                <?php echo bizshare_get_star_rating($rating); ?>
                <span class="rating-text"><?php echo $rating; ?>/5</span>
            </div>
        <?php endif; ?>
        
        <?php if(has_excerpt()): ?>
        <p class="business-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
        <?php endif; ?>
        
        <div class="business-meta">
            <span class="business-date">📅 <?php echo get_the_date(); ?></span>
            <span class="business-author">👤 <?php echo get_the_author(); ?></span>
        </div>
        
        <a href="<?php the_permalink(); ?>" class="btn btn-view">View Details</a>
    </div>
</article>
<?php endwhile; echo '</div>';

bizshare_pagination();

} else { echo '<p class="no-results">No businesses found in this category.</p>'; } ?>
</div>
<?php get_footer(); ?>