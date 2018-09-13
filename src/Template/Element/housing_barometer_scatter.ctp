<?php
/**
 * @var \App\View\AppView $this
 * @var array $chartData
 * @var array $colors
 * @var array $rhvs
 * @var float $stateGrowthValue
 * @var int $maxGrowth
 * @var int $maxRatio
 * @var int $minGrowth
 * @var int $minRatio
 */
?>
<?php $this->append('script'); ?>
    <script type="text/javascript">
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        let data = google.visualization.arrayToDataTable(<?= json_encode($chartData) ?>);

        let options = {
          colors: <?= json_encode(array_values($colors)) ?>,
          chartArea: {
            height: '80%',
            top: 15
          },
          hAxis: {
            title: 'Home value growth (2010 to 2017)',
            minValue: <?= $minGrowth ?>,
            maxValue: <?= $maxGrowth ?>,
            viewWindow: {
              min: <?= $minGrowth ?>,
              max: <?= $maxGrowth ?>,
            },
            baseline: <?= $stateGrowthValue ?>
          },
          vAxis: {
            title: 'County home value to state home value ratio',
            minValue: <?= $minRatio ?>,
            maxValue: <?= $maxRatio ?>,
            viewWindow: {
              min: <?= $minRatio ?>,
              max: <?= $maxRatio ?>,
            },
            baseline: 1
          },
          legend: {
            position: 'bottom'
          }
        };

        let chart = new google.visualization.ScatterChart(document.getElementById('chart-container'));

        chart.draw(data, options);
      }
    </script>
<?php $this->end(); ?>

<div id="chart-container" style="max-width: 720px; height: 500px;"></div>
