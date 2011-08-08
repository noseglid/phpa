<?php

namespace Analyzers;

class FrequencyAnalyzer extends analyzer {
  public static $dataName = 'frequency';

  public function analyze(&$data) {
    if (empty($data['xdbt'])) {
      throw new Exception('No xdebug trace file specified');
    }

    if (empty($data['units'])) {
      throw new Exception(UnitAnalyzer::__toString() . " must be run prior to $this\n");
    }

    $count = count($data['xdbt']);

    $this->initProgress($count, min(1000, $count));

    for ($i = 0; $i < $count; $i++) {
      $this->progress();

      $o = $this->analyzeXdebugTrace($data['xdbt'][$i]);
      foreach ($data['units'] as &$unit) {
        $unit[FrequencyAnalyzer::$dataName] += $o["{$unit['fnc']}"];
      }
    }
    unset($data['xdbt']);
  }

  public function analyzeXdebugTrace($file) {
    if (false === ($fh = @fopen($file, 'r'))) {
      throw new Exception("Unable to open '$file' for reading");
    }
    $out = array();
    $in_trace = false;
    while (false !== ($line = @fgets($fh))) {
      if (preg_match('/TRACE START[ ]+\[.+\]/', $line) === 1) {
        $in_trace = true;
        continue;
      }
      if (preg_match('/TRACE END[ ]+\[.+\]/', $line) === 1) {
        $in_trace = false;
        continue;
      }

      if (!$in_trace) {
        continue;
      }
      $trace = preg_split('/[\s]+/', $line);
      if ($trace[2] != 0 || $trace[6] != 1) {
        continue;
      }

      $fnc  = trim($trace[5]);
      $out["$fnc"]++;
    }
    return $out;
  }

  public function describe() {
    return 'unit frequency';
  }
}
