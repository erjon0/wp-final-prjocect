<?php /* Template Name: Business Search */ get_header(); ?>
<div class="container">
<div class="page-header">
    <h1>Search Businesses</h1>
    <p>Find the perfect business for your needs</p>
    <div class="header-actions">
        <a href="<?php echo home_url(); ?>" class="btn">Browse All</a>
        <a href="<?php echo bizshare_get_page_url('top-businesses'); ?>" class="btn">Top Rated</a>
        <a href="<?php echo admin_url('post-new.php?post_type=business'); ?>" class="btn btn-add">Add Business</a>
    </div>
</div>

<div class="search-form-wrapper">
    <form method="get" class="business-search-form" action="<?php echo bizshare_get_page_url('search-business'); ?>">
        <div class="form-row">
            <div class="form-group">
                <label for="search-input">Search by name</label>
                <input type="text" id="search-input" name="s" placeholder="Enter business name..." value="<?php echo esc_attr(get_search_query()); ?>">
            </div>
            
            <div class="form-group">
                <label for="category-select">Category</label>
                <?php wp_dropdown_categories([
                    'show_option_all'=>'All Categories',
                    'taxonomy'=>'business_category',
                    'name'=>'business_category',
                    'id'=>'category-select',
                    'selected'=>isset($_GET['business_category']) ? $_GET['business_category'] : '',
                    'value_field'=>'slug'
                ]); ?>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-search">🔍 Search</button>
            </div>
        </div>
    </form>
</div>

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = [
    'post_type'=>'business',
    'posts_per_page'=>12,
    'paged'=>$paged,
    's'=>isset($_GET['s'])?sanitize_text_field($_GET['s']):''
];

if(!empty($_GET['business_category'])){ 
    $args['tax_query']=[[
        'taxonomy'=>'business_category',
        'field'=>'slug',
        'terms'=>sanitize_text_field($_GET['business_category'])
    ]]; 
}

$q = new WP_Query($args);

if($q->have_posts()){ 
    echo '<div class="search-results-info"><p>Found <strong>'.$q->found_posts.'</strong> business(es)</p></div>';
    echo '<div class="business-grid">'; 
    while($q->have_posts()): $q->the_post(); 
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
    <?php endwhile; 
    echo '</div>';
    
    bizshare_pagination($q);
    
    wp_reset_postdata(); 
} else if (isset($_GET['s']) || isset($_GET['business_category'])) { 
    echo '<div class="no-results-wrapper">';
    echo '<p class="no-results">No results found. Try adjusting your search criteria.</p>';
    echo '<div class="suggestions">';
    echo '<a href="' . home_url() . '" class="btn">Browse All Businesses</a>';
    echo '<a href="' . bizshare_get_page_url('top-businesses') . '" class="btn">View Top Rated</a>';
    echo '<a href="' . admin_url('post-new.php?post_type=business') . '" class="btn btn-add">Add Your Business</a>';
    echo '</div>';
    echo '</div>'; 
} else { 
    // Show welcome message when no search has been performed
    echo '<div class="no-results-wrapper">';
    echo '<p class="no-results">Use the search form above to find businesses by name or category.</p>';
    echo '<div class="suggestions">';
    echo '<a href="' . home_url() . '" class="btn">Browse All Businesses</a>';
    echo '<a href="' . bizshare_get_page_url('top-businesses') . '" class="btn">View Top Rated Businesses</a>';
    if (is_user_logged_in()) {
        echo '<a href="' . admin_url('post-new.php?post_type=business') . '" class="btn btn-add">Add New Business</a>';
    } else {
        echo '<a href="' . wp_login_url(bizshare_get_page_url('dashboard')) . '" class="btn btn-add">Login to Add Business</a>';
    }
    echo '</div>';
    echo '</div>';
} ?>
</div>
<?php get_footer(); ?>