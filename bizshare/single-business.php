<?php get_header(); ?>
<div class="container">
<?php if(have_posts()): while(have_posts()): the_post(); ?>
<article class="single-business-post">
    <div class="post-header">
        <h1 class="post-title"><?php the_title(); ?></h1>
        <div class="post-meta">
            <span class="author">👤 <?php echo get_the_author(); ?></span>
            <span class="date">📅 <?php echo get_the_date(); ?></span>
            <?php 
            // FIXED: Use correct meta key with underscore
            $rating = get_post_meta(get_the_ID(), '_business_rating', true);
            if($rating): ?>
            <span class="rating">
                <?php echo bizshare_get_star_rating($rating); ?> 
                <?php echo $rating; ?>/5
            </span>
            <?php endif; ?>
        </div>
    </div>

    <?php if(has_post_thumbnail()): ?>
    <div class="post-thumbnail">
        <?php the_post_thumbnail('large'); ?>
    </div>
    <?php endif; ?>

    <div class="post-content">
        <?php the_content(); ?>
    </div>

    <?php 
    $categories = get_the_terms(get_the_ID(), 'business_category');
    if($categories && !is_wp_error($categories)): ?>
    <div class="post-categories">
        <strong>Categories:</strong>
        <?php foreach($categories as $cat): ?>
        <a href="<?php echo get_term_link($cat); ?>" class="category-tag"><?php echo $cat->name; ?></a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if(comments_open() || get_comments_number()): ?>
    <div class="comments-section">
        <?php comments_template(); ?>
    </div>
    <?php endif; ?>
</article>
<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>