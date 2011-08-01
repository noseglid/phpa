<?php

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once '../database.php';

class DatabaseTest extends PHPUnit_Extensions_Database_TestCase
{

  private $filename = 'testdb.sqlite';

  public function setUp()
  {
    Database::init($this->filename);
    Database::createTables();
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
      'file'        => '/test/file1.php',
      'row'         => 0,
      'frequency'   => 1,
      'complexity'  => 1,
      'dependency'  => '0 / 0',
      'depsum'      => 0,
      'sloc'        => 10,
      'src'         => '',
      'wrn'         => 0,
      'err'         => 0,
      'status'      => 1,
    );

    $unit2                = $unit1;
    $unit2['fnc']         = 'function2_from_xml';
    $unit2['file']        = '/test/file2.php';
    $unit2['frequency']   = '2';
    $unit2['complexity']  = '2';
    $unit2['sloc']        = '11';
    $unit2['status']      = '1';

    $unit3                = $unit1;
    $unit3['fnc']         = 'function3_from_xml';
    $unit3['file']        = '/test/file2.php';
    $unit3['frequency']   = '3';
    $unit3['complexity']  = '3';
    $unit3['sloc']        = '12';
    $unit3['status']      = '0';

    $expected = array(
      $unit3,
      $unit2,
      $unit1,
    );

    $this->assertEquals($expected, Database::getAll());
  }

  public function insertData_dp()
  {
    $data1 = array(
      'count' => array(
        'files' => '1',
      ),
      'units' => array(
        array(
          'fnc' => 'function2',
          'file' => '/test/file2.php',
        ),
      ),
    );

    $data2 = array(
      'count' => array(
        'files' => '3',
      ),
      'units' => array(
        array(
          'fnc' => 'function2',
          'file' => '/test/file2.php',
        ),
        array(
          'fnc' => 'function2',
          'file' => '/test/file3.php',
        ),
      ),
    );

    return array(
      array($data1, true),
      array($data2, true),
    );
  }

  /**
   * @dataProvider insertData_dp
   */
  public function testInsertUnits($data, $expected)
  {
    $this->assertEquals($expected, Database::insertData($data));
  }

  public function testSetStatus()
  {
    $fnc  = 'no_existing_function';
    $file = '/test/no_file.php';
    $this->assertFalse(Database::setStatus($fnc, $file, Database::STATUS_DONE));

    $fnc  = 'function1_from_xml';
    $file = '/test/file1.php';
    $this->assertTrue(Database::setStatus($fnc, $file, Database::STATUS_WAITING));
  }

  public function testGetStatistics()
  {
    $expected = array(
      'number_of_files'       => 5,
      'number_of_units'       => 3,
      'total_unit_sloc'       => 33,            // 10+11+12
      'average_sloc_unit'     => 11,            // (10+11+12)/3
      'average_complexity'    => 2,             // (1+2+3)/3
      'mean_sloc_complexity'  => 5,             // (10+11+12)/(1+2+3)=5.5
      'errors'                => 0,
      'warnings'              => 0,
      'status_done'           => 2,
    );

    $this->assertEquals($expected, Database::getStatistics());
  }
}

