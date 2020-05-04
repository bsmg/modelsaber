<?php
require_once '../resources/includes/constants.php';
setcookie('showName', (isset($_POST['showName']))? "checked" : "no", time()+60*60*24*364, WEBROOT);
setcookie('showAuthor', (isset($_POST['showAuthor']))? "checked" : "no", time()+60*60*24*364, WEBROOT);
setcookie('showTags', (isset($_POST['showTags']))? "checked" : "no", time()+60*60*24*364, WEBROOT);
setcookie('showHash', (isset($_POST['showHash']))? "checked" : "no", time()+60*60*24*364, WEBROOT);
setcookie('showStatus', (isset($_POST['showStatus']))? "checked" : "no", time()+60*60*24*364, WEBROOT);
setcookie('showDescription', (isset($_POST['showDescription']))? "checked" : "no", time()+60*60*24*364, WEBROOT);

header('Location: ' . WEBROOT . '/Manage');