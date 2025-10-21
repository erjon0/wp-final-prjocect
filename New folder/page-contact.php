<?php

get_header();
?>

<div class="container">
  <h2>Get in Touch</h2>
  <p>We’d love to hear from you — whether it’s feedback, partnership ideas, or just a friendly hello!</p>

  <?php
  // Fun randomized messages
  $messages = [
    "📬 Drop us a message, and we’ll get back faster than your coffee cools!",
    "🚀 Need help? We’re just one message away from solving it!",
    "💡 Got an idea? Let’s make it happen — together.",
    "🧠 Smart people ask questions. You’re definitely one of them!",
    "😄 Say hi! We promise to reply with something better than an auto-response."
  ];
  shuffle($messages);
  echo "<p style='color:#8E1616;font-weight:600;margin-top:10px;'>" . $messages[0] . "</p>";
  ?>

  <form method="post" class="contact-form">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
    <input type="submit" name="send_message" value="Send Message" class="btn">
  </form>

  <?php
  if (isset($_POST['send_message'])) {
      $name = sanitize_text_field($_POST['name']);
      $email = sanitize_email($_POST['email']);
      $msg = sanitize_textarea_field($_POST['message']);
      echo "<div style='margin-top:20px;background:#D84040;color:#fff;padding:15px;border-radius:6px;'>✅ Thanks, <strong>$name</strong>! Your message has been sent successfully (well... almost 😉).</div>";
  }
  ?>
</div>

<style>
.contact-form {
  max-width: 600px;
  margin: 30px auto;
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.contact-form input, .contact-form textarea {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
}
.contact-form input:focus, .contact-form textarea:focus {
  border-color: #D84040;
  box-shadow: 0 0 5px rgba(216,64,64,0.3);
  outline: none;
}
</style>

<?php get_footer(); ?>

