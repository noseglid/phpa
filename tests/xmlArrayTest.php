<?php

require_once 'PHPUnit/Framework.php';
require_once 'functions/xml_array.php';

include 'tests/data/common.php';
include 'tests/data/xml_array.php';

class array2htmlTest extends PHPUnit_Framework_Testcase {

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
    $a2h = new array2html($array);
    $this->assertEquals($expected, $a2h->__toString());
  }

  public function array2xml_dp() {
    global $array2xml_data;
    return array($array2xml_data[0], $array2xml_data[1]);
  }

  /**
   * @dataProvider array2xml_dp
   */
  public function testArray2xml($array, $expected) {
    $a2x = new array2xml($array);
    $this->assertEquals($expected, $a2x->__toString());
  }

}

