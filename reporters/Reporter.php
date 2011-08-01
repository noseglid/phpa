<?php

require_once 'functions/xml_array.php';
require_once 'functions/common.php';
require_once 'database.php';

require_once 'jpgraph/jpgraph.php';
require_once 'jpgraph/jpgraph_scatter.php';
require_once 'jpgraph/jpgraph_log.php';

abstract class Reporter {
  protected $data;
  protected $f;

  private $fh;
  private $file_open;

  public function __construct($data, $f) {
    $this->data = $data;
    $this->f    = $f;

    $this->file_open = false;
  }

  abstract public function report();
  abstract public function describe();

  private function openFile() {
    if ($file_open) {
      return;
    }

    $this->fh = @fopen($this->f, 'w');
    if (false === $this->fh) {
      throw new Exception("Unable to open file {$this->f} for reading");
    }

    $file_open = true;
  }

  protected function write($str) {
    $this->openFile();
    if (false === fwrite($this->fh, $str)) {
      throw new Exception("Unable to write to file {$this->f}");
    }
  }
}
