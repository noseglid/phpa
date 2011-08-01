<?php

class DiagramReporter extends Reporter {
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


