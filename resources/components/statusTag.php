<?php
$status = $model->getStatus();

if (empty($status) || $status == UNAPPROVED) {
  if ($model->getApproved()) {
    $status = APPROVED;
  } else {
    $status = UNAPPROVED;
  }
}

if (empty($status)) {
  $status = UNAPPROVED;
}

if ($status == APPROVED) {
   include 'approvedTag.php';
} else if ($status == UNAPPROVED) {
   include 'unapprovedTag.php';
} else if ($status == DECLINED) {
   include 'declinedTag.php';
}
  