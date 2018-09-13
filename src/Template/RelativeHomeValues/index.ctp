<?php
/**
 * @var \App\View\AppView $this
 * @var array $chartData
 * @var array $rhvs
 * @var int $maxGrowth
 * @var int $maxRatio
 * @var int $minGrowth
 * @var int $minRatio
 */
?>

<h1>
    Housing Value Barometer for All Counties
</h1>

<?= $this->element('load_google_charts') ?>
<?= $this->element('housing_barometer_scatter') ?>
