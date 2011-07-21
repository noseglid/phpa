<?php

require_once 'PHPUnit/Framework.php';
require_once 'analyzers/CyclomaticComplexityAnalyzer.php';

class CCATest extends PHPUnit_Framework_TestCase {
  private $cca;

  public function setUp() {
    $this->cca = new CyclomaticComplexityAnalyzer();
  }

  public function complexity_dp() {
    return array(
      array(array('err'       => 1), -1),
      array(array('src_strip' => 'if ($a)'), 2),
      array(array('src_strip' => "switch(\$a) {\ncase 1: break;\ndefault: break;\n}"), 2),
      array(array('src_strip' => "switch(\$a) {\ncase 1: break;\ncase 2: break;\n}"), 3),
      array(array('src_strip' => "switch(\$a) {\ncase 0: case 1: break;\ncase 2: break;\ncase 3: case 4: break;\n}"), 4),
      array(array('src_strip' => "switch(\$a) {\ncase 0: case 1: break;\ndefault:\n}"), 2),
      array(array('src_strip' => "switch(\$a) {\ncase 0: case 1: case 2: break; \ncase 3: break;\n}"), 3),
      array(array('src_strip' => "switch(\$a) {case 0: break; case 1: break; default: break;}"), 3),
      array(array('src_strip' => "while(\$a) {\necho \$b;\n}"), 2),
      array(array('src_strip' => "for (\$i = 1; \$i < 5; \$i++) {\necho \$i; \n}"), 2),
      array(array('src_strip' => "foreach (\$a as \$k => \$v) {\necho \$v;\n}"), 2),
      array(array('src_strip' => "if (\$a && \$b || \$c) {\n echo 'ok';\n}"), 3),
      array(array('src_strip' => "if (\$a && \$b && \$c) {\n echo 'ok';\n}"), 4),
      array(array('src_strip' => '$a ? $b : $c;'), 2),
      array(array('src_strip' => "while(\$a) {\nif (\$b && \$c) {\necho \$d;\n}\n}"), 4),
      array(array('src_strip' => "switch(\$a) {\ncase 1: if (\$b) f(); \n break;\n case 2:\n break;\n}"), 4),
      array(array('src_strip' => "switch(\$a) {case 1: break;}\nswitch(\$b) {case 1: break;\n case 2: break;\n default: break;}"), 4),
      array(array('src_strip' => "if (\$a\n\$b) { return 1; }" ), 2)
    );
  }

  public function testAnalyzeNoUnitsException() {
    $this->setExpectedException('Exception');
    $this->cca->analyze($data);
  }

  /**
   * @dataProvider complexity_dp
   */
  public function testAnalyze($unit, $expected) {
    $data = array('units' => array($unit));
    $this->cca->analyze($data);
    $this->assertEquals($expected, $data['units'][0]['complexity']);
  }

  /**
   * @dataProvider complexity_dp
   */
  public function testComplexity($unit, $expected) {
    $c = $this->cca->complexity($unit);
    $this->assertEquals($expected, $c);
  }

  public function testConstStrings() {
    $this->assertEquals('complexity of units', $this->cca->describe());
    $this->assertEquals('CyclomaticComplexityAnalyzer', $this->cca->__toString());
    $this->assertEquals('complexity', CyclomaticComplexityAnalyzer::$dataName);
  }
}

