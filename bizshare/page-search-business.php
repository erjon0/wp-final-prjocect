
<?php /* Template Name: Business Search */ get_header(); ?>
<div class="container">
<h1>Search Businesses</h1>
<form method="get">
<input type="text" name="s" placeholder="Search by name..." value="<?php the_search_query(); ?>">
<?php wp_dropdown_categories(['show_option_all'=>'All Categories','taxonomy'=>'business_category','name'=>'business_category','selected'=>get_query_var('business_category'),'value_field'=>'slug']); ?>
<button type="submit">Search</button>
</form>
<?php
$args = ['post_type'=>'business','posts_per_page'=>-1,'s'=>isset($_GET['s'])?sanitize_text_field($_GET['s']):''];
if(!empty($_GET['business_category'])){ $args['tax_query']=[['taxonomy'=>'business_category','field'=>'slug','terms'=>sanitize_text_field($_GET['business_category'])]]; }
$q = new WP_Query($args);
if($q->have_posts()){ echo '<div class="business-grid">'; while($q->have_posts()): $q->the_post(); ?>
<article class="business-card"><?php the_post_thumbnail('medium'); ?><h3><?php the_title(); ?></h3>
<a href="<?php the_permalink(); ?>" class="btn">View</a></article>
<?php endwhile; echo '</div>'; wp_reset_postdata(); } else { echo '<p>No results found.</p>'; } ?>
</div>
<?php get_footer(); ?>
