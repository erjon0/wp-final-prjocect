<?php /* Template Name: Business Search */ get_header(); ?>
<div class="container">
<div class="page-header">
    <h1>Search Businesses</h1>
    <p>Find the perfect business for your needs</p>
</div>

<div class="search-form-wrapper">
    <form method="get" class="business-search-form">
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
                    'selected'=>get_query_var('business_category'),
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
$args = ['post_type'=>'business','posts_per_page'=>-1,'s'=>isset($_GET['s'])?sanitize_text_field($_GET['s']):''];
if(!empty($_GET['business_category'])){ 
    $args['tax_query']=[['taxonomy'=>'business_category','field'=>'slug','terms'=>sanitize_text_field($_GET['business_category'])]]; 
}
$q = new WP_Query($args);

if($q->have_posts()){ 
    echo '<div class="search-results-info"><p>Found <strong>'.$q->found_posts.'</strong> business(es)</p></div>';
    echo '<div class="business-grid">'; 
    while($q->have_posts()): $q->the_post(); ?>
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
    echo '<div class="no-results-wrapper"><p class="no-results">No results found. Try adjusting your search criteria.</p></div>'; 
} ?>
</div>
<?php get_footer(); ?>
