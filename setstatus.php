<?php
header("Content-Type: text/plain");
$fnc    = $_GET['fnc'];
$file   = $_GET['file'];
$status = $_GET['status'];

if ($fnc != '' && $file != '' && $status != '') {
  require_once dirname(__FILE__) . '/database.php';
  require_once dirname(__FILE__) . '/config.php';
  Database::init(Config::$db);

  if ($status == 'done') {
    $status = Database::STATUS_DONE;
  } else if ($status == 'waiting') {
    $status = Database::STATUS_WAITING;
  } else {
    $status = Database::STATUS_NOT_DONE;
  }

  if (Database::setStatus($fnc, $file, $status)) {
    echo "true";
  } else {
    echo "false";
  }
}

