<?php

require_once 'analyzer.php';
require_once 'functions/xml_array.php';

class XMLAnalyzer extends analyzer {
    public static $dataName = false;
    private $file;

    public function __construct($xml_file) {
      $this->file = $xml_file;
    }

    public function analyze(&$data) {
      $data = xml2array(simplexml_load_file($this->file));
    }

    public function describe() {
      return 'multiple data from xml file';
    }

    public function __toString() {
      return 'XMLAnalyzer';
    }
}

