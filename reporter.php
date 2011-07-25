<?php

require_once 'functions/xml_array.php';
require_once 'functions/common.php';
require_once 'database.php';

require_once 'jpgraph/jpgraph.php';
require_once 'jpgraph/jpgraph_scatter.php';
require_once 'jpgraph/jpgraph_log.php';

abstract class Reporter {
  protected $data;
  protected $f;

  private $fh;
  private $file_open;

  public function __construct($data, $f) {
    $this->data = $data;
    $this->f    = $f;

    $this->file_open = false;
  }

  abstract public function report();
  abstract public function describe();

  private function openFile() {
    if ($file_open) {
      return;
    }

    $this->fh = @fopen($this->f, 'w');
    if (false === $this->fh) {
      throw new Exception("Unable to open file {$this->f} for reading");
    }

    $file_open = true;
  }

  protected function write($str) {
    $this->openFile();
    if (false === fwrite($this->fh, $str)) {
      throw new Exception("Unable to write to file {$this->f}");
    }
  }
}

class DatabaseReporter extends Reporter {
  public function report() {
    Database::init($this->f);
    Database::insertUnits($this->data['units']);
  }

  public function describe() {
    return "SQLite3 Database";
  }
}

class stdoutReporter extends Reporter {

  public function report() {
    echo "Files: " . count($this->data['files']) . "\n";
    echo "Units: " . count($this->data[UnitAnalyzer::$dataName]) . "\n";
  }

  public function describe() {
    return "stdout";
  }
}

class HTMLReporter extends Reporter {

  public function report() {
    $html = new array2html($this->data);
    $this->write($html);
    copy('script.js', dirname($this->f) . '/script.js');
    copy('style.css', dirname($this->f) . '/style.css');
  }

  public function describe() {
    return "HTML";
  }
}

class XMLReporter extends Reporter {

  public function report() {
    $xml = new array2xml($this->data);
    $this->write($xml);
  }
  public function describe() {
    return "XML";
  }
}

class textReporter extends Reporter {

  public function report() {
    $this->write(var_export($this->data, true));
  }
  public function describe() {
    return "text";
  }
}

class diagramReporter extends Reporter {
  private $datatype, $scales;

  public function __construct($data, $f, $dt, $scales) {
    parent::__construct($data, $f);
    if (empty($dt)) {
      $dt = 'complexity:frequency';
    }

    if (empty($scales)) {
      $scales = 'loglog';
    }

    $this->datatype = preg_split('/:/', $dt);
    $this->scales   = $scales;
  }

  public function report() {
    $xdata = array();
    $ydata = array();
    $xrange = $this->limits[0];
    $yrange = $this->limits[1];
    foreach ($this->data['units'] as $unit) {
      if (1 == $unit['err']) {
        continue;
      }

      $dx = $this->getValue($this->datatype[0], $unit);
      $dy = $this->getValue($this->datatype[1], $unit);
      if ($dx < 0 || $dy < 0) {
        var_dump($unit);
      }
      if (false === $dx || false === $dy) {
        var_dump($unit);
        throw new Exception("Unable to interpret axis data: ".
            $this->datatype[0] . ':' . $this->datatype[1]);
      }
      $xdata[] = $dx;
      $ydata[] = $dy;
    }

    $this->doGraph($xdata, $ydata);
  }

  public function getValue($datastring, $holder) {
    $mathstr = $datastring;
    foreach ($holder as $key => $value) {
      $mathstr = preg_replace("/$key/", $value, $mathstr);
    }
    return interp_math($mathstr);
  }

  public function doGraph($xdata, $ydata) {
    $graph = new Graph(1024, 768);
    $graph->SetScale($this->scales);
    $graph->img->Setmargin(40, 40, 40, 40);
    $graph->SetShadow();
    $graph->title->SetFont(FF_FONT1, FS_BOLD);
    $graph->title->Set("{$this->datatype[0]}:{$this->datatype[1]}");

    $sp = new ScatterPlot($ydata, $xdata);
    $sp->mark->SetSize(2);
    $sp->mark->SetType(MARK_FILLEDCIRCLE);
    $graph->add($sp);
    $graph->Stroke($this->f);
  }

  public function describe() {
    return "diagram";
  }
}


