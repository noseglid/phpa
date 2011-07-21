#!/usr/bin/php
<?php

require_once 'config.php';
require_once 'functions/xml_array.php';

DEFINE(DEBUG,true);

echo 'PHP Analyzer XML Merge by Alexander Olsson - Version ' . Config::$version . "\n\n";

function usage() {
  echo "Merges the frequency of units in several xml files which\n" .
       "have analyzed the same set of data, adding the frequency column.\n\n";
  echo "Usage:\n";
  echo "\t phpaxmlmerge [XML1 [XML2 ...]]\n\n";
  echo "\t -o  \tOutput, where the merged result is written.\n";
  exit(1);
}

function merge($data1, $data2) {
  foreach ($data1['units'] as &$unit1) {
    foreach ($data2['units'] as $unit2) {
      if ($unit1['fnc'] === $unit2['fnc']) {
        $unit1['frequency'] += $unit2['frequency'];
      }
    }
  }
  return $data1;
}

if ($argc < 2) {
  usage();
}

$files = array();

for ($i = 1; $i < $argc; $i++) {
  switch($argv[$i]) {
    case '-o':
    case '--output':
      if (empty($argv[$i+1])) {
        usage();
      }

      $out = $argv[++$i];
      break;

    default:
      $files[] = $argv[$i];
      break;
  }
}
$files = array_unique($files);

foreach ($files as $file) {
  echo "Merging file: $file\n";
  if (empty($data)) {
    $data = xml2array(simplexml_load_file($file));
  } else {
    $data = merge($data, xml2array(simplexml_load_file($file)));
  }
}

$a2x = new array2xml($data);

if (false === ($fh = @fopen($out, 'w'))) {
  echo "Error: Unable to open '$out' for writing. Exiting.\n";
  exit(1);
}

if (false === (fwrite($fh, $a2x->__toString()))) {
    echo "Error: Unable to write XML to '$out'. Exiting.\n";
    exit(1);
}

echo "Success! Wrote result of merge to '$out'.\n";

?>
