<?php

require_once dirname(__FILE__) . '/config_tests.php';

use Reporters\DiagramReporter;

class DiagramReporterTest extends PHPUnit_Framework_TestCase {

  private $dr;

  public function setUp() {
    TestRequire::jpgraph($this);

    $this->dr = new DiagramReporter('a', 'b', 'c:f', 'd');
  }

  public static function getValue_dp() {
    return array(
      array('a*b',   array('a' => 2, 'b' => 4),           8),
      array('a+b',   array('a' => 2, 'b' => 4),           6),
      array('a*b+c', array('a' => 2, 'b' => 4, 'c' => 3), 11),
      array('a*b*c', array('a' => 2, 'b' => 4, 'c' => 1), 8),
      array('a+b+c', array('a' => 2, 'b' => 4, 'c' => 3), 9)
    );
  }

  /**
   * @dataProvider getValue_dp
   */
  function testGetValue($datastring, $holder, $expected) {
    $val = $this->dr->getValue($datastring, $holder);
    $this->assertEquals($expected, $val);
  }
}
