<?php
// Template part for a business card
?>
<div class="business-card">
  <?php if (has_post_thumbnail()): the_post_thumbnail('medium'); else: ?>
    <img src="https://via.placeholder.com/400x250?text=<?php echo urlencode(get_the_title()); ?>" alt="<?php the_title_attribute(); ?>">
  <?php endif; ?>
  <span class="car-tag">Business</span>
  <h3><?php the_title(); ?></h3>
  <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>
  <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
</div>
