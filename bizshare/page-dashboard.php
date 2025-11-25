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
    if($q->have_posts()){ 
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
                
                <?php if (has_excerpt()): ?>
                    <p class="business-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                <?php endif; ?>
                
                <div class="card-actions">
                    <a href="<?php the_permalink(); ?>" class="btn">View</a>
                    <a href="<?php echo get_edit_post_link(get_the_ID()); ?>" class="btn btn-edit">Edit</a>
                    <a href="<?php echo get_delete_post_link(get_the_ID(), '', true); ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this business?')">Delete</a>
                </div>
            </div>
        </article>
        <?php endwhile; echo '</div>'; wp_reset_postdata(); 
    } else { 
        echo '<div class="empty-state"><p>No businesses submitted yet. <a href="'.admin_url('post-new.php?post_type=business').'">Create your first business</a>!</p></div>'; 
    } ?>
</div>

<!-- Quick Actions Section -->
<div class="dashboard-section">
    <div class="section-header">
        <h2>Quick Actions</h2>
    </div>
    <div class="quick-actions-grid">
        <a href="<?php echo home_url(); ?>" class="quick-action-card">
            <span class="action-icon">🏠</span>
            <span class="action-text">Browse All Businesses</span>
        </a>
        <a href="<?php echo bizshare_get_page_url('top-businesses'); ?>" class="quick-action-card">
            <span class="action-icon">⭐</span>
            <span class="action-text">Top Rated Businesses</span>
        </a>
        <a href="<?php echo bizshare_get_page_url('search-business'); ?>" class="quick-action-card">
            <span class="action-icon">🔍</span>
            <span class="action-text">Search Businesses</span>
        </a>
        <a href="<?php echo bizshare_get_page_url('contact'); ?>" class="quick-action-card">
            <span class="action-icon">📞</span>
            <span class="action-text">Contact Support</span>
        </a>
        <a href="<?php echo admin_url('post-new.php?post_type=business'); ?>" class="quick-action-card">
            <span class="action-icon">➕</span>
            <span class="action-text">Add New Business</span>
        </a>
        <a href="<?php echo wp_logout_url(home_url()); ?>" class="quick-action-card">
            <span class="action-icon">🚪</span>
            <span class="action-text">Logout</span>
        </a>
    </div>
</div>
</div>
<?php get_footer(); ?>