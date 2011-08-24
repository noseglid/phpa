<?php

require_once dirname(__FILE__) . '/config_tests.php';
require 'tests/data/frequency_analyzer.php';

use Analyzers\FrequencyAnalyzer;

class FrequencyAnalyzerTest extends PHPUnit_Framework_TestCase {
  protected $fa, $fs;

  protected function setUp()
  {
    TestRequire::vfs($this);

    $this->fa = new FrequencyAnalyzer();
    vfsStreamWrapper::register();
    vfsStreamWrapper::setRoot(new vfsStreamDirectory('r'));
    $this->fs = vfsStreamWrapper::getRoot();
  }

  public function testAnalyzeXdebugException() {
    $this->setExpectedException('Exception');
    $var = array('units' => 'exists');
    $this->fa->analyze($var);
  }

  public function testAnalyzeNoUnitException() {
    $this->setExpectedException('Exception');
    $var = array('xdbt' => 'exists');
    $this->fa->analyze($var);
  }

  public function analyzeXdebugtrace_dp() {
    global $xdebug_trace;
    return $xdebug_trace;
  }

  /**
   * @dataProvider analyzeXdebugtrace_dp
   */
  public function testAnalyzeXdebugTrace($file_content, $expected) {
    $f = new vfsStreamFile('file.php', 0664);
    $f->withContent($file_content);
    $this->fs->addChild($f);
    $out = $this->fa->analyzeXdebugTrace(vfsStream::url('file.php'));
    $this->assertEquals($expected, $out);
  }

  public function testAnalyzeXdebugNoFileException() {
    $this->setExpectedException('Exception');
    $this->fa->analyzeXdebugTrace($no_file);
  }

  public function analyze_dp() {
    global $xdebug_trace_analyze;
    return $xdebug_trace_analyze;
  }

  /**
   * @dataProvider analyze_dp
   */
  public function testAnalyze($data, $file_content, $expected) {
    $f = new vfsStreamFile('file.php', 0664);
    $f->withContent($file_content);
    $this->fs->addChild($f);
    $this->fa->analyze($data);
    $this->assertEquals($expected, $data);
  }

  public function testConstStrings() {
    $this->assertEquals('unit frequency', $this->fa->describe());
    $this->assertEquals('Analyzers\FrequencyAnalyzer', $this->fa->__toString());
    $this->assertEquals('frequency', FrequencyAnalyzer::$dataName);
  }
}

