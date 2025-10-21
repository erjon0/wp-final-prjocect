<?php

get_header();
?>

<div class="container">
  <h2>Add Your Business</h2>
  <p>Submit your business to be featured in the BizShare community.</p>

  <form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Business Name" required>
    <textarea name="description" rows="5" placeholder="Business Description" required></textarea>
    <input type="text" name="category" placeholder="Category (e.g., Cars, Food, Tech)">
    <input type="text" name="city" placeholder="City">
    <input type="url" name="image" placeholder="Image URL (optional)">
    <input type="submit" name="add_business" value="Submit Business">
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_business'])) {
      $title = sanitize_text_field($_POST['title']);
      $desc = sanitize_textarea_field($_POST['description']);
      $category = sanitize_text_field($_POST['category']);
      $city = sanitize_text_field($_POST['city']);
      $image = esc_url_raw($_POST['image']);

      $post_id = wp_insert_post([
          'post_title'   => $title,
          'post_content' => $desc,
          'post_type'    => 'business',
          'post_status'  => 'pending'
      ]);

      if ($post_id) {
          if (!empty($image)) {
              require_once(ABSPATH . 'wp-admin/includes/media.php');
              require_once(ABSPATH . 'wp-admin/includes/file.php');
              require_once(ABSPATH . 'wp-admin/includes/image.php');
              media_sideload_image($image, $post_id, $title);
          }
          echo "<p style='color:green;margin-top:20px;'>✅ Your business was submitted and is awaiting review.</p>";
      } else {
          echo "<p style='color:red;margin-top:20px;'>❌ Submission failed. Try again later.</p>";
      }
  }
  ?>
</div>

<?php get_footer(); ?>
