<?php
require_once dirname(__FILE__) . '/database.php';
require_once dirname(__FILE__) . '/config.php';
Database::init(Config::$db);

if (isset($_POST['submit'])) {
  $status = $_POST['status'];
  echo "status:$status";
  if ($status == 'done') {
    Database::setStatus();
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>PHP Analyzer report</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script type="text/javascript" src="js/script.js"></script>
  <script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<h1>Analysis report - PHP Analyzer</h1>

<h2>Statistics</h2>
<?php
$stats = Database::getStatistics();
?>
<table>
  <tr>
    <td>Number of files examined</td>
    <td><?=$stats['number_of_files']; ?></td>
  </tr>
  <tr>
    <td>Number of units</td>
    <td><?=$stats['number_of_units']; ?></td>
  </tr>
  <tr>
    <td>Total unit SLOC</td>
    <td><?=$stats['total_unit_sloc']; ?></td>
  </tr>
  <tr>
    <td>Average SLOC/unit</td>
    <td><?=$stats['average_sloc_unit']; ?></td>
  </tr>
  <tr>
    <td>Average complexity</td>
    <td><?=$stats['average_complexity']; ?></td>
  </tr>
  <tr>
    <td>Mean SLOC between complexity</td>
    <td><?=$stats['mean_sloc_complexity']; ?></td>
  </tr>
  <tr class="error">
    <td>Unparsable units</td>
    <td><?=$stats['errors']; ?></td>
  </tr>
  <tr class="warning">
    <td>Warnings</td>
    <td><?=$stats['warnings']; ?></td>
  </tr>
</table>

<h2>Units</h2>
<p>
  <div class="error"></div>Red lines had errors while parsing. Data may not be reliable.
</p>
<p>
  <div class="warn"></div>Yellow lines had warnings while parsing (Duplicates). Frequency column may not be reliable.
</p>
<?php
$units = Database::getAll();
?>
<div id="statusboard">
  <img src="images/done.png" onclick="set_status('done')">
  <img src="images/not_done.png" onclick="set_status('not_done')">
  <img src="images/waiting.png" onclick="set_status('waiting')">
</div>
<form action="index.php" method="post">
  <table class="units">
    <tr>
      <th></th>
      <th>Unit name</th>
      <th>File</th>
      <th>Row</th>
      <th>Frequency</th>
      <th>Complexity</th>
      <th>Dependencies<br>(int / ext)</th>
      <th>SLOC</th>
    </tr>
    <?php 
    $counter = 0;
    foreach ($units as $unit) {
      $codeId = md5("{$unit['fnc']}:{$unit['file']}");
    
      $class = "";
      if ($unit['err'] == 1) {
        $class = 'error';
      } else if ($unit['wrn'] == 1) {
        $class = 'warning';
      }
    ?>
    <tr class="<?= $class; ?>">
      <td>
        <?php
        $status_img = 'not_done.png';
        if ($unit['status'] == Database::STATUS_DONE) {
          $status_img = 'done.png';
        } else if ($unit['status'] == Database::STATUS_WAITING) {
          $status_img = 'waiting.png';
        }
        ?>
        <img src="images/<?= $status_img; ?>"
             class="clickable"
             onclick="show_statusboard($(this), '<?= $unit['fnc'] ?>', '<?= $unit['file'] ?>')">
      </td>
      <td class="clickable" onclick="toggle_source('<?= $codeId; ?>');" >
        <?= $unit['fnc']; ?>
      </td>
      <td><?= $unit['file']; ?></td>
      <td><?= $unit['row']; ?></td>
      <td><?= $unit['frequency']; ?></td>
      <td><?= $unit['complexity']; ?></td>
      <td><?= $unit['dependency']; ?></td>
      <td><?= $unit['sloc']; ?></td>
    </tr>
    <div class="source-viewer" onclick="toggle_source('<?= $codeId; ?>');" id="<?= $codeId; ?>">
      <pre><?= htmlspecialchars($unit['src']); ?></pre>
    </div>
  <?php 
  $counter++; 
  }
  ?>
  </table>
</form>

</body>
</html>

