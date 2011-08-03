<?php

namespace Functions\XML;

use \DOMDocument;
use \XSLTProcessor;

class Array2HTML extends Array2XML
{
  public function get() {
    $xsl = new XSLTProcessor();
    $xsldoc = new DOMDocument();
    $xsldoc->load('Resources/base.xsl');
    $xsl->importStyleSheet($xsldoc);

    return $xsl->transformToXML($this->dom);
  }
}
