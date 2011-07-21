<?php

require_once 'vfsStream/vfsStream.php';
require_once 'PHPUnit/Framework.php';
require_once 'analyzers/SourceAnalyzer.php';

include 'tests/data/common.php';

class SATest extends PHPUnit_Framework_TestCase {
  private $sa;

  public function setUp() {
    $this->sa = new SourceAnalyzer();
    vfsStreamWrapper::register();
    vfsStreamWrapper::setRoot(new vfsStreamDirectory('r'));
  }

  public function testAnalyzeNoUnitsException() {
    $this->setExpectedException('Exception');
    $this->sa->analyze($data);
  }

  public function testConstStrings() {
    $this->assertEquals('source code', $this->sa->describe());
    $this->assertEquals('SourceAnalyzer', $this->sa->__toString());
    $this->assertEquals('src', SourceAnalyzer::$dataName);
  }

  public function testExtractSourceNoFileException() {
    $unit = array('path' => 'no/path', 'file' => 'no.file');
    $this->setExpectedException('Exception');
    $this->sa->extractSource($unit);
  }

  public function testExtractSourceBadFileException() {
    vfsStreamWrapper::getRoot()->addChild(
        vfsStream::newFile('unreadable', 0000));
    $unit = array(
      'file' => vfsStream::url('unreadable'),
      'row'  => '10'
    );

    $this->setExpectedException('Exception');
    $this->sa->extractSource($unit);
  }

  public function extractSource_dp() {
    global $data;

    return array(
      $data[2],
      $data[3],
      $data[4]
    );
  }

  /**
   * @dataProvider extractSource_dp
   */
  public function testAnalyze($units, $file_content, $expected) {
    $f = vfsStream::newFile('file.php', 0664);
    $f->withContent($file_content);
    vfsStreamWrapper::getRoot()->addChild($f);
    $data = array('units' => $units);
    $this->sa->analyze($data);
    $this->assertEquals(explode("\n", $expected['src']),       explode("\n", $data['units'][0]['src']));
    $this->assertEquals(explode("\n", $expected['src_strip']), explode("\n", $data['units'][0]['src_strip']));
    $this->assertEquals(explode("\n", $expected['sloc']),      explode("\n", $data['units'][0]['sloc']));
    $this->assertEquals(explode("\n", $expected['err']),       explode("\n", $data['units'][0]['err']));
  }

  /**
   * @dataProvider extractSource_dp
   */
  public function testExtractSource($units, $file_content, $expected) {
    $f = vfsStream::newFile('file.php', 0664);
    $f->withContent($file_content);
    vfsStreamWrapper::getRoot()->addChild($f);
    $this->sa->extractSource($units[0]);
    $this->assertEquals(explode("\n", $expected['src']),       explode("\n", $units[0]['src']));
    $this->assertEquals(explode("\n", $expected['src_strip']), explode("\n", $units[0]['src_strip']));
    $this->assertEquals(explode("\n", $expected['sloc']),      explode("\n", $units[0]['sloc']));
    $this->assertEquals(explode("\n", $expected['err']),       explode("\n", $units[0]['err']));
  }
}

