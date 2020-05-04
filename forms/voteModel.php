<?php
require_once '../resources/includes/constants.php';
require_once ROOT . '/user.php';
$upvote = (isset($_POST['upvote'])) ? $_POST['upvote'] : false;
$downvote = (isset($_POST['downvote'])) ? $_POST['downvote'] : false;
$vote = NULL;

if (isset($_POST['vote'])) {
  switch ($_POST['vote']) {
    case 'upvote':
      $vote = TRUE;
      break;
    case 'downvote':
      $vote = FALSE;
      break;
  }
}

/*
if ($upvote == true) {
  $vote = true;
} else if ($downvote == true) {
  $vote = false;
}
*/
//echo $upvote . ' ' . $downvote . ' ' . $vote;
//exit;
if (isset($_POST['id']) && !empty($_POST['id'])) {
  $currentUser->vote($_POST['id'], $vote);
}

if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
  header('Location: ' . $_POST['redirect'] . '?id=' . $_POST['id']);
} else {
  header('Location: ' . WEBROOT);
}
