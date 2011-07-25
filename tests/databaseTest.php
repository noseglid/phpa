<?php

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once '../database.php';

class DatabaseTest extends PHPUnit_Extensions_Database_TestCase
{

  private $filename = 'testdb.sqlite';

  public function setUp()
  {
    Database::init($this->filename);
    parent::setUp();
  }

  public function tearDown()
  {
    parent::tearDown();
    unlink($this->filename);
  }

  protected function getConnection()
  {
    $pdo = new PDO('sqlite:' . $this->filename);
    return $this->createDefaultDBConnection($pdo, 'units');
  }

  protected function getDataSet()
  {
    return $this->createXMLDataSet(dirname(__FILE__) . '/resources/dbseed.xml');
  }

  public function testDatabaseFileIsCreated()
  {
    $this->assertTrue(file_exists($this->filename));
  }

  public function testGetAll()
  {
    $unit1 = array(
      'fnc'         => 'function1_from_xml',
      0             => 'function1_from_xml',
      'file'        => '/test/file1.php',
      1             => '/test/file1.php',
      'row'         => 0,
      2             => 0,
      'frequency'   => 0,
      3             => 0,
      'complexity'  => 0,
      4             => 0,
      'dependency'  => '0 / 0',
      5             => '0 / 0',
      'depsum'      => 0,
      6             => 0,
      'sloc'        => 0,
      7             => 0,
      'src'         => '',
      8             => '',
      'wrn'         => 0,
      9             => 0,
      'err'         => 0,
      10            => 0,
    );

    $unit2          = $unit1;
    $unit2['fnc']   = 'function2_from_xml';
    $unit2[0]       = $unit2['fnc'];
    $unit2['file']  = '/test/file2.php';
    $unit2[1]       = $unit2['file'];

    $unit3          = $unit1;
    $unit3['fnc']   = 'function3_from_xml';
    $unit3[0]       = $unit3['fnc'];
    $unit3['file']  = '/test/file3.php';
    $unit3[1]       = $unit3['file'];

    $expected = array(
      $unit1,
      $unit2,
      $unit3,
    );
    $this->assertEquals($expected, Database::getAll());
  }

  public function insertUnits_dp()
  {
    $unit1 = array(
      array(
        'fnc' => 'function2',
        'file' => '/test/file2.php',
      ),
    );

    $unit2 = array(
      array(
        'fnc' => 'function2',
        'file' => '/test/file2.php',
      ),
      array(
        'fnc' => 'function2',
        'file' => '/test/file3.php',
      ),
    );

    return array(
      array($unit1, true),
      array($unit2, true),
    );
  }

  /**
   * @dataProvider insertUnits_dp
   */
  public function testInsertUnits($units, $expected)
  {
    $this->assertEquals($expected, Database::insertUnits($units));
  }

  public function testSetStatus()
  {
    $fnc  = 'no_existing_function';
    $file = '/test/no_file.php';
    $this->assertFalse(Database::setStatus($fnc, $file, Database::STATUS_DONE));

    $fnc  = 'function1_from_xml';
    $file = '/test/file1.php';
    $this->assertTrue(Database::setStatus($fnc, $file, Database::STATUS_DONE));
  }
}

