<?php

namespace Analyzers;

use Functions\XML\XML2Array;

class XMLAnalyzer extends Analyzer {
  public static $dataName = false;
  private $file;

  public function __construct($xml_file) {
    $this->file = $xml_file;
  }

  public function analyze(&$data) {
    $data = XML2Array::get(simplexml_load_file($this->file));
  }

  public function describe() {
    return 'multiple data from xml file';
  }
}
