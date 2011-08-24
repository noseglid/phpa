<?php

require_once dirname(__FILE__) . '/config_tests.php';
require dirname(__FILE__) . '/data/common.php';
require dirname(__FILE__) . '/data/xml_array.php';

use Functions\XML\Array2HTML;

class Array2HTMLTest extends PHPUnit_Framework_Testcase
{
  public function setUp()
  {
    TestRequire::extension($this, 'xsl');
  }

  public function array2html_dp() {
    global $data, $array2html_result;
    return array(
      array($data[0], $array2html_result)
    );
  }

  /**
   * @dataProvider array2html_dp
   */
  public function testArray2html($array, $expected) {
    $a2h = new Array2HTML($array);
    $this->assertEquals($expected, $a2h->get());
  }
}
