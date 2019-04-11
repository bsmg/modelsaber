<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type">
  <!-- Start Favicon-->
  <link rel="apple-touch-icon" sizes="57x57" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.assistant.moe/favicon/modelsaber/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="https://cdn.assistant.moe/favicon/modelsaber/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://cdn.assistant.moe/favicon/modelsaber/favicon-16x16.png">
  <link rel="manifest" href="https://cdn.assistant.moe/favicon/modelsaber/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="https://cdn.assistant.moe/favicon/modelsaber/favicon/ms-icon-144x144.png">
  <!-- End Favicon -->
  <!-- Start OEmbed -->
  <meta content="ModelSaber" property="og:site_name">
  <meta content="ModelSaber" property="og:title">
  <meta content="Come get your models" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="/resources/modelsaber.png" property="og:image">
  <!-- End OEmbed -->
  <title>ModelSaber</title>
  <link href="https://cdn.assistant.moe/css/bulma.css" media="screen" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/custom.css" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/light.css" id="light-theme" rel="stylesheet">
  <link href="https://cdn.assistant.moe/css/dark.css" id="dark-theme" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.assistant.moe/js/theme.js"></script>
</head>
<body>
<!-- Start Nav Bar -->
<nav class="navbar has-shadow" aria-label="main navigation">
  <div class="container">
    <div class="navbar-brand">
      <a class="navbar-item modelsaber-logo" href="/">
        <i class=icon-modelsaber></i>
      </a>
      <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu"  aria-expanded="false">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>
    <div class="navbar-menu" id="navMenu">
      <div class="navbar-start">
        <a class="navbar-item upload modal-trigger" data-target="upload" href="/Upload">
          <i class="fas fa-cloud-upload-alt fa-2x"></i>
        </a>
      </div>
      <div class="navbar-end">
        <a class="navbar-item donate" target="_blank" href="https://bs.assistant.moe/Donate">
          <i class="fab fa-gratipay fa-2x"></i>
        </a>
        <a class="navbar-item settings modal-trigger" href="/api">
          <i class="fas fa-code fa-2x"></i>
        </a>
        <a class="navbar-item settings modal-trigger" href="/Manage">
          <i class="fas fa-cog fa-2x"></i>
        </a>
      </div>
    </div>
  </div>
</nav>
<!-- End Nav Bar -->
<section class="section">
  <div class="container">
<?php
  if (isset($_GET['redirect']) && $_GET['redirect'] == "true") {
echo <<< EOF
    <div class="notification is-warning">
      <i class="fas fa-exclamation-triangle"></i> 
      The URL has changed to <strong>https://modelsaber.com</strong>
    </div>
EOF;
  }
?>
    <!-- Start Tabs -->
    <div class="tabs is-centered is-boxed">
      <ul>
        <li>
          <a href="/Avatars">
            <span class="icon is-small"><i class="fas fa-hat-wizard" aria-hidden="true"></i></span>
            <span>Avatars</span>
          </a>
        </li>
        <li>
          <a href="/Sabers">
            <span class="icon is-small"><i class="fas fa-magic" aria-hidden="true"></i></span>
            <span>Sabers</span>
          </a>
        </li>
        <li>
          <a href="/Platforms">
            <span class="icon is-small"><i class="fas fa-torii-gate" aria-hidden="true"></i></span>
            <span>Platforms</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- End Tabs -->
    <div class="content">
      <h3 class="title is-3 has-text-centered">Welcome to ModelSaber!</h3>
      <p>ModelSaber is a repository for Avatars, Sabers, and Platforms.</p>
      <p>If you have the <a href="https://github.com/beat-saber-modding-group/BeatSaberModInstaller/releases/">Mod Manager</a> installed, you can simply click on the <kbd>Install</kbd> buttons to automatically install these models.</p>
      <p>If you don't, you can still <kbd>Download</kbd> them manually.</p>
      <p>Now with a <strong><em>dark secret...</em></strong></p>
      <p>If you want to learn to make your own, visit the guides for <a href="https://bs.assistant.moe/Avatars">Avatars</a>, <a href="https://bs.assistant.moe/Sabers">Sabers</a>, and <a href="https://bs.assistant.moe/Platforms">Platforms</a>.</p>
    </div>
  </div>
</section>
<script src="https://cdn.assistant.moe/js/burger.js"></script>
<script src="/resources/magnify.js"></script>
</body>
</html>