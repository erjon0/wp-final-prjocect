<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
<div class="container">
<div class="header-wrapper">
<h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
<nav class="header-nav">
    <a href="<?php echo bizshare_get_page_url('top-businesses'); ?>" class="nav-btn">Top Businesses</a>
    <a href="<?php echo bizshare_get_page_url('search-business'); ?>" class="nav-btn">Search</a>
    <a href="<?php echo bizshare_get_page_url('contact'); ?>" class="nav-btn">Contact</a>
    <a href="<?php echo bizshare_get_page_url('dashboard'); ?>" class="nav-btn">Add And Rate</a>
</nav>
<div class="header-search">
<form method="get" action="<?php echo bizshare_get_page_url('search-business'); ?>" class="header-search-form">
    <input type="text" name="s" placeholder="Search businesses..." value="<?php echo esc_attr(get_search_query()); ?>" required>
    <button type="submit" aria-label="Search">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>
    </button>
</form>
</div>
</div>
</div>
</header>