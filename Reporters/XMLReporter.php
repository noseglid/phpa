<?php

namespace Reporters;

use Functions\XML\Array2XML;

class XMLReporter extends Reporter
{
  public function report() {
    $xml = new Array2XML($this->data);
    $this->write($xml->get());
  }

  public function describe() {
    return "XML";
  }
}
