#!/usr/bin/php
<?php

define('DEBUG', 0);

require_once dirname(__FILE__) . '/Config/init.php';

use Config\Config;

use Functions\Filesystem;

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
  printf("Usage:%s", PHP_EOL);
  printf("  phpa [options] [-i FILE [-i FILE2 ..]|-xml XMLFILE]%s%s", PHP_EOL, PHP_EOL);
  printf("Options:%s", PHP_EOL);
  printf(PHP_EOL);
  printf("  -i --input   Input file/folder. The system which should be analyzed.%s", PHP_EOL);
  printf("               May not coexist with '-xml'.%s", PHP_EOL);
  printf("  -xml         Input xml file. Use an XML file as input.%s", PHP_EOL);
  printf("               May not coexist with '-i'.%s", PHP_EOL);
  printf("  -t --type    Report type. allowed values 'stdout', 'text',%s", PHP_EOL);
  printf("               'diagram','xml', 'html' and 'db'.%s", PHP_EOL);
  printf("  -dt          Diagram type. Datas on axises, colon separated, x:y.%s", PHP_EOL);
  printf("               Only valid when --type is diagram.%s", PHP_EOL);
  printf("               Default: complexity:frequency%s", PHP_EOL);
  printf("  -ds          Diagram scales. The axis scales, colo separated, x:y.%s", PHP_EOL);
  printf("               Only valid when --type is diagram.%s", PHP_EOL);
  printf("               Any combination of 'int', 'lin', 'log', 'text' and 'dat'.%s", PHP_EOL);
  printf("               Default: 'log:log'.%s", PHP_EOL);
  printf("  -xdbt        Xdebug Trace folder. A folder containing files from xdebug trace.%s", PHP_EOL);
  printf("  -o --out     Output file. The file where reports should be written.%s", PHP_EOL);
  printf(PHP_EOL);
  printf("Example: phpa -t xml -o analysis.xml -i function_library/%s", PHP_EOL);
  printf(PHP_EOL);
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
  echo "\n-!- $type: $message\n";
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
      Filesystem::findByExtension($data['xdbt'], flagval($i, $argv), 'xt');
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
      Filesystem::filesByExtension($data['files'], $input_path, 'php');
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

if ((!empty($report_t) &&  empty($report_f)) ||
     (empty($report_t) && !empty($report_f))) {
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

Filesystem::trimFilePaths($data, $input_path);
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
    if (!isset($dt)) {
      printf('Data type (-dt) not set. Defaulting to \'sloc:complexity\'%s', PHP_EOL);
      $dt = 'sloc:complexity';
    }
    if (!isset($ds)) {
      printf('Data scales (-ds) not set. Defaulting to \'log:log\'%s', PHP_EOL);
      $ds = 'log:log';
    }
    $reporter = new DiagramReporter($data, $report_f, $dt, $ds);
    break;

  case 'db':
    $reporter = new DatabaseReporter($data, $report_f);
    break;

  case 'xml':
    $reporter = new XMLReporter($data, $report_f);
    break;

  default:
    usage();
}

echo "Writing {$reporter->describe()} report.\n";

try {
  $reporter->report();
} catch (Exception $e) {
  elog($e->getMessage(), 'Error');
  exit(1);
}

if ($report_f !== '/dev/null') {
  printf('Report written to \'%s\'.%s', $report_f, PHP_EOL);
}
