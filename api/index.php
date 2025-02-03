<?php
require_once '../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT . '/head.php'; ?>
  <!-- Start OEmbed -->
  <meta content="ModelSaber" property="og:site_name">
  <meta content="ModelSaber API" property="og:title">
  <meta content="Come wget your models" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="/resources/api.png" property="og:image">
  <!-- End OEmbed -->
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <div class="content">
      <h3 class="title is-3 has-text-centered">Welcome to the ModelSaber API!</h3>
      <p class="title is-4">ModelSaber API is currently on <a href="v2/">version 2</a>. Click that link for more information.</p>
      <p class="title is-4">Or visit <a href="v1/">version 1</a>. Click that link for more information.</p>
    </div>
  </div>
</section>
</body>
</html>