<?php

namespace Functions;

class Filesystem
{
  public static function filesByExtension(&$files, $file, $ext)
  {
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

        $this->parseFilesystem($files, $path . $df, $ext);
      }
    }
  }

  public static function trimFilePaths(&$data, $input_path) {
    if ($input_path != "") {
      foreach ($data['files'] as &$file) {
        $file = str_replace($input_path, '', $file);
      }
      foreach ($data['units'] as &$unit) {
        $unit['file'] = str_replace($input_path, '', $unit['file']);
      }
    }
  }
}
