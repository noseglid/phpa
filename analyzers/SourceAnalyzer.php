<?php

namespace Analyzers;

require_once 'functions/common.php';

class SourceAnalyzer extends Analyzer {
  public static $dataName = 'src';

  public function analyze(&$data) {
    if (empty($data['units'])) {
      throw new Exception(UnitAnalyzer::__toString() . " must be run prior to $this\n");
    }
    $this->initProgress(count($data['units']));
    foreach ($data['units'] as &$unit) {
      $this->progress();
      $this->extractSource($unit);
    }
  }

  public function extractSource(&$unit) {
    $f = $unit['file'];
    if (false === ($fh = @fopen($f, 'r'))) {
      throw new Exception("Unable to open file $f for reading.");
    }
    /* Go to right location in file */
    for ($i = 1; $i < $unit['row']; $i++) {
      if (false === @fgets($fh)) {
        throw new Exception("Unable to read file '$f'.");
      }
    }

    $source             = &$unit['src'];
    $source_strip       = &$unit['src_strip'];
    $sloc               = &$unit['sloc'];
    $err                = &$unit['err'];
    $err                = 0;
    $curlies            = 0;
    $first_curlie_found = false;
    $in_comment         = false;

    do {
      $sloc         += 1;
      $line          = fgets($fh);
      $line_strip    = strip_1sloc($line);
      $source       .= $line;
      $source_strip .= $line_strip;
      if (1 === preg_match('/\/\*/', $line_strip)) {
        $in_comment = true;
      }
      if (1 === preg_match('/\*\//', $line_strip)) {
        $in_comment = false;
      }
      if ($in_comment) {
        continue;
      }

      if (1 === preg_match('/\{/', $line_strip)) {
        $first_curlie_found = true;
        $curlies++;
      }
      if (1 === preg_match('/\}/', $line_strip)) {
        $curlies--;
      }

    } while ((!$first_curlie_found || $curlies != 0) && !feof($fh));

    if ($curlies !== 0) {
      $source       = "Unable to parse source code for unit";
      $err          = 1;
      $source_strip = false;
      $sloc         = -1;
    }

    $source_strip = strip_nsloc($source_strip);
  }

  public function describe() {
    return "source code";
  }
}
