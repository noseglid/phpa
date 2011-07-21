<?php

require_once 'PHPUnit/Extensions/OutputTestCase.php';
require_once 'reporter.php';

include 'tests/data/common.php';

class ReporterTest extends PHPUnit_Extensions_OutputTestCase {

  public function stdoutReporter_dp() {
    global $data;

    return array(
      array($data[0], "Files: 1\nUnits: 1\n"),
      array($data[1], "Files: 3\nUnits: 6\n")
    );
  }

  /**
   * @dataProvider stdoutReporter_dp
   */
  public function teststdoutReporter($data, $expected) {
    $stdoutr = new stdoutReporter($data, 'NULL');

    $this->expectOutputString($expected);
    $stdoutr->report();
  }
}

