<?php

require_once 'vfsStream/vfsStream.php';
require_once 'PHPUnit/Framework.php';
require_once 'functions/common.php';


class commonTest extends PHPUnit_Framework_TestCase {

/*
  function testUseFolder() {
    $this->markTestIncomplete('Not done yet');
    $p = new vfsStreamDirectory('dir', 0664);
    $f1 = vfsStream::newFile('file.php', 0664);
    $f1->withContent("<?php\nfunction afnc() {\n  return true;\n}\n?>");
    $f2 = vfsStream::newFile('file.notphp', 0664);
    $f2->withContent('<?php function bfnc() { return false; } ?>');
    $p->addChild($f1);
    $p->addChild($f2);

    vfsStreamWrapper::getRoot()->addChild($p);

    $this->assertTrue(use_folder(vfsStream::url('dir')));
    $this->assertTrue(function_exists('afnc'),
      'function \'afnc\' does not exists, though it should.');
    $this->assertFalse(function_exists('bfnc'),
      'function \'bfnc\' exists, though it shouldn\'t');
  }
*/

  function testUseNonExistingFolder() {
    $this->assertFalse(use_folder(vfsStream::url('no_dir')));
  }


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
}

