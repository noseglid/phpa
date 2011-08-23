<?php

namespace Reporters;

use Functions\XML\Array2HTML;

class HTMLReporter extends Reporter {

  public function report() {
    $html = new Array2HTML($this->data);
    $this->write($html->get());
    copy('Resources/script.js', dirname($this->f) . '/script.js');
    copy('Resources/style.css', dirname($this->f) . '/style.css');
  }

  public function describe() {
    return "HTML";
  }
}
