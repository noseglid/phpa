<?php

namespace Analyzers;

use \Exception;
use Config\Config;

class DependencyAnalyzer extends Analyzer {
  public static $dataName = "dependency";
  private $data;
  private $fncs;

  public function analyze(&$data) {
    if (empty($data[UnitAnalyzer::$dataName])) {
      throw new Exception(UnitAnalyzer::text() . " must be run prior to $this\n");
    }
    $this->initProgress(count($data['units']));
    $this->setData($data);

    foreach ($data['units'] as $key => $unit) {
      $this->progress();
      $dep = $this->dependencies($unit);
      $data[UnitAnalyzer::$dataName][$key]
           [DependencyAnalyzer::$dataName] = $dep[0];
      $data[UnitAnalyzer::$dataName][$key]
           ['depsum']                      = $dep[1];
    }
  }

  public function setData($data) {
    $this->data = $data;

    /* Set internal functions to find internal deps */
    foreach ($data['units'] as $unit) {
      $this->fncs['int'][] = $unit['fnc'];
    }

    /* Use predefined list to find external deps */
    $this->fncs['ext'] =
      array(
        'filesystem' => array(
          '/basename/', '/chgrp/', '/chmod/', '/chown/', '/clearstatcache/', '/copy/',
          '/delete/', '/dirname/', '/disk_free_space/', '/disk_total_space/',
          '/diskfreespace/', '/fclose/', '/feof/', '/fflush/', '/fgetc/', '/fgetcsv/',
          '/fgets/', '/fgetss/', '/file_exists/', '/file_get_contents/',
          '/file_put_contents/', '/file/', '/fileatime/', '/filectime/', '/filegroup/',
          '/fileinode/', '/filemtime/', '/fileowner/', '/fileperms/', '/filesize/',
          '/filetype/', '/flock/', '/fnmatch/', '/fopen/', '/fpassthru/', '/fputcsv/',
          '/fputs/', '/fread/', '/fscanf/', '/fseek/', '/fstat/', '/ftell/', '/ftruncate/',
          '/fwrite/', '/glob/', '/is_dir/', '/is_executable/', '/is_file/', '/is_link/',
          '/is_readable/', '/is_uploaded_file/', '/is_writable/', '/is_writeable/',
          '/lchgrp/', '/lchown/', '/link/', '/linkinfo/', '/lstat/', '/mkdir/',
          '/move_uploaded_file/', '/parse_ini_file/', '/parse_ini_string/', '/pathinfo/',
          '/pclose/', '/popen/', '/readfile/', '/readlink/', '/realpath/', '/rename/', '/rewind/',
          '/rmdir/', '/set_file_buffer/', '/stat/', '/symlink/', '/tempnam/', '/tmpfile/',
          '/touch/', '/umask/', '/unlink/'
        ),
        'web'        => array(
          '/curl_/', '/fsockopen/', '/checkdnsrr/', '/closelog/', '/define_syslog_variables/',
          '/dns_/', '/gethost/', '/getmxrr/', '/getproto/', '/getservby/', '/header/', '/inet/',
          '/ip2long/', '/long2ip/', '/openlog/', '/pfsockopen/', '/setcookie/', '/setrawcookie/',
          '/socket_/', '/syslog/'
        ),
        'database'   => array(
          '/mysql_/', '/mysqli_/', '/odbc_/'
        )
      );
    foreach ($this->fncs['ext'] as $key => &$ext) {
      $ext = array_merge(Config::$add_ext_dep[$key], $ext);
    }
  }

  public function dependencies($unit) {
    $d = array('int' => 0, 'ext' => 0);

    preg_match_all('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\(.*?\))/',
                   $unit['src_strip'], $matches);
    $f = array_unique($matches[1]);

    /* Find internal dependencies */
    foreach ($f as $m) {
      if ($m == $unit['fnc']) {
        continue;
      }

      if (in_array($m, $this->fncs['int'])) {
        $d['int']++;
      }
    }

    /* Find external dependencies */
    $e_d = array();
    foreach ($f as $m) {
      if ($m == $unit['fnc']) {
        continue;
      }

      foreach ($this->fncs['ext'] as $k => $v) {
        foreach ($v as $p) {
          if (preg_match($p, $m)) {
            $e_d["$k"] = 1;
            break;
          }
        }
      }
    }

    $d['ext'] = array_sum($e_d);

    return array(implode(' / ', $d), array_sum($d));
  }

  public function describe() {
    return "unit dependencies";
  }
}
