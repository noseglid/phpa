<?php

namespace Reporters;

class TextReporter extends Reporter {

  public function report() {
    $this->write(var_export($this->data, true));
  }
  public function describe() {
    return "text";
  }
}


