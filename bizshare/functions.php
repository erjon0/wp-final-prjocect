
<?php
function bizshare_setup() {
    add_theme_support('post-thumbnails');
    register_nav_menus(['main_menu'=>'Main Menu']);
}
add_action('after_setup_theme', 'bizshare_setup');

// Custom Post Type: Business
function bizshare_register_cpt() {
    register_post_type('business',[
        'label'=>'Businesses','public'=>true,'has_archive'=>true,
        'menu_icon'=>'dashicons-store','supports'=>['title','editor','thumbnail','author']
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
?>
