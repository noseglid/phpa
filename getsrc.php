<?php
header("Content-Type: text/plain");
$fnc    = $_GET['fnc'];
$file   = $_GET['file'];

if ($fnc != '' && $file != '') {
  require_once dirname(__FILE__) . '/database.php';
  require_once dirname(__FILE__) . '/config.php';
  Database::init(Config::$db);

  $src = Database::getSrc($fnc, $file);
  if (!empty($src)) {
    $src = highlight_string("<?php\n" . $src . "\n?>", true);
    $src = substr($src, 86, -70);
    echo $src;
  } else {
    echo "false";
  }
} else {
  echo "false";
}

