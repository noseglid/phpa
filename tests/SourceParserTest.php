<?php

require_once 'PHPUnit/Framework.php';
require_once 'functions/source_parser.php';

class SourceParserTest extends PHPUnit_Framework_TestCase {

  public function strip_1sloc_dp() {
    return array(
      array('none', 'none'),
      array('\\\'', 'ESCAPED_SINGLE_QUOTE'),
      array('\"', 'ESCAPED_DOUBLE_QUOTE'),
      array('/* One line comment */', 'SLST_COMMENT'),
      array('\' a single qoute encapsed string \'', 'SINGLE_QUOTE'),
      array('" a double quote encapsed string "', 'DOUBLE_QUOTE'),
      array('// One line comment', 'ONLI_COMMENT'),
      array('# One line comment', 'ONLI_COMMENT'),
      array('$somecode; /* an slst comment */', '$somecode; SLST_COMMENT'),
      array('$somecode; // an onli comment', '$somecode; ONLI_COMMENT'),
      array('\'str1\' && \'str2\'', 'SINGLE_QUOTE && SINGLE_QUOTE'),
      array('"str1" && "str2"', 'DOUBLE_QUOTE && DOUBLE_QUOTE'),
      array('code // onli /* slst */', 'code ONLI_COMMENT'),
      array('code /* slst // onli */', 'code SLST_COMMENT')
    );
  }

  public function strip_nsloc_dp() {
    return array(
      array("/*start of comment\n multiple lines\n*/", 'MULI_COMMENT'),
      array("\"start of string\n multiple lines\n\"", 'MULI_STR'),
      array("'start of string\n multiple lines\n'", 'MULI_STR'),
      array("code /* muli comment\n continues*/ more code",
            'code MULI_COMMENT more code'),
      array("code 'muli\nstr' more code", 'code MULI_STR more code')
    );
  }

  /**
   * @dataProvider strip_1sloc_dp
   */
  function testStrip_1sloc($line, $expected_strip) {
    $strip = strip_1sloc($line);
    $this->assertEquals($expected_strip, $strip);
  }

  /**
   * @dataProvider strip_nsloc_dp
   */
  function testStrip_nsloc($line, $expected_strip) {
    $strip = strip_nsloc($line);
    $this->assertEquals($expected_strip, $strip);
  }

}

