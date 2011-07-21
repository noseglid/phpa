<?php

class array2xml
{
  protected $dom;

  function __construct($array) {
    $this->dom = new DOMDocument('1.0');
    $this->dom->formatOutput = true;
    $this->dom->appendChild(
    $this->dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="base.xsl"'));

    $r = $this->dom->createElement('root');
    $c = $this->dom->createElement('count');

    $r->appendChild($c);
    $this->set_counts($array, $c);
    $this->struct_xml($array, $r);
    $this->dom->appendChild($r);
  }

  function struct_xml($array, &$p) {
    foreach($array as $k => $v) {
      if(is_array($v)) {
        $tag = preg_replace('/^[0-9]{1,}/', 'data', $k);
        $node = $this->dom->createElement($tag);
        $this->struct_xml($v, $node);
        $p->appendChild($node);
      } else {
        $tag = preg_replace('/^[0-9]{1,}/', 'data', $k);
        $p->appendChild($this->dom->createElement($tag, htmlspecialchars($v)));
      }
    }
  }

  function set_counts($array, &$c) {
    foreach ($array as $k => $v) {
      $c->appendChild($this->dom->createElement($k, count($v)));
    }
  }

  function __toString() {
    return $this->dom->saveXML();
  }
}

class array2html extends array2xml
{
  function __toString() {
    $xsl = new XSLTProcessor();
    $xsldoc = new DOMDocument();
    $xsldoc->load('base.xsl');
    $xsl->importStyleSheet($xsldoc);

    return $xsl->transformToXML($this->dom);
  }
}

function xml2array($elem) {
  $i = 0;
  if ($elem->children()) {
    foreach ($elem as $c) {
      if ($c->getName() === 'count') {
        continue;
      }
      $name = $c->getName() === 'data' ?
        $i++ : $c->getName();
      if (!$c->children()) {
        $array[$name] = "$c";
      } else {
        $array[$name] = xml2array($c);
      }
    }
  }
  return $array;
}

