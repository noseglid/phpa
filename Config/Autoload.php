<?php

class Autoload
{
  public static function init()
  {
    spl_autoload_register(array(__CLASS__, 'load'));
  }

  public static function load($class)
  {
    switch ($class) {
    case 'vfsStream':
    case 'vfsStreamWrapper':
    case 'vfsStreamDirectory':
    case 'vfsStreamFile':
      @include 'vfsStream/vfsStream.php';
      break;

    case 'Graph':
      @include 'jpgraph/jpgraph.php';
      //@include 'jpgraph/jpgraph_log.php';
      break;

    case 'ScatterPlot':
      @include 'jpgraph/jpgraph_scatter.php';
      break;

    default:
      $file = dirname(__FILE__) . '/../' . str_replace('\\', '/', $class) . '.php';
      if (!file_exists($file)) {
        throw new Exception("Tried to load $class." . PHP_EOL .
                            "File: $file - not found Exiting." . PHP_EOL);
      }

      require_once $file;
      break;
    }
  }
}
