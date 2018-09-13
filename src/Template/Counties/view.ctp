<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\County $county
 * @var \App\Model\Entity\Category[] $categories
 */
?>
<?= $this->element('load_google_charts') ?>
<div id="full-report">
    <h2>
        <?= $county->name ?> County's Full Asset Inventory Report
    </h2>
    <p>
        Click on a category to view grades/points in all counties. Want to learn more about this county? Visit
        <?= $county->name ?> County's profile at the Center for Business and Economic Research's
        <a href="https://profiles.cberdata.org">County Profiles</a> website.
    </p>

    <?= $this->element('county_table') ?>
</div>

<h2>
    Housing Value Barometer
</h2>
<?= $this->element('housing_barometer') ?>
