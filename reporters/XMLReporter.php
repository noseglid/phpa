<?php

class XMLReporter extends Reporter {

  public function report() {
    $xml = new array2xml($this->data);
    $this->write($xml);
  }
  public function describe() {
    return "XML";
  }
}


