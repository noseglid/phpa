<?php

require_once dirname(__FILE__) . '/config_tests.php';
require dirname(__FILE__) . '/data/common.php';
require dirname(__FILE__) . '/data/xml_array.php';

use Functions\XML\Array2XML;

class Array2XMLTest extends PHPUnit_Framework_Testcase
{
  public function array2xml_dp() {
    global $array2xml_data;
    return array($array2xml_data[0], $array2xml_data[1]);
  }

  /**
   * @dataProvider array2xml_dp
   */
  public function testArray2xml($array, $expected) {
    $a2x = new Array2XML($array);
    $this->assertEquals($expected, $a2x->__toString());
  }

}

