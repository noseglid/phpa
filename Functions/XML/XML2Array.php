<?php

namespace Functions\XML;

class XML2Array
{
  public static function get($elem) {
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
          $array[$name] = self::get($c);
        }
      }
    }
    return $array;
  }
}
