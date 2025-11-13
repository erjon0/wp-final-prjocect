<?php
/* Template Name: User Dashboard */
get_header();
if(!is_user_logged_in()){ 
    echo '<div class="container"><div class="auth-notice"><p>You must <a href="'.wp_login_url().'">log in</a> to access your dashboard.</p></div></div>'; 
    get_footer(); 
    exit; 
}
$user = wp_get_current_user();
?>
<div class="container">
<div class="dashboard-header">
    <h1>Your Dashboard</h1>
    <p class="welcome-message">Welcome back, <strong><?php echo esc_html($user->display_name); ?></strong>!</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>Your Businesses</h2>
        <a href="<?php echo admin_url('post-new.php?post_type=business'); ?>" class="btn btn-add">+ Add New Business</a>
    </div>
    
    <?php
    $q = new WP_Query(['post_type'=>'business','posts_per_page'=>-1,'author'=>$user->ID]);
    if($q->have_posts()){ echo '<div class="business-grid">'; while($q->have_posts()): $q->the_post(); ?>
    <article class="business-card">
        <?php the_post_thumbnail('medium'); ?>
        <h3><?php the_title(); ?></h3>
        <div class="card-actions">
            <a href="<?php the_permalink(); ?>" class="btn">View</a>
            <a href="<?php echo get_edit_post_link(get_the_ID()); ?>" class="btn btn-edit">Edit</a>
        </div>
    </article>
    <?php endwhile; echo '</div>'; wp_reset_postdata(); } else { 
        echo '<div class="empty-state"><p>No businesses submitted yet. <a href="'.admin_url('post-new.php?post_type=business').'">Create your first business</a>!</p></div>'; 
    } ?>
</div>
</div>
<?php get_footer(); ?>
