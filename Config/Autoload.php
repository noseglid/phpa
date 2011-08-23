<?php

class Autoload
{
  public static function init()
  {
    spl_autoload_register(array(__CLASS__, 'load'));
  }

  public static function load($class)
  {
//    printf('Attempting to load class %s%s', $class, PHP_EOL);
    $file = dirname(__FILE__) . '/../' . str_replace('\\', '/', $class) . '.php';
    if (!file_exists($file)) {
      throw new Exception("Tried to load $class." . PHP_EOL .
                          "File: $file - not found Exiting." . PHP_EOL);
    }

    require_once $file;
  }
}
