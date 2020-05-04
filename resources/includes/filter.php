<?php
if(!isset($_GET['id'])): ?>
    <!-- Start Filter -->
    <div id="filterTitle" style="width: 100%;">
      <h4 class="title is-4">Search</h4>
      <div class="field has-addons">
        <div class="control">
          <input class="input is-rounded" id="filterInput" form="none" type="text" placeholder="Search" style="width: 100%;">
        </div>
        <div class="control">
          <button onClick="filterAdd()" form="none" class="button is-success is-rounded">
            <span class="icon is-small">
              <i class="fas fa-plus"></i>
            </span>
          </button>
        </div>
      </div>
      <!--Hardcoded for the sake of design and useability-->
    <a role="button" class="icon info mobile-toggle has-text-info is-large" title="Click for info" data-target="info-taglist">
  <i class="fas fa-info-circle" style="font-size: 2em">
  </i>
</a>
    </div>
    
<span class="infoText is-info is-small notification" id="info-taglist" aria-expanded="false"><?php include ROOT . '/resources/components/filterList.php'; ?></span>
    <div>
      <div id="filterPool" class="tags">
      </div>
    </div>
    <?= (strpos($_SERVER['PHP_SELF'], "/Manage"))? '': '<hr />'; ?>
    
    <!-- End Filter -->
<?php endif; ?>