<?php

namespace Analyzers;

require_once 'functions/source_parser.php';

/**
 * A analyzer which finds units in a source code tree.
 *
 * @author Alexander Olsson <noseglid@gmail.com>
 * @package analyzers
 */
class UnitAnalyzer extends Analyzer {
  public static $dataName = 'units';

  public $units;

  public function __construct() {
    $this->units = array();
  }

  public function analyze(&$data) {
    $this->initProgress(count($data['files']));

    foreach ($data['files'] as $k => $file) {
      if(0 === preg_match('/.Test\.php$/', $file)) {
        $this->progress();
        $this->locateUnits($file);
      } else {
        unset($data['files'][$k]);
      }
    }

    $data[UnitAnalyzer::$dataName] = $this->units;
  }

  public function locateUnits($file) {
    if (false === ($fh = fopen($file, 'r'))) {
      /* Oops. The file cannot be opened for reading. */
      throw new Exception("Unable to open file '$file' for reading.");
    }

    $row        = 0;
    $in_comment = false;
    $in_php     = false;
    while(($line = fgets($fh)) !== false) {
      $row++;
      $line = strip_1sloc($line);

      if (1 == preg_match('/\<\?(php)?/', $line)) {
        $in_php = true;
      }
      if (1 === preg_match('/\?\>/', $line)) {
        $in_php = false;
      }
      if (1 === preg_match('/\/\*/', $line)) {
        $in_comment = true;
      }
      if (1 === preg_match('/\*\//', $line)) {
        $in_comment = false;
      }
      if ($in_comment || !$in_php) {
        continue;
      }

      if (false !== ($array_pos = $this->findUnit($line))) {
        $this->units[$array_pos]['file'] = $file;
        $this->units[$array_pos]['row']  = $row;
        $this->setFaults($this->units[$array_pos]);
      }
    }
  }

  /**
   * Identifies units on a line.
   * The line is assumed to have comments, quotes etc stripped
   * (see strip_1sloc) and be in a php context.
   *
   * @return mixed position of inserted unit
   *         if found and set successfully, false otherwise.
   */
  public function findUnit($line) {
    /*
    Is this a line which defines a function?
    Ignore abstract functions.
    */
    if (0 === preg_match('/function[\s]+&?([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\(.*\)(?!;)/', $line, $match)) {
      return false;
    }
    $array_pos = count($this->units);
    $this->units[$array_pos]['fnc'] = $match[1];
    return $array_pos;
  }

  public function setFaults(&$unit) {
    $unit['err']  = in_array(false, $unit, true) ? 1 : 0;
  }

  public function describe() {
    return 'units in the system';
  }
}
