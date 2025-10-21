<?php get_header(); ?>
<section class="hero">
  <h1>Welcome to BizShare</h1>
  <p>Discover and share local businesses — cars, services, and more.</p>
  <a href="<?php echo esc_url(site_url('/add-business')); ?>" class="btn">Add Your Business</a>
</section>

<div class="container">
  <h2>Latest Businesses</h2>
  <div class="filter-bar">
    <button class="active" data-filter="*">All</button>
    <button data-filter="cars">Cars</button>
    <button data-filter="rentals">Rentals</button>
    <button data-filter="restoration">Restoration</button>
  </div>

  <div class="business-grid" id="biz-grid">
    <?php
    $query = new WP_Query(['post_type'=>'business','posts_per_page'=>12]);
    if ($query->have_posts()): while ($query->have_posts()): $query->the_post(); ?>
      <div class="business-card" data-category="cars">
        <?php if (has_post_thumbnail()): the_post_thumbnail('medium'); else: ?>
          <img src="https://via.placeholder.com/400x250?text=<?php echo urlencode(get_the_title()); ?>" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
        <span class="car-tag">Business</span>
        <h3><?php the_title(); ?></h3>
        <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
      </div>
    <?php endwhile; else: ?>
      <p>No businesses found yet.</p>
    <?php endif; wp_reset_postdata(); ?>

   
    <div class="business-card" data-category="cars">
      <img src="OIF(1).png" alt="Auto Elite">
      <span class="car-tag">For Sale</span>
      <h3>Auto Elite</h3>
      <p>Premium car dealership offering top-tier vehicles and service excellence. Drive luxury today.</p>
      <a href="#" class="btn">View Details</a>
    </div>
    <div class="business-card" data-category="rentals">
      <img src="OIP.png" alt="EcoDrive Rentals">
      <span class="car-tag">Rental</span>
      <h3>EcoDrive Rentals</h3>
      <p>Environmentally-friendly car rentals with hybrid and electric vehicles at great rates.</p>
      <a href="#" class="btn">View Details</a>
    </div>
  </div>
</div>

<?php get_footer(); ?>
