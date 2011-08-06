<?php

namespace Reporters;

use \Exception;
use \Graph;
use \ScatterPlot;

use Exceptions\DiagramException;

require_once 'jpgraph/jpgraph.php';
require_once 'jpgraph/jpgraph_scatter.php';
require_once 'jpgraph/jpgraph_log.php';

class DiagramReporter extends Reporter {
  private $dt, $scales;

  public function __construct($data, $f, $dt, $scales) {
    parent::__construct($data, $f);

    if (empty($dt)) {
      throw new Exception('Data type not specified.');
    }
    if (empty($scales)) {
      throw new Exception('Data type not specified.');
    }

    list($this->dt['x'], $this->dt['y']) = preg_split('/:/', $dt);
    $this->scales                        = $scales;
  }

  public function report() {
    $xdata = array();
    $ydata = array();

    foreach ($this->data['units'] as $unit) {
      if (1 == $unit['err']) {
        continue;
      }

      $dx = $this->getValue($this->dt['x'], $unit);
      if (false === $dx) {
        throw new DiagramException(sprintf("Data '%s' (x-axis) is not available.",
                                           $this->dt['x']));
      }
      $dy = $this->getValue($this->dt['y'], $unit);
      if (false === $dy) {
        throw new DiagramException(sprintf("Data '%s' (x-axis) is not available.",
                                           $this->dt['y']));
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
    $graph->title->Set("{$this->dt['x']}:{$this->dt['y']}");

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
