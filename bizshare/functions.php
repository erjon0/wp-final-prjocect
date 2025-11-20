<?php
function bizshare_setup() {
    add_theme_support('post-thumbnails');
    register_nav_menus(['main_menu'=>'Main Menu']);
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form'));
    add_theme_support('title-tag');
    
    // Add custom image sizes
    add_image_size('business-thumbnail', 400, 300, true);
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
        'label'=>'Businesses',
        'public'=>true,
        'has_archive'=>true,
        'menu_icon'=>'dashicons-store',
        'supports'=>['title','editor','thumbnail','author','comments'],
        'show_in_rest' => true // Enable Gutenberg editor
    ]);
}
add_action('init','bizshare_register_cpt');

function bizshare_register_tax() {
    register_taxonomy('business_category','business',[
        'label'=>'Business Categories',
        'rewrite'=>['slug'=>'business-category'],
        'hierarchical'=>true,
        'show_in_rest' => true // Enable in REST API
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
    // Check if our nonce is set and verify it
    if (!isset($_POST['bizshare_rating_nonce']) || !wp_verify_nonce($_POST['bizshare_rating_nonce'], 'bizshare_save_rating')) {
        return;
    }
    
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save the rating
    if (isset($_POST['business_rating'])) {
        $rating = sanitize_text_field($_POST['business_rating']);
        if (!empty($rating)) {
            update_post_meta($post_id, '_business_rating', $rating);
        } else {
            delete_post_meta($post_id, '_business_rating');
        }
    }
}
add_action('save_post', 'bizshare_save_rating');

function bizshare_get_star_rating($rating) {
    if (empty($rating)) {
        return '';
    }
    
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

function bizshare_pagination($query = null) {
    global $wp_query;
    
    if (!$query) {
        $query = $wp_query;
    }
    
    $total_pages = $query->max_num_pages;
    
    if ($total_pages <= 1) {
        return;
    }
    
    $current_page = max(1, get_query_var('paged'));
    
    echo '<div class="pagination-wrapper">';
    
    // Previous button
    if ($current_page > 1) {
        echo '<a href="' . get_pagenum_link($current_page - 1) . '" class="pagination-btn pagination-prev">← Previous</a>';
    }
    
    echo '<div class="pagination-numbers">';
    
    $range = 2;
    $show_ellipsis = false;
    
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == 1 || $i == $total_pages || ($i >= $current_page - $range && $i <= $current_page + $range)) {
            if ($show_ellipsis) {
                echo '<span class="pagination-ellipsis">...</span>';
                $show_ellipsis = false;
            }
            
            if ($i == $current_page) {
                echo '<span class="pagination-number active">' . $i . '</span>';
            } else {
                echo '<a href="' . get_pagenum_link($i) . '" class="pagination-number">' . $i . '</a>';
            }
        } else {
            $show_ellipsis = true;
        }
    }
    
    echo '</div>';
    
    // Next button
    if ($current_page < $total_pages) {
        echo '<a href="' . get_pagenum_link($current_page + 1) . '" class="pagination-btn pagination-next">Next →</a>';
    }
    
    echo '</div>';
}

// Add search functionality
function bizshare_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search) {
        $query->set('post_type', 'business');
    }
}
add_action('pre_get_posts', 'bizshare_search_filter');

// Auto-create required pages on theme activation
function bizshare_create_required_pages() {
    $pages = [
        [
            'title' => 'Dashboard',
            'slug' => 'dashboard',
            'template' => 'page-dashboard.php'
        ],
        [
            'title' => 'Top Businesses',
            'slug' => 'top-businesses', 
            'template' => 'page-top-businesses.php'
        ],
        [
            'title' => 'Search Business',
            'slug' => 'search-business',
            'template' => 'page-search-business.php'
        ],
        [
            'title' => 'Contact',
            'slug' => 'contact',
            'template' => 'page-contact.php'
        ]
    ];
    
    foreach ($pages as $page) {
        // Check if page exists
        $existing_page = get_page_by_path($page['slug']);
        
        if (!$existing_page) {
            $new_page = [
                'post_title' => $page['title'],
                'post_name' => $page['slug'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_content' => ''
            ];
            
            $page_id = wp_insert_post($new_page);
            
            // Set page template if provided
            if (!empty($page['template']) && $page_id) {
                update_post_meta($page_id, '_wp_page_template', $page['template']);
            }
        }
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'bizshare_create_required_pages');

// Temporary function to check pages - remove after testing
function bizshare_check_pages() {
    if (current_user_can('manage_options') && is_admin()) {
        $pages = ['dashboard', 'top-businesses', 'search-business', 'contact'];
        $missing_pages = [];
        
        foreach ($pages as $page_slug) {
            $page = get_page_by_path($page_slug);
            if (!$page) {
                $missing_pages[] = $page_slug;
            }
        }
        
        if (!empty($missing_pages)) {
            add_action('admin_notices', function() use ($missing_pages) {
                echo '<div class="notice notice-warning is-dismissible">';
                echo '<p><strong>BizShare:</strong> Missing pages: ' . implode(', ', $missing_pages) . '. <a href="' . admin_url('themes.php?page=theme-settings') . '">Click here to create them</a> or reactivate the theme.</p>';
                echo '</div>';
            });
        }
    }
}
add_action('admin_init', 'bizshare_check_pages');

// Fix for rating display in top businesses page
function bizshare_fix_rating_meta() {
    // This ensures we're using the correct meta key
    $args = array(
        'post_type' => 'business',
        'meta_key' => 'business_rating',
        'posts_per_page' => -1
    );
    
    $posts = get_posts($args);
    foreach ($posts as $post) {
        $old_rating = get_post_meta($post->ID, 'business_rating', true);
        if ($old_rating && !get_post_meta($post->ID, '_business_rating', true)) {
            update_post_meta($post->ID, '_business_rating', $old_rating);
        }
    }
}
add_action('init', 'bizshare_fix_rating_meta');

// Add theme settings page
function bizshare_theme_settings_page() {
    add_theme_page(
        'BizShare Settings',
        'BizShare Settings',
        'manage_options',
        'bizshare-settings',
        'bizshare_theme_settings_html'
    );
}
add_action('admin_menu', 'bizshare_theme_settings_page');

function bizshare_theme_settings_html() {
    ?>
    <div class="wrap">
        <h1>BizShare Theme Settings</h1>
        
        <?php
        // Handle page creation request
        if (isset($_POST['create_pages']) && check_admin_referer('bizshare_create_pages')) {
            bizshare_create_required_pages();
            echo '<div class="notice notice-success is-dismissible"><p>Pages created successfully!</p></div>';
        }
        ?>
        
        <div class="card">
            <h2>Page Setup</h2>
            <p>Create the required pages for the BizShare theme:</p>
            <form method="post">
                <?php wp_nonce_field('bizshare_create_pages'); ?>
                <input type="submit" name="create_pages" class="button button-primary" value="Create Missing Pages">
            </form>
        </div>
        
        <div class="card">
            <h2>Current Pages Status</h2>
            <ul>
                <?php
                $pages = ['dashboard', 'top-businesses', 'search-business', 'contact'];
                foreach ($pages as $page_slug) {
                    $page = get_page_by_path($page_slug);
                    if ($page) {
                        echo '<li>✓ ' . $page_slug . ' - <a href="' . get_edit_post_link($page->ID) . '">Edit</a> | <a href="' . get_permalink($page->ID) . '">View</a></li>';
                    } else {
                        echo '<li>✗ ' . $page_slug . ' - Missing</li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
}

// Helper function to get page URL safely
function bizshare_get_page_url($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page->ID);
    }
    return home_url('/' . $slug . '/');
}

// Ensure business posts appear in main query
function bizshare_add_business_to_main_query($query) {
    if (!is_admin() && $query->is_main_query() && ($query->is_home() || $query->is_archive())) {
        $query->set('post_type', array('post', 'business'));
    }
}
add_action('pre_get_posts', 'bizshare_add_business_to_main_query');
?>