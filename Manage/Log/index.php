<?php
require_once '../../resources/includes/constants.php';
if (!$currentUser->isVerified() || !$currentUser->isAdmin()) {
  HTTPError(403);
}
require_once ROOT . '/log.php';
$log = new Log();

$fromValue = '';
$toValue = '';

if (!empty($_POST['level'])) {
  $log->setFilterLevels($_POST['level']);
}
if (!empty($_POST['from'])) {
  $log->setFilterDateFrom($_POST['from']);
  $fromValue = $log->getDatetimeFrom()->format('Y-m-d h:i');
}
if (!empty($_POST['to'])) {
  $log->setFilterDateTo($_POST['to']);
  $toValue = $log->getDatetimeTo()->format('Y-m-d h:i');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once ROOT . '/head.php'; ?>
  <meta name="msapplication-TileImage" content="/favicon/note/ms-icon-144x144.png">
  <?php if (isset($_GET['id'])):
    require '../embed.php';
    getEmbed (NOTE, $_GET['id']);
  else: ?> 
    <!-- Start OEmbed --> 
    <meta content="<?= SITECAMEL ?>" property="og:site_name">
    <meta content="<?= ucfirst(NOTES) ?>" property="og:title">
    <meta content="Get your waifu targets here" property="og:description">
    <meta content="#ebf4f9" name="theme-color">
    <meta content="/resources/note.png" property="og:image">
    <!-- End OEmbed -->
  <?php endif; ?>
</head>
<body>
<?php include_once ROOT . '/resources/includes/menu.php'; ?>
<section class="section">
  <div class="container">
    <?php include_once ROOT . '/resources/includes/redirectAlert.php'; ?>
    <form id="logForm" action="" method="post" style="flex-wrap:nowrap;display:flex;justify-content:space-around;">
      
      <div class="field is-grouped" style="flex-wrap:nowrap;">
        <label class="label" style="margin-right:.5em;">Level:</label>
        <?php foreach ($log->getAllLevels() as $level): ?>
          <div class="control">
            <label class="checkbox"><input type="checkbox" name="level[]" value="<?= $level ?>" <?= ($log->hasLevelFilter($level)) ? 'checked' : ''; ?>><?= ucfirst($level) ?></label>
          </div>
        <?php endforeach; ?>
      </div>
      
      <div class="field has-addons" style="flex-wrap:nowrap;">
        <label class="label" style="margin-right:.5em;">Date and time:</label>
        <div class="control">
          <span class="input disabled">From</span>
        </div>
        
        <div class="control">
          <input class="input" type="datetime" name="from" value="<?= $fromValue ?>" placeholder="<?= date(Log::DATETIME_FORMAT, 0) ?>">
        </div>
        
        <div class="control">
          <span class="input disabled">To</span>
        </div>
        
        <div class="control">
          <input class="input" type="datetime" name="to" value="<?= $fromValue ?>" placeholder="<?= date(Log::DATETIME_FORMAT) ?>">
        </div>
      </div>
      
      <div class="field">
        <div class="control">
          <input class="input" type="submit" value="Add filters">
        </div>
      </div>
    </form>
    
    <!-- Start Items -->
    <?php
      $rows = $log->read();
    ?>
    <div id="log-div">
      <table id="logTable" class="table is-fullwidth">
        <thead>
          <tr><th>Date and Time</th><th>Error Level</th><th>Error Message</th><th>File</th><th>Line</th></tr>
        </thead>
        <tbody>
          <?php if ($rows !== FALSE): ?>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td><span><?= $row['datetime'] ?></span></td>
                <td><span style="color:<?= $row['color'] ?>;"><?= $row['level'] ?></span></td>
                <td><span><?= $row['message'] ?></span></td>
                <td><span><?= $row['file'] ?></span></td>
                <td><span><?= $row['line'] ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    
    
    <?php if ($rows === FALSE): ?>
      <span class="is-size-4">Log was empty</span>
    <?php endif; ?>
    <!-- End Items -->
  </div>
</section>
<?php include_once ROOT . '/resources/includes/scripts.php'; ?>
<!--<script src="<?= WEBROOT ?>/resources/fetcher.js"></script>-->
</body>
</html>