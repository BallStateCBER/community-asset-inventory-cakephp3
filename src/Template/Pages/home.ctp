<h1>
    What Is the Community Asset Inventory and Rankings?
</h1>
<p>
    This website offers a detailed look at the factors that affect the reputation of Indiana's communities, both large
    and small.  The purpose of this project is to provide policy makers and residents within Indiana's counties with
    an objective, data-focused assessment of the factors that influence the quality of life and the economic conditions
    within each county.  See the methodology and FAQs for more information.
</p>

<div id="downloads_screenshots">
    <a href="/files/CAIR Report 2019.pdf">
        <img src="/img/reports/CAIR2019-cover.jpg" alt="Print Report 2018 (PDF)" />
        <br />
        <span>Download Print Report 2019 (PDF)</span>
    </a>
    <a href="/files/CAIR raw data 2019.xlsx">
        <img src="/img/reports/RawData2012.png" alt="Raw Data Spreadsheet 2018 (Excel)" />
        <br />
        <span>Download Raw Data Spreadsheet 2019 (Excel)</span>
    </a>
</div>

<h1>
    How to Use This Site
</h1>
<p>
    To begin, click on a category to view data for all Indiana counties.  If you want to view all the data for a
    specific county, you can click on the county using the map, or you can select the county from the navigation panel
    on the left.
</p>

<h1>
    New for 2019
</h1>
<?= $this->element('load_google_charts') ?>
<?= $this->element('HousingBarometer/gauge') ?>
<p>
    The updated Community Asset Inventory and Rankings informs communities of the economic and residential strengths in
    Indiana's 92 counties. In the 2019 report, we have added a new measure:
    <?= $this->Html->link(
        'the housing barometer',
        [
            'controller' => 'RelativeHomeValues',
            'action' => 'index'
        ]
    ) ?>.
</p>
<p>
    The housing barometer evaluates county housing markets using county-wide home values and the relative growth rate
    over eight years. When compared to the state average for value and growth, these variables indicate which of four
    housing market scenarios is present in a given county: growing, warning, distressed, and recovering housing markets.
</p>
