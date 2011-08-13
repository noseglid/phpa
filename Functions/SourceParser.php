<?php

namespace Functions;

class SourceParser
{
   public function stripSingle($line) {
     $pattern     = array("/\\\'/",
                          '/\\\"/',
                          '/\'{1}[.]*[^\']*\'{1}/',
                          '/"{1}[.]*[^"]*"{1}/',
                          '/\/\*.*\*\//',
                          '/(#|\/\/).*/');
     $replacement = array('ESCAPED_SINGLE_QUOTE',
                          'ESCAPED_DOUBLE_QUOTE',
                          'SINGLE_QUOTE',
                          'DOUBLE_QUOTE',
                          'SLST_COMMENT',
                          'ONLI_COMMENT');
     return preg_replace($pattern, $replacement, $line);
}

  public function stripMultiple($src) {
    $pattern     = array('/\/\*.*\*\//s',
                         '/(\'|").*(\'|")/s');
    $replacement = array('MULI_COMMENT',
                         'MULI_STR');
    return preg_replace($pattern, $replacement, $src);
  }
}
