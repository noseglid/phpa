<?php

namespace Analyzers;

use \Exception;

class CyclomaticComplexityAnalyzer extends Analyzer {
  public static $dataName = 'complexity';

  public function analyze(&$data) {
    if (empty($data['units'])) {
      throw new Exception(UnitAnalyzer::text() . " must be run prior to $this\n");
    }

    $this->initProgress(count($data['units']));

    foreach ($data['units'] as $key => $unit) {
      $this->progress();
      $data[UnitAnalyzer::$dataName][$key]
           [CyclomaticComplexityAnalyzer::$dataName] =
             $this->complexity($unit);
    }
  }

  public function complexity($unit) {
    if (isset($unit['err'])) {
      return -1;
    }

    $source            = $unit['src_strip'];
    $counts            = array();
    $counts['if']      = preg_match_all('/(else)?if.*\(.+/',      $source, $matches);
    $counts['olif']    = preg_match_all('/\?.*:/',                $source, $matches);
    $counts['andand']  = preg_match_all('/&&/',                   $source, $matches);
    $counts['case']    = preg_match_all('/case.+?:.*?break[;]/s', $source, $matches);
    $counts['default'] = preg_match_all('/default[ ]*:/',         $source, $matches);
    $counts['switch']  = preg_match_all('/switch.*\(.*\)\{?/',    $source, $matches);
    $counts['while']   = preg_match_all('/while.*\(.*\)/',        $source, $matches);
    $counts['for']     = preg_match_all('/for.*\(.*;.*;.*\)/',    $source, $matches);
    $counts['foreach'] = preg_match_all('/foreach.*\(.*as.*\)/',  $source, $matches);

    $nodes = 1;
    $edges = 0;

    // if and olif
    $nodes += 3*$counts['if'] + 3*$counts['olif'] + 3*$counts['andand'];
    $edges += 4*$counts['if'] + 4*$counts['olif'] + 4*$counts['andand'];

    //switch, case and default
    $nodes += 2*$counts['switch'] + 1*$counts['case'] - 2*$counts['default'];
    $edges += 2*$counts['switch'] + 2*$counts['case'] - 2*$counts['default'];

    //while
    $nodes += 3*$counts['while'];
    $edges += 4*$counts['while'];

    //for and foreach
    $nodes += 3*$counts['for'] + 3*$counts['foreach'];
    $edges += 4*$counts['for'] + 4*$counts['foreach'];

    return $edges - $nodes + 2;
  }

  public function describe() {
    return "complexity of units";
  }
}
