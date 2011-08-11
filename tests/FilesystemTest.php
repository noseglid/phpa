<?php

require_once dirname(__FILE__) . '/config_tests.php';

use Functions\Filesystem;

class FilesystemTest extends PHPUnit_Framework_TestCase
{
  public function trim_file_paths_dp() {
    $path = '/random/test/';
    return array(
      array(
        array(
          'files' => array(
            $path . 'dir2/test1.php',
            $path . 'dir1/test2.php',
          ),
          'units' => array(
            array('file' => $path . 'dir2/test1.php'),
            array('file' => $path . 'dir1/test2.php'),
          ),
        ),
        $path,
        array(
          'files' => array(
            'dir2/test1.php',
            'dir1/test2.php',
          ),
          'units' => array(
            array('file' => 'dir2/test1.php'),
            array('file' => 'dir1/test2.php'),
          ),
        ),
      ),
      array(
        array(
          'files' => array(
            $path . 'dir2/test1.php',
            $path . 'dir1/test2.php',
          ),
          'units' => array(
            array('file' => $path . 'dir2/test1.php'),
            array('file' => $path . 'dir1/test2.php'),
          ),
        ),
        '',
        array(
          'files' => array(
            $path . 'dir2/test1.php',
            $path . 'dir1/test2.php',
          ),
          'units' => array(
            array('file' => $path . 'dir2/test1.php'),
            array('file' => $path . 'dir1/test2.php'),
          ),
        ),
      ),
    );
  }

  /**
   * @dataProvider trim_file_paths_dp
   */
  public function test_trim_file_paths($data, $path, $expected) {
    Filesystem::trimFilePaths($data, $path);
    $this->assertEquals($expected, $data);
  }

}
