<?php
/**
 * @var \App\View\AppView $this
 */
?>

<h1>
    Housing Value Barometer for All Counties
</h1>

<?= $this->element('load_google_charts') ?>
<?= $this->element('housing_barometer_scatter') ?>
<?= $this->element('housing_barometer_table') ?>

