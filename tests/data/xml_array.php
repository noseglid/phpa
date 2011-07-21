<?php

$array2html_result =
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>PHP Analyzer report</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="script.js"></script>
</head>
<body>
<h1>Analysis report - PHP Analyzer</h1>
<h2>Statistics</h2>
<table>
<tr>
<td>Number of files examined</td>
<td>1</td>
</tr>
<tr>
<td>Number of units</td>
<td>1</td>
</tr>
<tr>
<td>Total unit SLOC</td>
<td>20</td>
</tr>
<tr>
<td>Average SLOC/unit</td>
<td>20</td>
</tr>
<tr>
<td>Average complexity</td>
<td>2</td>
</tr>
<tr>
<td>Mean SLOC between complexity</td>
<td>10</td>
</tr>
<tr>
<td>Unparsable units</td>
<td>0</td>
</tr>
<tr>
<td>Warnings</td>
<td>0</td>
</tr>
</table>
<h2>Units</h2>
<table class="units">
<tr>
<th>Unit name</th>
<th>File</th>
<th>Row</th>
<th>Frequency</th>
<th>Complexity</th>
<th>Dependencies<br>(int / ext)</th>
<th>SLOC</th>
</tr>
<tr onclick="
              toggle_source(\'some/path/f.ext_10_a\');
            ">
<td>a</td>
<td>some/path/f.ext</td>
<td>10</td>
<td></td>
<td>2</td>
<td>0/0</td>
<td>20</td>
</tr>
<div class="source-viewer" onclick="
              toggle_source(\'some/path/f.ext_10_a\');
            " id="some/path/f.ext_10_a"><pre></pre></div>
</table>
</body>
</html>
';

$array2xml_data = array(
  array(
    array(
      ),
'<?xml version="1.0"?>
<?xml-stylesheet type="text/xsl" href="base.xsl"?>
<root>
  <count/>
</root>
'),

  array(
    array('data1' => array('a' => array('aa' => 1, 'ab' => 2)),
          'data2' => array('b' => array('ba' => 3, 'bb' => 4))),
'<?xml version="1.0"?>
<?xml-stylesheet type="text/xsl" href="base.xsl"?>
<root>
  <count>
    <data1>1</data1>
    <data2>1</data2>
  </count>
  <data1>
    <a>
      <aa>1</aa>
      <ab>2</ab>
    </a>
  </data1>
  <data2>
    <b>
      <ba>3</ba>
      <bb>4</bb>
    </b>
  </data2>
</root>
')
);

