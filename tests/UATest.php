<?php

require_once 'PHPUnit/Framework.php';
require_once 'analyzers/UnitAnalyzer.php';

include 'tests/data/common.php';

class UATest extends PHPUnit_Framework_TestCase {
  private $ua;
  private $fsroot;

  function setUp() {
    vfsStreamWrapper::register();
    vfsStreamWrapper::setRoot(new vfsStreamDirectory('r'));
    $this->fsroot = vfsStreamWrapper::getRoot();
    $this->ua = new UnitAnalyzer();
  }

  function testConstStrings() {
    $this->assertEquals('units in the system', $this->ua->describe());
    $this->assertEquals('UnitAnalyzer', $this->ua->__toString());
    $this->assertEquals('units', UnitAnalyzer::$dataName);
  }

  function findUnit_dp() {
    return array(
      array('function a()', 'a'),
      array('function abcdef_162($var1, $var2) {', 'abcdef_162'),
      array('abstract function abstract();',       ''),
      array('private function     spaces() {',     'spaces'),
      array('function &reffnc()',                  'reffnc'),
      array('function 8invalid()',                 '')
    );
  }

  /**
   * @dataProvider findUnit_dp
   */
  function testFindUnit($line, $expected) {
    $this->ua->findUnit($line);
    $this->assertEquals($expected, $this->ua->units[0]['fnc']);
  }

  function testLocateUnitsNoFile() {
    $this->setExpectedException('Exception');
    $this->ua->locateUnits($no_file);
  }

  function locateUnits_dp() {
    global $data;
    return array(
      array($data[2][0], $data[2][1]),
      array($data[3][0], $data[3][1])
    );
  }

  /**
   * @dataProvider locateUnits_dp
   */
  function testLocateUnits($expected, $file) {
    $f = new vfsStreamFile('file.php', 0664);
    $f->withContent($file);
    $this->fsroot->addChild($f);
    $this->ua->locateUnits(vfsStream::url('file.php'));
    $this->assertEquals($expected, $this->ua->units);
  }

  function analyze_dp() {
    global $data;
    return array ($data[5]);
  }

  /**
   * @dataProvider analyze_dp
   */
  function testAnalyze($data, $file_content, $expected) {
    foreach ($data['files'] as $k => $file) {
      $f = new vfsStreamFile($file, 0664);
      $f->withContent($file_content[$k]);
      $this->fsroot->addChild($f);
      $data['files'][$k] = vfsStream::url($data['files'][$k]);
    }
    $this->ua->analyze($data);
    $this->assertEquals($expected, $data);
  }

}

