<?php
require_once '../../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once '../../head.php'; ?>
  <?php
  if (ENV == 'local') {
    $url = 'http://localhost/modelsaber/api/v1/saber/get.php?end=2&sort=date&sortDirection=desc';
  } else if (ENV == 'production') {
    $url = 'https://modelsaber.com/api/v1/saber/get.php?start=1&end=3&sort=name&sortDirection=desc&filter=author:MissRaynor,-Lightning';
  }
  ?>
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
      <h3 class="title is-3 has-text-centered">Welcome to the ModelSaber API v1!</h3>
      <p>The currently supported endpoints are:</p>
      <ul>
        <li><a href="https://modelsaber.com/api/v1/avatar/get.php">avatar/get.php</a></li>
        <li><a href="https://modelsaber.com/api/v1/saber/get.php">saber/get.php</a></li>
        <li><a href="https://modelsaber.com/api/v1/platform/get.php">platform/get.php</a></li>
      </ul>
      <p>These will return a JSON object with the available resources in that category.</p>
      <p>They support the following optional <kbd>GET</kbd> options:</p>
      <ul>
        <li><kbd>start=&lt;int&gt;</kbd>: The first index to return.</li>
        <li><kbd>end=&lt;int&gt;</kbd>: The last index to return.</li>
        <li><kbd>sort=&lt;date|name|author&gt;</kbd> : The sort method to use. Default is <kbd>sort=date</kbd></li>
        <li><kbd>sortDirection=&lt;asc|desc&gt;</kbd> : The sort direction to use. Default is <kbd>sortDirection=asc</kbd></li>
        <li><kbd>filter=&lt;filter1,filter2,etc&gt;</kbd> : Filter results. Comma delimited.</li>
        <ul>  
          <li>Allows <kbd>author:&lt;author&gt;</kbd>, <kbd>name:&lt;name&gt;</kbd>, <kbd>&lt;tag&gt;</kbd>, and <kbd>hash:&lt;hash&gt;</kbd> as filters.</li>
          <li>Allows negative filters with a <kbd>-</kbd> before the filter.</li>
        </ul>
      </ul>
      <p>Example query:</p>
      <div class="uri-example">
        <pre><span class="uri-example-url">https://modelsaber.com/api/v1/saber/get.php</span><span class="uri-example-question">?</span><span class="uri-example-option">start</span><span class="uri-example-equals">=</span><span class="uri-example-value">1</span><span class="uri-example-separator">&</span><span class="uri-example-option">end</span><span class="uri-example-equals">=</span><span class="uri-example-value">3</span><span class="uri-example-separator">&</span><span class="uri-example-option">sort</span><span class="uri-example-equals">=</span><span class="uri-example-value">name</span><span class="uri-example-separator">&</span><span class="uri-example-option">sortDirection</span><span class="uri-example-equals">=</span><span class="uri-example-value">desc</span><span class="uri-example-separator">&</span><span class="uri-example-option">filter</span><span class="uri-example-equals">=</span><span class="uri-example-filter-option">author</span><span class="uri-example-filter-colon">:</span><span class="uri-example-filter-value">MissRaynor</span><span class="uri-example-filter-separator">,</span><span class="uri-example-filter-negative">-</span><span class="uri-example-filter-negative-value">Lightning</span></pre>
      </div>
      <p>Example response:</p>
      <pre class="language-json">
<code class="language-json"><?php
ini_set("allow_url_fopen", 1);
echo json_encode(json_decode(file_get_contents($url)), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?></code></pre>
    </div>
  </div>
</section>
  <?php require_once ROOT . '/resources/includes/scripts.php'; ?>
<script src="https://cdn.assistant.moe/js/prism.js"></script>
</body>
</html>