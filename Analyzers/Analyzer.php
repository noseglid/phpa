<?php

namespace Analyzers;

abstract class Analyzer {
  private $flip, $count;
  private $flips, $start, $total_flips;

  public static $dataName;

  public function __construct() {}
  abstract public function analyze(&$data);
  abstract public function describe();

  protected function initProgress($total, $flips = 10) {
    $this->total_flips = $flips;
    $this->flip = $total/$this->total_flips;
    $this->start = microtime(true);
    $this->count = 0;
    $this->flips = 0;
  }

  protected function progress() {
    if (++$this->count >= $this->flip) {
      $dur = microtime(true) - $this->start;
      $this->flips++;
      $done = $this->flips/$this->total_flips;
      $total_time = $dur/$done;
      if ($dur > 10) {
        echo "\n[" . $this->__toString() . "]\n";
        echo "        Elapsed: " . format_seconds(round($dur)) . " s\n";
        echo " Est. Time left: " . format_seconds(round($total_time-$dur)) . " s\n";
        echo "Est. Total time: " . format_seconds(round($total_time)) . " s \n";
        echo "      Completed: " . round(100*$this->flips/$this->total_flips, 2) ." %\n";
      } else {
        echo ".";
      }
      $this->count = 0;
    }
  }

  public function __toString()
  {
    return get_called_class();
  }

  public static function text() {
    return get_called_class();
  }

  private function timerep($sec)
  {
    return date("G:i:s", $sec-3600); //FIXME: only works in GMT+1 timezone.
  }
}
