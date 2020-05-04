<?php
require_once '../resources/includes/constants.php';
FishyUtils::getInstance()->log('Running background task: optimization.php');
$dir = $argv[1];
$file = $argv[2];
//$dir = ROOT . '/files/' . $this->type . '/' . $this->id . '/';

    if (!isset($file)) {
      trigger_error('File doesnt exist');
      die();
    }
    $imageClass = new Image($dir . $file);
    
    $optimizedExtension = $imageClass->optimize($dir);
    if ($optimizedExtension === false) {
      trigger_error('Uploaded image failed to move');
      die();
    }
    
    $imageClass->resizeEmbed($dir);
    FishyUtils::getInstance()->log('Ending background task: optimization.php');
    die;
