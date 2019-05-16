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

<?= $this->element('housing_barometer_gauge') ?>
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
<?= $this->element('housing_barometer_scatter') ?>

<h2>
    Reading the Graph
</h2>
<p>
    For each county in Indiana, we estimate the relative measure
    of two metrics and plot them in a graph. The horizontal axis
    represents the 2010-2017 growth of home values relative to
    state average and the vertical axis represents 2017 county
    home values <em>relative to the state average</em>.
</p>
<p>
    If a county appears in the <strong>first quadrant (upper-right,
    <span class="housing-growing">green</span>)</strong>, it represents a growing scenario where the home
    prices are above state average and is growing above state
    average for the past eight years.
</p>
<p>
    The <strong>second quadrant (upper-left, <span class="housing-warning">yellow</span>)</strong> depicts a warning
    scenario where the home prices are above state average, but
    the eight-year growth is lower than the state average.
</p>
<p>
    The <strong>third quadrant (bottom-left, <span class="housing-distressed">red</span>)</strong> shows that the
    county’s home prices are in distress where the values are
    below state average and the growth is also lower than state
    average.
</p>
<p>
    If a county falls in the <strong>fourth quadrant (bottom-right, <span class="housing-recovering">blue</span>)</strong>,
    it depicts a recovering scenario where the growth in home
    prices is higher than the state average growth, despite their
    recent home values being lower than the state.
</p>
<p>
    Some counties may perform below average when compared
    with the state, but perform relatively better than their
    neighbors.
</p>
