<?php

$xml_file_data =
'<?xml version="1.0"?>
<?xml-stylesheet type="text/xsl" href="base.xsl"?>
<root>
  <files>
    <data>vfs://a.php</data>
    <data>vfs://b.php</data>
  </files>
  <count>
    <files>2</files>
    <units>4</units>
  </count>
  <units>
    <data>
      <fnc>fnc1</fnc>
      <file>vfs://a.php</file>
      <row>2</row>
      <err>0</err>
      <src>return true;</src>
      <src_strip>return true;</src_strip>
      <sloc>1</sloc>
      <complexity>1</complexity>
      <frequency>1</frequency>
    </data>
    <data>
      <fnc>fnc2</fnc>
      <file>vfs://a.php</file>
      <row>4</row>
      <err>0</err>
      <src>return false;</src>
      <src_strip>return false;</src_strip>
      <sloc>1</sloc>
      <complexity>1</complexity>
      <frequency>4</frequency>
    </data>
    <data>
      <fnc>fnc3</fnc>
      <file>vfs://b.php</file>
      <row>2</row>
      <err>1</err>
      <src>return true;</src>
      <src_strip>return true;</src_strip>
      <sloc>1</sloc>
      <complexity>1</complexity>
      <frequency>1</frequency>
    </data>
    <data>
      <fnc>fnc4</fnc>
      <file>vfs://b.php</file>
      <row>5</row>
      <err>0</err>
      <src>return true ?
 "money" : "broke";</src>
      <src_strip>return true ?
 DOUBLE_QOUTE : DOUBLE_QOUTE</src_strip>
      <sloc>2</sloc>
      <complexity>2</complexity>
      <frequency>1</frequency>
    </data>
  </units>
</root>';

$xml_file_expected =
  array(
    'files' => array('vfs://a.php', 'vfs://b.php'),
    'units' => array(
      array(
        'fnc' => 'fnc1',
        'file' => 'vfs://a.php',
        'row' => 2,
        'err' => 0,
        'src' => 'return true;',
        'src_strip' => 'return true;',
        'sloc' => 1,
        'complexity' => 1,
        'frequency' => 1
      ),
      array(
        'fnc' => 'fnc2',
        'file' => 'vfs://a.php',
        'row' => 4,
        'err' => 0,
        'src' => 'return false;',
        'src_strip' => 'return false;',
        'sloc' => 1,
        'complexity' => 1,
        'frequency' => 4
      ),
      array(
        'fnc' => 'fnc3',
        'file' => 'vfs://b.php',
        'row' => 2,
        'err' => 1,
        'src' => 'return true;',
        'src_strip' => 'return true;',
        'sloc' => 1,
        'complexity' => 1,
        'frequency' => 1
      ),
      array(
        'fnc' => 'fnc4',
        'file' => 'vfs://b.php',
        'row' => 5,
        'err' => 0,
        'src' => 'return true ?
 "money" : "broke";',
        'src_strip' => 'return true ?
 DOUBLE_QOUTE : DOUBLE_QOUTE',
        'sloc' => 2,
        'complexity' => 2,
        'frequency' => 1
      )
    )
  );

