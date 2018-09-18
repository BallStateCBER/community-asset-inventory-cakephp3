<?php
/**
 * @var \App\View\AppView $this
 * @var array $colors
 * @var string $barometerStatus
 */
$gaugeValues = [
    'ideal' => 6,
    'growth' => 4,
    'warning' => 4,
    'bad' => 2
];
$gaugeValue = $gaugeValues[$barometerStatus];
$intermediateColor = $barometerStatus == 'growth' ? $colors['growth'] : $colors['warning'];
?>
<?php $this->append('script'); ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages': ['gauge']});
      google.charts.setOnLoadCallback(drawGauge);

      function drawGauge() {
        let data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['', <?= $gaugeValue ?>]
        ]);

        const options = {
          width: 400,
          height: 120,
          redFrom: 1,
          redTo: 3,
          yellowColor: <?= json_encode($intermediateColor) ?>,
          yellowFrom: 3,
          yellowTo: 5,
          greenFrom: 5,
          greenTo: 7,
          majorTicks: ['Bad', '', '', 'Ideal'],
          minorTicks: 3,

          max: 7,
          min: 1,
        };

        let gaugeChart = new google.visualization.Gauge(document.getElementById('gauge-container'));

        gaugeChart.draw(data, options);
      }
    </script>
<?php $this->end(); ?>

<div id="gauge-container" style="width: 400px; height: 120px;"></div>
