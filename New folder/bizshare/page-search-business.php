<?php
/*
Template Name: Search Businesses
*/
get_header();
?>
<div class="container">
  <h2>Search Businesses</h2>
  <form method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="hidden" name="post_type" value="business">
    <input type="text" name="s" placeholder="Search by name or category..." required>
    <input type="submit" value="Search" class="btn">
  </form>

  <div class="business-grid" style="margin-top:40px;">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <div class="business-card">
        <?php if (has_post_thumbnail()) the_post_thumbnail('medium'); else: ?>
          <img src="https://via.placeholder.com/400x250?text=<?php echo urlencode(get_the_title()); ?>" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
        <h3><?php the_title(); ?></h3>
        <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn">View</a>
      </div>
    <?php endwhile; else: ?>
      <p>No businesses found.</p>
    <?php endif; ?>
  </div>
</div>
<?php get_footer(); ?>
