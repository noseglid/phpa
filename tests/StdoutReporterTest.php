<?php

require_once dirname(__FILE__) . '/config_tests.php';
require 'tests/data/common.php';

use Reporters\StdoutReporter;

class ReporterTest extends PHPUnit_Extensions_OutputTestCase {

  public function StdoutReporter_dp() {
    global $data;

    return array(
      array($data[0], "Files: 1\nUnits: 1\n"),
      array($data[1], "Files: 3\nUnits: 6\n")
    );
  }

  /**
   * @dataProvider StdoutReporter_dp
   */
  public function testStdoutReporter($data, $expected) {
    $stdoutr = new StdoutReporter($data, 'NULL');

    $this->expectOutputString($expected);
    $stdoutr->report();
  }
}

