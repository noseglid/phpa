<?php
require_once 'PHPUnit/Framework.php';
require_once 'analyzers/WarningAnalyzer.php';

include 'tests/data/common.php';

class WATest extends PHPUnit_Framework_TestCase {

  private $wa;

  function __construct() {
    $this->wa = new WarningAnalyzer();
  }

  function testAnalyze() {
    global $data;

    $analyze_data  = $data[6][0];
    $expected_data = $data[6][1];
    $this->wa->analyze($analyze_data);
    $this->assertEquals($expected_data, $analyze_data);
  }

  function testConstStrings() {
    $this->assertEquals('unit warnings', $this->wa->describe());
    $this->assertEquals('WarningAnalyzer', $this->wa->__toString());
    $this->assertEquals('wrn', WarningAnalyzer::$dataName);
  }

}

