<?php

require_once 'PHPUnit/Framework.php';
require_once 'analyzers/DependencyAnalyzer.php';

include 'tests/data/common.php';

class DATest extends PHPUnit_Framework_TestCase {

  private $da;

  public function setUp() {
    $this->da = new DependencyAnalyzer();
  }

  public function dependencies_dp() {
    global $data;
    return array(
      array($data[1], $data[1]['units'][0], array('0 / 0', 0)),
      array($data[1], $data[1]['units'][1], array('1 / 0', 1)),
      array($data[1], $data[1]['units'][2], array('2 / 0', 2)),
      array($data[1], $data[1]['units'][3], array('2 / 1', 3)),
      array($data[1], $data[1]['units'][4], array('1 / 1', 2)),
      array($data[1], $data[1]['units'][5], array('3 / 2', 5))
    );
  }

  /**
   * @dataProvider dependencies_dp
   */
  public function testDependencies($data, $unit, $expected) {
    $this->da->setData($data);
    $out = $this->da->dependencies($unit);
    $this->assertEquals($expected, $out);
  }

  public function analyze_dp() {
    global $data;
    return array(
      array($data[1],
        array(
          array('0 / 0', 0),
          array('1 / 0', 1),
          array('2 / 0', 2),
          array('2 / 1', 3),
          array('1 / 1', 2),
          array('3 / 2', 5)
        )
      )
    );
  }

  /**
   * @dataProvider analyze_dp
   */
  public function testAnalyze($data, $expected) {
    $this->da->analyze($data);
    foreach ($expected as $k => $e) {
      $this->assertEquals($e[0], $data['units'][$k]['dependency']);
      $this->assertEquals($e[1], $data['units'][$k]['depsum']);
    }
  }

  public function testAnalyzeNoUnitException() {
    $this->setExpectedException('Exception');
    $this->da->analyze($no_var);
  }

  function testConstStrings() {
    $this->assertEquals('unit dependencies', $this->da->describe());
    $this->assertEquals('DependencyAnalyzer', $this->da->__toString());
    $this->assertEquals('dependency', DependencyAnalyzer::$dataName);
  }
}

