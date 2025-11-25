<?php get_header(); ?>
<div class="container">
<div class="page-header">
    <h1>Latest Businesses</h1>
    <p>Explore recently added businesses in our directory</p>
    <div class="header-actions">
        <a href="<?php echo bizshare_get_page_url('search-business'); ?>" class="btn">Search Businesses</a>
        <a href="<?php echo bizshare_get_page_url('top-businesses'); ?>" class="btn">Top Rated</a>
        <?php if (is_user_logged_in()): ?>
            <a href="<?php echo bizshare_get_page_url('dashboard'); ?>" class="btn btn-add">Add Business</a>
        <?php else: ?>
            <a href="<?php echo wp_login_url(bizshare_get_page_url('dashboard')); ?>" class="btn btn-add">Login to Add Business</a>
        <?php endif; ?>
    </div>
</div>

<!-- Latest Businesses Section -->
<section class="latest-businesses-section">
    <h2 class="section-title">Recently Added Businesses</h2>
    
    <?php
    $latest_businesses = new WP_Query([
        'post_type' => 'business',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC'
    ]);

    if ($latest_businesses->have_posts()) { 
        echo '<div class="business-grid">';
        while ($latest_businesses->have_posts()) {
            $latest_businesses->the_post(); 
            $rating = get_post_meta(get_the_ID(), '_business_rating', true);
            ?>
            <article class="business-card">
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail('medium');
                } else { ?>
                    <div class="business-placeholder">
                        🏢 <?php the_title(); ?>
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
                    
                    <?php if (has_excerpt()) { ?>
                        <p class="business-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 12); ?></p>
                    <?php } ?>
                    
                    <div class="business-meta">
                        <span class="business-date">📅 <?php echo get_the_date(); ?></span>
                        <span class="business-author">👤 <?php echo get_the_author(); ?></span>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="btn btn-view">View Details</a>
                </div>
            </article>
            <?php 
        }
        echo '</div>';
        
        // View All Businesses button
        echo '<div class="view-all-container">';
        echo '<a href="' . home_url('/businesses/') . '" class="btn btn-view-all">View All Businesses</a>';
        echo '</div>';
        
        wp_reset_postdata(); 
    } else { 
        echo '<div class="no-businesses-message">';
        echo '<p>No businesses found yet. Be the first to add one!</p>';
        if (is_user_logged_in()) {
            echo '<a href="' . admin_url('post-new.php?post_type=business') . '" class="btn btn-add-business">Add Your Business</a>';
        } else {
            echo '<a href="' . wp_registration_url() . '" class="btn btn-add-business">Register to Add Business</a>';
        }
        echo '</div>';
    }
    ?>
</section>

<!-- Featured Luxury Car Rentals Section -->
<section class="car-rental-section">
    <h2 class="section-title">Featured Luxury Car Rentals</h2>
    <div class="car-rental-grid">
        <article class="car-rental-card">
            <div class="car-image">
                <div class="car-placeholder">
                    🚗 Lamborghini Huracán
                </div>
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
                    <a href="<?php echo bizshare_get_page_url('contact'); ?>?car=Lamborghini Huracán" class="btn btn-rent">Rent Now</a>
                </div>
            </div>
        </article>

        <article class="car-rental-card">
            <div class="car-image">
                <div class="car-placeholder">
                    🚗 Ferrari F8 Tributo
                </div>
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
                    <a href="<?php echo bizshare_get_page_url('contact'); ?>?car=Ferrari F8 Tributo" class="btn btn-rent">Rent Now</a>
                </div>
            </div>
        </article>

        <article class="car-rental-card">
            <div class="car-image">
                <div class="car-placeholder">
                    🚗 Bugatti Chiron
                </div>
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
                    <a href="<?php echo bizshare_get_page_url('contact'); ?>?car=Bugatti Chiron" class="btn btn-rent">Rent Now</a>
                </div>
            </div>
        </article>
    </div>
</section>

<!-- All Businesses Grid -->
<section class="all-businesses-section">
    <h2 class="section-title">All Businesses</h2>
    
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $query = new WP_Query([
        'post_type' => 'business',
        'posts_per_page' => 9,
        'paged' => $paged
    ]);

    if ($query->have_posts()) { 
        echo '<div class="business-grid">';
        while ($query->have_posts()) {
            $query->the_post(); 
            $rating = get_post_meta(get_the_ID(), '_business_rating', true);
            ?>
            <article class="business-card">
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail('medium');
                } else { ?>
                    <div class="business-placeholder">
                        🏢 <?php the_title(); ?>
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
                    
                    <?php if (has_excerpt()) { ?>
                        <p class="business-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                    <?php } ?>
                    
                    <div class="business-meta">
                        <span class="business-date">📅 <?php echo get_the_date(); ?></span>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="btn btn-view">View Details</a>
                </div>
            </article>
            <?php 
        }
        echo '</div>';
        
        bizshare_pagination($query);
        
        wp_reset_postdata(); 
    } else { 
        echo '<p class="no-results">No businesses found. <a href="' . admin_url('post-new.php?post_type=business') . '">Add your first business</a></p>'; 
    }
    ?>
</section>
</div>
<?php get_footer(); ?>