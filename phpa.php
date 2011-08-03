#!/usr/bin/php
<?php

define('DEBUG', 0);

require_once dirname(__FILE__) . '/Config/init.php';

use Config\Config;

use Analyzers\CyclomaticComplexityAnalyzer;
use Analyzers\DependencyAnalyzer;
use Analyzers\SourceAnalyzer;
use Analyzers\UnitAnalyzer;
use Analyzers\WarningAnalyzer;
use Analyzers\FrequencyAnalyzer;
use Analyzers\XMLAnalyzer;

use Reporters\DatabaseReporter;
use Reporters\DiagramReporter;
use Reporters\HTMLReporter;
use Reporters\StdoutReporter;
use Reporters\TextReporter;
use Reporters\XMLReporter;

echo 'PHP Analyzer by Alexander Olsson - Version ' . Config::$version . "\n\n";

/**
 * Prints usage and terminates the application
 */
function usage() {
  echo "Usage:\n";
  echo "\t phpa [options] [[-i FILE [-i FILE2 ..]]|-xml XMLFILE]\n\n";
  echo "Options:\n";
  echo "\t -i --input \tInput file/folder. The system which should be analyzed. May not coexist with '-xml'.\n";
  echo "\t -xml       \tInput xml file. Use an XML file as input. May not coexist with '-i'.\n";
  echo "\t -t --type  \tReport type. allowed values 'stdout', 'text', 'diagram' (see '-dt'), 'xml', 'html' and 'db'.\n";
  echo "\t -dt        \tDiagram type. Datas on axises, colon separated, x:y. Example: complexity:frequency\n";
  echo "\t -ds        \tDiagram scales. The axis scales. Any combination of 'int', 'lin', 'log', 'text' and 'dat'. E.g. 'loglog'.\n";
  echo "\t -xdbt      \tXdebug Trace folder. A folder containing files from xdebug trace.\n";
  echo "\t -o --out   \tOutput file. The file where reports should be written.\n";
  echo "\n";
  echo "Example:\t phpa -t xml -o analysis.xml -i function_library/\n";
  echo "\n";
  if (DEBUG) var_dump(debug_backtrace());
  exit(0);
}

/**
 * Finds the next value of input flags, returns it and advances i
 *
 * @param   int    $i    The flag counter
 * @param   array  $argv The array with cli flags.
 * @returns string       The value of the switch at i.
 */
function flagval(&$i, $argv) {
  if (empty($argv[$i+1])) {
    usage();
  }
  return $argv[++$i];
}

function elog($message, $type = 'Warning') {
  echo "$type: $message\n";
}

if ($argc < 2) {
  usage();
}

$input_path  = "";
$data        = array();
$files       = array();
$analyzers   = array();
for ($i = 1; $i < $argc; $i++) {
  switch($argv[$i]) {
    case '-t':
    case '--type':
      $report_t = flagval($i, $argv);
      if ($report_t === 'stdout') {
        $report_f = '/dev/null';
      }
      break;

    case '-o':
    case '--output':
      $report_f = flagval($i, $argv);
      break;

    case '-xdbt':
      $analyzers[FrequencyAnalyzer::$dataName] = new FrequencyAnalyzer();
      parse_fs($data['xdbt'], flagval($i, $argv), 'xt');
      break;

    case '-xdbtl':
      $xdbtl = flagval($i, $argv);
      break;

    case '-xml':
      $analyzers['xml'] = new XMLAnalyzer(flagval($i, $argv));
      break;

    case '-dt':
      $dt = flagval($i, $argv);
      break;

    case '-ds':
      $ds = flagval($i, $argv);
      break;

    case '-i':
    case '--input':
      $analyzers[UnitAnalyzer::$dataName]                 = new UnitAnalyzer();
      $analyzers[SourceAnalyzer::$dataName]               = new SourceAnalyzer();
      $analyzers[CyclomaticComplexityAnalyzer::$dataName] = new CyclomaticComplexityAnalyzer();
      $analyzers[DependencyAnalyzer::$dataName]           = new DependencyAnalyzer();

      $input_path = flagval($i, $argv);
      parse_fs($data['files'], $input_path, 'php');
      break;

    default:
      usage();
      break;
  }
}

/*
 * Analyzers which always can be run,
 * as they do not depend on what type
 * of input we have.
 */
$analyzers[WarningAnalyzer::$dataName] = new WarningAnalyzer();


if (
   (!empty($report_t) && empty($report_f)) ||
   (empty($report_t) && !empty($report_f))
   )
{
   usage();
}

/* Default report type to standard output */
if (empty($report_t)) {
  $report_t = 'stdout';
  $report_f = '/dev/null';
}

if (DEBUG) {
  echo "Starting analysis, used memory: " . (memory_get_usage()/1000000) . " MB\n";
}
$start = microtime(true);
foreach ($analyzers as $a) {
  try {
    echo "Analyzing {$a->describe()}.";
    $s = microtime(true);
    $a->analyze($data);
    $d = microtime(true) - $s;
    echo ' (' . round($d,2) . "s)\n";
  } catch (Exception $e) {
    elog($e->getMessage());
  }
}
$dur = microtime(true) - $start;

echo "Analyzing using " . count($analyzers) . " analyzers took $dur s.\n";

if (DEBUG) {
  $f = pathinfo($report_f);
  $fh = fopen("dbg_time_{$f['filename']}", 'w');
  fwrite($fh, "Analyzing using " . count($analyzers) . " analyzers took $dur s.\n");
  fclose($fh);
}

trim_file_paths($data, $input_path);
switch ($report_t) {
  case 'stdout':
    $reporter = new StdoutReporter($data, $report_f);
    break;

  case 'text':
    $reporter = new TextReporter($data, $report_f);
    break;

  case 'html':
    $reporter = new HTMLReporter($data, $report_f);
    break;

  case 'diagram':
    $reporter = new DiagramReporter($data, $report_f, $dt, $ds);
    break;

  case 'db':
    $reporter = new DatabaseReporter($data, $report_f);
    break;

  default:
    elog("Report type not supported: $report_t. Defaulting to xml.");

  case 'xml':
    $reporter = new XMLReporter($data, $report_f);
    break;
}

echo "Writing {$reporter->describe()} report.\n";

try {
  $reporter->report();
} catch (Exception $e) {
  elog($e->getMessage(),'Error');
  exit(1);
}

if ($report_f !== '/dev/null') {
  echo "Report written to $report_f";
}

?>

