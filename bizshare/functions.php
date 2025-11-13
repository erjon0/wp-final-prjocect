<?php
function bizshare_setup() {
    add_theme_support('post-thumbnails');
    register_nav_menus(['main_menu'=>'Main Menu']);
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form'));
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'bizshare_setup');

function bizshare_enqueue_assets() {
    // Enqueue main stylesheet
    wp_enqueue_style('bizshare-style', get_stylesheet_uri(), array(), '2.0');
    
    // Enqueue Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap', array(), null);
    
    // Enqueue comment reply script for threaded comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'bizshare_enqueue_assets');

// Custom Post Type: Business
function bizshare_register_cpt() {
    register_post_type('business',[
        'label'=>'Businesses','public'=>true,'has_archive'=>true,
        'menu_icon'=>'dashicons-store','supports'=>['title','editor','thumbnail','author','comments']
    ]);
}
add_action('init','bizshare_register_cpt');

// Taxonomy: Business Categories
function bizshare_register_tax() {
    register_taxonomy('business_category','business',[
        'label'=>'Business Categories','rewrite'=>['slug'=>'business-category'],'hierarchical'=>true
    ]);
}
add_action('init','bizshare_register_tax');

function bizshare_add_rating_metabox() {
    add_meta_box(
        'business_rating',
        'Business Rating',
        'bizshare_rating_callback',
        'business',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'bizshare_add_rating_metabox');

function bizshare_rating_callback($post) {
    wp_nonce_field('bizshare_save_rating', 'bizshare_rating_nonce');
    $rating = get_post_meta($post->ID, '_business_rating', true);
    ?>
    <label for="business_rating">Rating (1-5):</label>
    <select name="business_rating" id="business_rating">
        <option value="">Select Rating</option>
        <?php for($i = 1; $i <= 5; $i++): ?>
            <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
        <?php endfor; ?>
    </select>
    <?php
}

function bizshare_save_rating($post_id) {
    if (!isset($_POST['bizshare_rating_nonce']) || !wp_verify_nonce($_POST['bizshare_rating_nonce'], 'bizshare_save_rating')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['business_rating'])) {
        update_post_meta($post_id, '_business_rating', sanitize_text_field($_POST['business_rating']));
    }
}
add_action('save_post', 'bizshare_save_rating');

function bizshare_get_star_rating($rating) {
    $output = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $output .= '<span class="star filled">★</span>';
        } else {
            $output .= '<span class="star">☆</span>';
        }
    }
    return $output;
}

function bizshare_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'bizshare_excerpt_length');

function bizshare_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'bizshare_excerpt_more');
?>
