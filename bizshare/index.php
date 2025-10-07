
<?php get_header(); ?>
<div class="container">
<h1>Latest Businesses</h1>
<?php
$query = new WP_Query(['post_type'=>'business','posts_per_page'=>6]);
if($query->have_posts()){ echo '<div class="business-grid">';
while($query->have_posts()): $query->the_post(); ?>
<article class="business-card">
<?php the_post_thumbnail('medium'); ?>
<h3><?php the_title(); ?></h3>
<a href="<?php the_permalink(); ?>" class="btn">View</a>
</article>
<?php endwhile; echo '</div>'; wp_reset_postdata(); } else { echo '<p>No businesses found.</p>'; }
?>
</div>
<?php get_footer(); ?>
