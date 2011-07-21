<?php
require_once 'functions/common.php';

final class Config {
  /**
   * The version number of this release
   */
  public static $version = '0.1';

  /**
   * Defined additional function names which should be interpreted as
   * external dependencies.
   */
  public static $add_ext_dep = array(
    'filesystem' => array('/^$/'),
    'web'        => array('/^$/'),
    'database'   => array('/^$/')
  );
}

