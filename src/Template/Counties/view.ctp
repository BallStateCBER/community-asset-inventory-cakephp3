<?php
/**
 * @var AppView $this
 * @var County $county
 * @var Category[] $categories
 */

use App\Model\Entity\Category;
use App\Model\Entity\County;
use App\View\AppView;
?>

<?= $this->element('load_google_charts') ?>
<h1>
    <?= $county->name ?> County's Full Asset Inventory Report
</h1>
<div id="full-report">
    <p>
        Click on a category to view grades/points in all counties. Want to learn more about this county? Visit
        <?= $county->name ?> County's profile at the Center for Business and Economic Research's
        <a href="https://profiles.cberdata.org">County Profiles</a> website.
    </p>

    <aside class="county-info">
        <div class="state-map">
            <?= $this->element('indiana', ['width' => 100, 'height' => 152, 'classes' => ['small']]) ?>
            <?php $this->append('buffered'); ?>
            $('#in-map-<?= $county->simplified ?>').css('fill', '#000');
            <?php $this->end(); ?>
        </div>
        <h2>
            About <?= $county->name ?> County
        </h2>
        <dl>
            <dt>County Seat:</dt>
            <dd><?= $county->county_seat ?></dd>
            <dt>Founded:</dt>
            <dd><?= $county->founded ?></dd>
            <dt>Area:</dt>
            <dd><?= $county->square_miles ?> square miles</dd>
        </dl>
        <br class="clear" />
    </aside>
    <?= $this->element('county_table') ?>
</div>

<?= $this->element('HousingBarometer/gauge') ?>
<h1>
    Housing Value Barometer
</h1>
<p>
    To describe county-level housing markets, we use data sets that assess both the changing price and quality of
    housing. The best of these indices is provided by <a href="https://www.zillow.com/">Zillow, Inc.</a>, which
    aggregates the value of homes as estimated through its pricing model.
</p>
<p>
    The Zillow home price measure captures both the change in price of existing housing stock and the effect of new,
    higher quality housing stock. In that way, the price changes reflect both the value of existing and new homes,
    without holding home quality constant.
</p>
<?= $this->element('HousingBarometer/scatter') ?>

<?= $this->element('HousingBarometer/footnote') ?>
