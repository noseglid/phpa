<?php

require_once 'vfsStream/vfsStream.php';
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../functions/common.php';


class commonTest extends PHPUnit_Framework_TestCase {

  public static function interpMath_dp() {
    return array(
      array('1',      '1'),
      array('1+1',    '2'),
      array('2*2',    '4'),
      array('5*8+2',  '42'),
      array('0*800',  '0'),
      array('NOMATH', false)
    );
  }

  /**
   * @dataProvider interpMath_dp
   */
  function testInterpMath($str, $exp) {
    $val = interp_math($str);
    $this->assertEquals($exp, $val);
  }

  public static function formatSeconds_dp() {
    return array(
      array('0',    '0:00:00'),
      array('1',    '0:00:01'),
      array('59',   '0:00:59'),
      array('60',   '0:01:00'),
      array('61',   '0:01:01'),
      array('3599', '0:59:59'),
      array('3600', '1:00:00'),
      array('3601', '1:00:01')
    );
  }

  /**
   * @dataProvider formatSeconds_dp
   */
  function testFormatSeconds($s, $exp) {
    $this->assertEquals($exp, format_seconds($s));
  }

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
    trim_file_paths($data, $path);
    $this->assertEquals($expected, $data);
  }
}

