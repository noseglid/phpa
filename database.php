<?php

class Database
{

  const STATUS_NOT_DONE = 0;
  const STATUS_DONE     = 1;
  const STATUS_WAITING  = 2;

  private static $db;
  private static $db_exists;

  public static function init($filename = 'units.sqlite')
  {
    self::$db_exists = file_exists($filename);
    self::$db = new PDO('sqlite:' . $filename);
    self::$db->query('PRAGMA foreign_keys = ON');
    self::createTable();
  }

  private static function createTable()
  {
    self::$db->beginTransaction();

    $query = "CREATE TABLE IF NOT EXISTS units (
                fnc           TEXT    NOT NULL,
                file          TEXT    NOT NULL,
                row           INTEGER NOT NULL DEFAULT 0,
                frequency     INTEGER NOT NULL DEFAULT 0,
                complexity    INTEGER NOT NULL DEFAULT 0,
                dependency    TEXT    NOT NULL DEFAULT '0 / 0',
                depsum        INTEGER NOT NULL DEFAULT 0,
                sloc          INTEGER NOT NULL DEFAULT 0,
                src           TEXT,
                wrn           INTEGER NOT NULL DEFAULT 0,
                err           INTEGER NOT NULL DEFAULT 0,
                PRIMARY KEY (fnc, file)
              )";

    if (self::$db_exists) {
      $query = "DROP TABLE IF EXISTS units";
    }
    self::$db->exec($query);

    $query = 'CREATE TABLE IF NOT EXISTS status (
                fnc           TEXT    NOT NULL,
                file          TEXT    NOT NULL,
                status        INTEGER NOT NULL DEFAULT 0,
                FOREIGN KEY (fnc, file) REFERENCES units,
                PRIMARY KEY (fnc, file)
              )';
    self::$db->exec($query);

    return self::$db->commit();
  }

  public static function getAll()
  {
    $result = array();
    $query = 'SELECT u.*, s.status FROM units u
              LEFT JOIN status s
              ON s.fnc=u.fnc AND s.file=u.file
              ORDER BY u.frequency DESC';
    $sth = self::$db->prepare($query);
    if ($sth) {
      $sth->execute();
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    return $result;
  }

  public static function insertUnits($units)
  {
    self::$db->beginTransaction();

    foreach ($units as $unit) {
      $col = '';
      $val = '';
      $val_arr = array();
      foreach ($unit as $k => $v) {
        if ($k === 'src_strip') continue;
        $k = sqlite_escape_string($k);
        $col .= "$k, ";
        $val .= ":$k, ";
        $val_arr[":$k"] = sqlite_escape_string($v);
      }
      $col = trim($col, ', ');
      $val = trim($val, ', ');

      $stmt_units = self::$db->prepare("INSERT INTO units ({$col}) VALUES({$val})");
      $stmt_units->execute($val_arr);
 
      $stmt_status = self::$db->prepare("INSERT INTO status (fnc, file)
                                         VALUES(:fnc, :file)");
      $stmt_status->execute(array(":fnc" => $unit['fnc'], ":file" => $unit['file']));
    }
    return self::$db->commit();
  }

  public static function setStatus($fnc, $file, $status)
  {
    $query = "UPDATE status
              SET status={$status}
              WHERE fnc='{$fnc}' AND file='{$file}'";
    if (self::$db->exec($query) > 0) {
      return true;
    }
    return false;
  }

  public static function getStatistics()
  {
    $result = array();
    $query = "SELECT COUNT(DISTINCT u.file) AS number_of_files,
                     COUNT(u.fnc) AS number_of_units,
                     SUM(u.sloc) AS total_unit_sloc,
                     (SUM(u.sloc)/COUNT(u.fnc)) AS average_sloc_unit,
                     (SUM(u.complexity)/COUNT(u.fnc)) AS average_complexity,
                     (SUM(u.sloc)/SUM(u.complexity)) AS mean_sloc_complexity,
                     SUM(err) AS errors,
                     SUM(wrn) AS warnings
              FROM units u";
    $sth = self::$db->prepare($query);
    if ($sth) {
      $sth->execute();
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    return $result[0];
  }
}

