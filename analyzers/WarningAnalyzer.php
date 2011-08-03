<?php

namespace Analyzers;

class WarningAnalyzer extends Analyzer {
  public static $dataName = 'wrn';

  public function analyze(&$data) {
    $this->initProgress(count($data['units']));

    foreach ($data['units'] as &$u2) {
      $this->progress();
      $u2[WarningAnalyzer::$dataName] = 0;
      foreach ($data['units'] as &$u1) {
        if ($u1 === $u2) {
          continue;
        }

        if ($u1['fnc'] === $u2['fnc']) {
          $u1[WarningAnalyzer::$dataName] = 1;
          $u2[WarningAnalyzer::$dataName] = 1;
        }
      }
    }
  }


  public function describe() {
    return 'unit warnings';
  }
}
