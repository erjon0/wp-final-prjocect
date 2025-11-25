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
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'businesses'),
        'labels' => array(
            'name' => 'Businesses',
            'singular_name' => 'Business',
            'add_new' => 'Add New Business',
            'add_new_item' => 'Add New Business',
            'edit_item' => 'Edit Business',
            'new_item' => 'New Business',
            'view_item' => 'View Business',
            'search_items' => 'Search Businesses',
            'not_found' => 'No businesses found',
            'not_found_in_trash' => 'No businesses found in Trash'
        )
    ]);
}
add_action('init','bizshare_register_cpt');

function bizshare_register_tax() {
    register_taxonomy('business_category','business',[
        'label'=>'Business Categories',
        'rewrite'=>['slug'=>'business-category'],
        'hierarchical'=>true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Business Categories',
            'singular_name' => 'Business Category',
            'search_items' => 'Search Categories',
            'all_items' => 'All Categories',
            'parent_item' => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item' => 'Edit Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'menu_name' => 'Categories'
        )
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
    
=    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
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
    
    if ($current_page < $total_pages) {
        echo '<a href="' . get_pagenum_link($current_page + 1) . '" class="pagination-btn pagination-next">Next →</a>';
    }
    
    echo '</div>';
}

function bizshare_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search) {
        $query->set('post_type', 'business');
    }
}
add_action('pre_get_posts', 'bizshare_search_filter');

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
            
            if (!empty($page['template']) && $page_id) {
                update_post_meta($page_id, '_wp_page_template', $page['template']);
            }
        }
    }
    
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'bizshare_create_required_pages');

function bizshare_get_page_url($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page->ID);
    }
    
    $page = get_page_by_title($slug);
    if ($page) {
        return get_permalink($page->ID);
    }
    
    return home_url('/' . $slug . '/');
}

function bizshare_add_business_to_main_query($query) {
    if (!is_admin() && $query->is_main_query() && ($query->is_home() || $query->is_archive())) {
        $query->set('post_type', array('post', 'business'));
    }
}
add_action('pre_get_posts', 'bizshare_add_business_to_main_query');

function bizshare_fallback_menu() {
    ?>
    <a href="<?php echo bizshare_get_page_url('top-businesses'); ?>" class="nav-btn">Top Businesses</a>
    <a href="<?php echo bizshare_get_page_url('search-business'); ?>" class="nav-btn">Search</a>
    <a href="<?php echo bizshare_get_page_url('contact'); ?>" class="nav-btn">Contact</a>
    <a href="<?php echo bizshare_get_page_url('dashboard'); ?>" class="nav-btn">Add And Rate</a>
    <?php
}

function bizshare_fix_rating_display() {
    $businesses = get_posts([
        'post_type' => 'business',
        'posts_per_page' => -1,
        'meta_query' => [
            'relation' => 'OR',
            [
                'key' => 'business_rating',
                'compare' => 'EXISTS'
            ],
            [
                'key' => '_business_rating', 
                'compare' => 'EXISTS'
            ]
        ]
    ]);
    
    foreach ($businesses as $business) {
        $old_rating = get_post_meta($business->ID, 'business_rating', true);
        $new_rating = get_post_meta($business->ID, '_business_rating', true);
        
        if ($old_rating && !$new_rating) {
            update_post_meta($business->ID, '_business_rating', $old_rating);
            delete_post_meta($business->ID, 'business_rating');
        }
    }
}
add_action('init', 'bizshare_fix_rating_display');

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
                $pages = [
                    'dashboard' => 'Dashboard',
                    'top-businesses' => 'Top Businesses', 
                    'search-business' => 'Search Business',
                    'contact' => 'Contact'
                ];
                
                foreach ($pages as $slug => $title) {
                    $page = get_page_by_path($slug);
                    if ($page) {
                        echo '<li>✓ ' . $title . ' - <a href="' . get_edit_post_link($page->ID) . '">Edit</a> | <a href="' . get_permalink($page->ID) . '">View</a></li>';
                    } else {
                        echo '<li>✗ ' . $title . ' - Missing</li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
}

function bizshare_check_required_pages() {
    $required_pages = ['dashboard', 'top-businesses', 'search-business', 'contact'];
    $missing_pages = [];
    
    foreach ($required_pages as $page_slug) {
        $page = get_page_by_path($page_slug);
        if (!$page) {
            $missing_pages[] = $page_slug;
        }
    }
    
    return $missing_pages;
}

function bizshare_admin_notice_missing_pages() {
    if (!current_user_can('manage_options')) return;
    
    $missing_pages = bizshare_check_required_pages();
    if (!empty($missing_pages)) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><strong>BizShare Theme:</strong> The following pages are missing: <?php echo implode(', ', $missing_pages); ?>. 
            <a href="<?php echo admin_url('themes.php?page=bizshare-settings'); ?>">Click here to create them</a>.</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'bizshare_admin_notice_missing_pages');
?>