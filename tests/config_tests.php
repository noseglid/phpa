<?php

require_once dirname(__FILE__) . '/../Config/Autoload.php';

Autoload::init();

date_default_timezone_set('Europe/Stockholm');

class TestRequire
{
  public static function extension($test, $extension)
  {
    if (!extension_loaded($extension)) {
      $test->markTestSkipped("Extenstion $extension not loaded.");
    }
  }

  public static function classExtension($test, $class)
  {
    if (!class_exists($class)) {
      $test->markTestSkipped("Class extension $class not loaded.");
    }
  }

  public static function vfs($test)
  {
    self::classExtension($test, 'vfsStream');
    self::classExtension($test, 'vfsStreamWrapper');
    self::classExtension($test, 'vfsStreamFile');
    self::classExtension($test, 'vfsStream');
  }

  public static function jpgraph($test)
  {
    self::classExtension($test, 'Graph');
    self::classExtension($test, 'ScatterPlot');
  }

  public static function PDODriver($test, $driver)
  {
    self::extension($test, 'PDO');

    if (!in_array($driver, PDO::getAvailableDrivers())) {
      $test->markTestSkipped();
    }
  }
}
