<?php

namespace Reporters;

use Analyzers\UnitAnalyzer;

class StdoutReporter extends Reporter {

  public function report() {
    echo "Files: " . count($this->data['files']) . "\n";
    echo "Units: " . count($this->data[UnitAnalyzer::$dataName]) . "\n";
  }

  public function describe() {
    return "stdout";
  }
}


