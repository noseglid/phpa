<?php

function use_folder($dir, $mode = 'require_once') {
  if (!file_exists($dir) || !is_dir($dir)) {
    return false;
  }
  $d = dir($dir);
  while(false !== ($entry = $d->read())) {
    if (1 === preg_match('/^[\.]{1,2}/', $entry) ||
        0 === preg_match('/\.php$/', $entry)) {
      continue;
    }
    eval("$mode '$dir/$entry';");
  }
  return true;
}

function parse_fs(&$files, $file, $ext) {
  if (!file_exists($file) || !is_readable($file))  {
    /* Not a valid file. */
    return;
  }

  if (is_file($file)) {
    $info = pathinfo($file);
    if(isset($info['extension']) && 0 === strcasecmp($info['extension'], $ext)) {
      $files[] = $file;
    }
    return;
  } else if (is_dir($file)) {
    $path = preg_replace('/\/\//', '/', $file . '/');
    $d = dir($path);
    while (false !== ($df = $d->read())) {
      if (1 === preg_match('/^[\.]{1,2}/', $df)) {
        continue;
      }

      parse_fs($files, $path . $df, $ext);
    }
  }
}

function interp_math($string) {
  if (0 === preg_match('/[0-9][0-9\+\-\*%\/\(\)]*/', $string)) {
    return false;
  }
  $f = create_function('', "return $string;");
  return $f();
}

function format_seconds($sec) {
  return date("G:i:s", $sec-3600);
}

function trim_file_paths(&$data, $input_path) {
  if ($input_path != "") {
    foreach ($data['files'] as &$file) {
      $file = str_replace($input_path, '', $file);
    }
    foreach ($data['units'] as &$unit) {
      $unit['file'] = str_replace($input_path, '', $unit['file']);
    }
  }
}


