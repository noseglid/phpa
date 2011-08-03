<?php

namespace Functions\XML;

use \DOMDocument;

class Array2XML
{
  protected $dom;

  public function __construct($array) {
    $this->dom = new DOMDocument('1.0');
    $this->dom->formatOutput = true;
    $this->dom->appendChild(
    $this->dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="base.xsl"'));

    $r = $this->dom->createElement('root');
    $c = $this->dom->createElement('count');

    $r->appendChild($c);
    $this->set_counts($array, $c);
    $this->structXML($array, $r);
    $this->dom->appendChild($r);
  }

  public function get() {
    return $this->dom->saveXML();
  }

  private function structXML($array, &$p) {
    foreach($array as $k => $v) {
      if(is_array($v)) {
        $tag = preg_replace('/^[0-9]{1,}/', 'data', $k);
        $node = $this->dom->createElement($tag);
        $this->structXML($v, $node);
        $p->appendChild($node);
      } else {
        $tag = preg_replace('/^[0-9]{1,}/', 'data', $k);
        $p->appendChild($this->dom->createElement($tag, htmlspecialchars($v)));
      }
    }
  }

  private function set_counts($array, &$c) {
    foreach ($array as $k => $v) {
      $c->appendChild($this->dom->createElement($k, count($v)));
    }
  }
}
