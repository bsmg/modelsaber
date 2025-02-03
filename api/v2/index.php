<?php
require_once '../../resources/includes/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once ROOT . '/head.php'; ?>
  <?php
    $apiPath = WEBROOT . "/api/v2/";
    $url = 'type=saber&end=2&sort=date&sortDirection=desc';
    if (ENV == 'local') {
    $url = 'type=saber&end=2&sort=date&sortDirection=desc';
  } else if (ENV == 'production') {
    $url = 'type=saber&start=1&end=3&sort=name&sortDirection=desc&filter=author:MissRaynor,-tag:Lightning';
  }
  if (isset($_POST['url'])) {
    $url = $_POST['url'];
  }
  
  ?>
  <!-- Start OEmbed -->
  <meta content="ModelSaber" property="og:site_name">
  <meta content="ModelSaber API" property="og:title">
  <meta content="Come get your models" property="og:description">
  <meta content="#ebf4f9" name="theme-color">
  <meta content="/resources/api.png" property="og:image">
  <!-- End OEmbed -->
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <div class="content" id="apiv2">
      <h3 class="title is-3 has-text-centered">Welcome to the ModelSaber API v2!</h3>
      <p>The currently supported endpoints are:</p>
      <ul>
        <li><a href="<?= $apiPath ?>get.php">/get.php</a></li>
        <li><a href="<?= $apiPath ?>types.php">/types.php</a></li>
      </ul>
      <p>These will return a JSON object with the available resources in that category.</p>
      <p>They support the following optional <span class="tag no-hover">GET</span> options:</p>
      <ul>
        <li><span class="tag no-hover">type=&lt;string&gt;</span>: The model type to return. Default is <span class="tag no-hover">type=all</span></li>
        <li><span class="tag no-hover">platform=&lt;string&gt;</span>: The model platform to return. Default is <span class="tag no-hover">platform=all</span>, possible values: <span class="tag no-hover">all</span><span class="tag no-hover">pc</span><span class="tag no-hover">quest</span></li>
        <li><span class="tag no-hover">start=&lt;int&gt;</span>: The first index to return.</li>
        <li><span class="tag no-hover">end=&lt;int&gt;</span>: The last index to return.</li>
        <li><span class="tag no-hover">sort=&lt;date|name|author&gt;</span> : The sort method to use. Default is <span class="tag no-hover">sort=date</span></li>
        <li><span class="tag no-hover">sortDirection=&lt;asc|desc&gt;</span> : The sort direction to use. Default is <span class="tag no-hover">sortDirection=asc</span></li>
        <li><span class="tag no-hover">filter=&lt;filter1,filter2,etc&gt;</span> : Filter results. Comma delimited.</li>
        <ul>  
          <li>Allows <span class="tag no-hover">author:&lt;author&gt;</span>,
            <span class="tag no-hover">name:&lt;name&gt;</span>,
            <span class="tag no-hover">tag:&lt;tag&gt;</span>,
            <span class="tag no-hover">hash:&lt;hash&gt;</span>,
            <span class="tag no-hover">discordid:&lt;discordid&gt;</span>,
            <span class="tag no-hover">id:&lt;id&gt;</span>,
            and <span class="tag no-hover">&lt;name&gt;*</span> as filters.<span class="help">* not an exact match</span></li>
          
          <li>Allows negative filters with a <span class="tag no-hover">-</span> before the filter.</li>
        </ul>
      </ul>
      <p>Example query:</p>
      <div class="uri-example is-singleLine">
        <pre style="display: flex; flex-flow: row nowrap;">
<span class="uri-example-url"><?= $apiPath . 'get.php' ?></span>
<span class="uri-example-question">?</span>
<span class="uri-example-option">type</span>
<span class="uri-example-equals">=</span>
<span class="uri-example-value">all</span>
<span class="uri-example-separator">&</span>
<span class="uri-example-option">start</span>
<span class="uri-example-equals">=</span>
<span class="uri-example-value">1</span>
<span class="uri-example-separator">&</span>
<span class="uri-example-option">end</span>
<span class="uri-example-equals">=</span>
<span class="uri-example-value">3</span>
<span class="uri-example-separator">&</span>
<span class="uri-example-option">sort</span>
<span class="uri-example-equals">=</span>
<span class="uri-example-value">name</span>
<span class="uri-example-separator">&</span>
<span class="uri-example-option">sortDirection</span>
<span class="uri-example-equals">=</span>
<span class="uri-example-value">desc</span>
<span class="uri-example-separator">&</span>
<span class="uri-example-option">filter</span>
<span class="uri-example-equals">=</span>
<span class="uri-example-filter-option">author</span>
<span class="uri-example-filter-colon">:</span>
<span class="uri-example-filter-value">MissRaynor</span>
<span class="uri-example-filter-separator">,</span>
<span class="uri-example-filter-negative">-</span>
<span class="uri-example-filter-negative-value">Lightning</span>
        </pre>
      </div>
      <p>Test you own query:</p>
      <form id="apiQuery" action="#apiResponse" method="post">
        <div class="field has-addons">
          <div class="control">
            <span class="button is-static disabled"><?= $apiPath . 'get.php' ?></span>
          </div>
          <div class="control is-expanded">
            <input class="input is-fullwidth" name="url" placeholder="<?= $url ?>" <?= (isset($_POST['url']))? "value='$url'" : '' ?> required>
          </div>
          <div class="control">
            <button class="button is-link is-fullwidth" class="submit">Search</button>
          </div>
        </div>
        
      </form>
      <p id="apiResponse">Example response:</p>
      <pre class="language-json">
<code class="language-json"><?php
ini_set("allow_url_fopen", 1);
echo json_encode(json_decode(file_get_contents($apiPath . 'get.php?' . $url)), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?></code></pre>
    </div>
  </div>
</section>
  <?php require_once ROOT . '/resources/includes/scripts.php'; ?>
<script src="<?= WEBROOT ?>/resources/js/prism.js"></script>
</body>
</html>