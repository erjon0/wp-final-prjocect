
<?php
/* Template Name: User Dashboard */
get_header();
if(!is_user_logged_in()){ echo '<div class="container"><p>You must <a href="'.wp_login_url().'">log in</a>.</p></div>'; get_footer(); exit; }
$user = wp_get_current_user();
?>
<div class="container">
<h1>Your Dashboard</h1><p>Welcome, <?php echo esc_html($user->display_name); ?>!</p>
<h2>Your Businesses</h2>
<?php
$q = new WP_Query(['post_type'=>'business','posts_per_page'=>-1,'author'=>$user->ID]);
if($q->have_posts()){ echo '<div class="business-grid">'; while($q->have_posts()): $q->the_post(); ?>
<article class="business-card">
<?php the_post_thumbnail('medium'); ?>
<h3><?php the_title(); ?></h3>
<a href="<?php the_permalink(); ?>" class="btn">View</a>
<a href="<?php echo get_edit_post_link(get_the_ID()); ?>" class="btn">Edit</a>
</article>
<?php endwhile; echo '</div>'; wp_reset_postdata(); } else { echo '<p>No businesses submitted yet.</p>'; } ?>
</div>
<?php get_footer(); ?>
