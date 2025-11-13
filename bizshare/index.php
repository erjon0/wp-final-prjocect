<?php get_header(); ?>
<div class="container">
<div class="page-header">
    <h1>Latest Businesses</h1>
    <p>Explore recently added businesses in our directory</p>
</div>

<?php
?>
<section class="car-rental-section">
    <h2 class="section-title">Featured Luxury Car Rentals</h2>
    <div class="car-rental-grid">
        <article class="car-rental-card">
            <div class="car-image">
                <img src="/images/download.jpeg" alt="Lamborghini Huracán">
                <span class="car-badge premium">Premium</span>
            </div>
            <div class="car-content">
                <h3 class="car-title">Lamborghini Huracán</h3>
                <p class="car-description">Experience the thrill of Italian engineering with this stunning supercar</p>
                <div class="car-specs">
                    <span class="spec"><strong>Top Speed:</strong> 325 km/h</span>
                    <span class="spec"><strong>0-100:</strong> 2.9s</span>
                    <span class="spec"><strong>Power:</strong> 640 HP</span>
                </div>
                <div class="car-footer">
                    <span class="car-price">$1,500<small>/day</small></span>
                    <a href="#" class="btn btn-rent">Rent Now</a>
                </div>
            </div>
        </article>

        <article class="car-rental-card">
            <div class="car-image">
                <img src="/images/download-20-282-29.jpeg" alt="Ferrari F8 Tributo">
                <span class="car-badge luxury">Luxury</span>
            </div>
            <div class="car-content">
                <h3 class="car-title">Ferrari F8 Tributo</h3>
                <p class="car-description">Pure passion and performance from the legendary Prancing Horse</p>
                <div class="car-specs">
                    <span class="spec"><strong>Top Speed:</strong> 340 km/h</span>
                    <span class="spec"><strong>0-100:</strong> 2.9s</span>
                    <span class="spec"><strong>Power:</strong> 720 HP</span>
                </div>
                <div class="car-footer">
                    <span class="car-price">$1,800<small>/day</small></span>
                    <a href="#" class="btn btn-rent">Rent Now</a>
                </div>
            </div>
        </article>

        <article class="car-rental-card">
            <div class="car-image">
                <img src="/images/download-20-281-29.jpeg" alt="Bugatti Chiron">
                <span class="car-badge exclusive">Exclusive</span>
            </div>
            <div class="car-content">
                <h3 class="car-title">Bugatti Chiron</h3>
                <p class="car-description">The pinnacle of automotive excellence and hypercar performance</p>
                <div class="car-specs">
                    <span class="spec"><strong>Top Speed:</strong> 420 km/h</span>
                    <span class="spec"><strong>0-100:</strong> 2.4s</span>
                    <span class="spec"><strong>Power:</strong> 1,500 HP</span>
                </div>
                <div class="car-footer">
                    <span class="car-price">$3,500<small>/day</small></span>
                    <a href="#" class="btn btn-rent">Rent Now</a>
                </div>
            </div>
        </article>
    </div>
</section>

<?php
$query = new WP_Query(['post_type'=>'business','posts_per_page'=>9]);
if($query->have_posts()){ 
    echo '<div class="business-grid">';
    while($query->have_posts()): $query->the_post(); ?>
    <article class="business-card">
        <?php the_post_thumbnail('medium'); ?>
        <h3><?php the_title(); ?></h3>
        <?php if(has_excerpt()): ?>
        <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="btn">View Details</a>
    </article>
    <?php endwhile; 
    echo '</div>'; 
    wp_reset_postdata(); 
} else { 
    echo '<p class="no-results">No businesses found.</p>'; 
}
?>
</div>
<?php get_footer(); ?>
