<?php
// functions.php
function bizshare_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(['main-menu' => __('Main Menu', 'bizshare')]);
}
add_action('after_setup_theme', 'bizshare_setup');

function bizshare_enqueue_scripts() {
    wp_enqueue_style('bizshare-style', get_stylesheet_uri());
    wp_enqueue_script('bizshare-scripts', get_template_directory_uri() . '/assets/js/bizshare.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'bizshare_enqueue_scripts');

function bizshare_register_business_cpt() {
    $labels = ['name'=>'Businesses','singular_name'=>'Business'];
    $args = [
        'labels'=>$labels,
        'public'=>true,
        'menu_icon'=>'dashicons-store',
        'supports'=>['title','editor','thumbnail'],
        'has_archive'=>true,
        'rewrite'=>['slug'=>'businesses']
    ];
    register_post_type('business', $args);
}
add_action('init', 'bizshare_register_business_cpt');

// Add demo businesses on theme activation
function bizshare_add_demo_businesses() {
    if (get_option('bizshare_demo_data_added')) return;
    $demo_businesses = [
        ['title'=>'Auto Elite','content'=>'Premium car dealership offering top-tier vehicles and service excellence. Drive luxury today.','image'=>'https://via.placeholder.com/400x250?text=Auto+Elite'],
        ['title'=>'Speedline Motors','content'=>'Reliable and affordable cars with modern designs and top performance for all lifestyles.','image'=>'https://via.placeholder.com/400x250?text=Speedline+Motors'],
        ['title'=>'EcoDrive Rentals','content'=>'Environmentally-friendly car rentals with hybrid and electric vehicles at great rates.','image'=>'https://via.placeholder.com/400x250?text=EcoDrive+Rentals'],
        ['title'=>'Classic Wheels','content'=>'Vintage car restoration and sales — rediscover the beauty of timeless automotive design.','image'=>'https://via.placeholder.com/400x250?text=Classic+Wheels']
    ];

    foreach ($demo_businesses as $biz) {
        $post_id = wp_insert_post(['post_title'=>$biz['title'],'post_content'=>$biz['content'],'post_type'=>'business','post_status'=>'publish']);
        if ($post_id && !empty($biz['image'])) {
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            media_sideload_image($biz['image'], $post_id, $biz['title']);
            // setting featured image by URL requires additional handling; left simple for demo.
        }
    }
    update_option('bizshare_demo_data_added', true);
}
add_action('after_switch_theme', 'bizshare_add_demo_businesses');
