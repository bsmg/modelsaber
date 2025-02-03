<?php
require_once ROOT . '/resources/includes/footBloqs.php';
require_once ROOT . '/resources/filter.php'; ?>
<script src="<?= WEBROOT ?>/resources/js/burger.js"></script>
<?php if (!$helper->isLocal()): ?>
<script src="<?= WEBROOT ?>/resources/magnify.js"></script>
<?php endif; ?>
<script src="<?= WEBROOT ?>/resources/tags.js"></script>
<?php dbConnection::getInstance()->close(); ?>