<?php
/**
 * @var \App\View\AppView $this
 */
?>

<h1>
    Housing Value Barometer for All Counties
</h1>

<?= $this->element('load_google_charts') ?>
<?= $this->element('HousingBarometer/scatter') ?>
<?= $this->element('HousingBarometer/table') ?>
<?= $this->element('HousingBarometer/footnote') ?>
