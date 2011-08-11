<?php

require_once dirname(__FILE__) . '/config_tests.php';
require_once 'vfsStream/vfsStream.php';
include 'tests/data/xml_analyzer.php';

use Analyzers\XMLAnalyzer;

class XMLAnalyzerTest extends PHPUnit_Framework_TestCase {
  protected $xa, $fs;

  protected function setUp() {
    global $xml_file_data;

    vfsStreamWrapper::register();
    vfsStreamWrapper::setRoot(new vfsStreamDirectory('r'));
    $this->fs = vfsStreamWrapper::getRoot();

    $f = new vfsStreamFile('file.xml', 0664);
    $f->withContent($xml_file_data);
    $this->fs->addChild($f);
    $this->xa = new XMLAnalyzer(vfsStream::url('file.xml'));
  }

  function testAnalyze() {
    global $xml_file_expected;

    $this->xa->analyze($data);
    $this->assertEquals($xml_file_expected, $data);
  }

  public function testConstStrings() {
    $this->assertEquals('multiple data from xml file', $this->xa->describe());
    $this->assertEquals('Analyzers\XMLAnalyzer', $this->xa->__toString());
    $this->assertEquals(false, XMLAnalyzer::$dataName);
  }
}

