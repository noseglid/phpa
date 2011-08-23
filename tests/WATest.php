<?php

require_once dirname(__FILE__) . '/config_tests.php';
require'tests/data/common.php';

use Analyzers\WarningAnalyzer;

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
    $this->assertEquals('Analyzers\WarningAnalyzer', $this->wa->__toString());
    $this->assertEquals('wrn', WarningAnalyzer::$dataName);
  }

}

