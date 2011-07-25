<?php

class Database
{

  const STATUS_NOT_DONE = 0;
  const STATUS_DONE     = 1;
  const STATUS_WAITING  = 2;

  private static $db;

  public static function init($filename = 'units.sqlite')
  {
    self::$db = new PDO('sqlite:' . $filename);
    self::$db->query('PRAGMA foreign_keys = ON');
    self::createTable();
  }

  private static function createTable()
  {
    self::$db->beginTransaction();

    $query = 'CREATE TABLE IF NOT EXISTS units (
                fnc           TEXT    NOT NULL,
                file          TEXT    NOT NULL,
                row           INTEGER NOT NULL DEFAULT 0,
                frequency     INTEGER NOT NULL DEFAULT 0,
                complexity    INTEGER NOT NULL DEFAULT 0,
                dependency    TEXT    NOT NULL DEFAULT "0 / 0",
                depsum        INTEGER NOT NULL DEFAULT 0,
                sloc          INTEGER NOT NULL DEFAULT 0,
                src           TEXT,
                wrn           INTEGER NOT NULL DEFAULT 0,
                err           INTEGER NOT NULL DEFAULT 0,
                PRIMARY KEY (fnc, file)
              )';
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
      $result = $sth->fetchAll();
    }
    return $result;
  }

  public static function insertUnits($units)
  {
    self::$db->beginTransaction();
    for ($i = 0; $i < count($units); $i++) {
      $col = '';
      $val = '';
      foreach ($units[$i] as $k => $v) {
        if ($k === 'src_strip') continue;
        $col .= "$k, ";
        $val .= "'$v', ";
      }
      $col = trim($col, ', ');
      $val = trim($val, ', ');

      $query = "INSERT INTO units ({$col}) VALUES({$val})";
      self::$db->exec($query);

      $query = "INSERT INTO status (fnc, file)
                VALUES('{$units[$i]['fnc']}','{$units[$i]['file']}')";
      self::$db->exec($query);
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
}

