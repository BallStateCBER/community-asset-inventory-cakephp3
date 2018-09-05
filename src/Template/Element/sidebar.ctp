<?php
    /**
     * @var \App\View\AppView $this
     * @var array $sidebar
     */

    use Cake\Utility\Text;
    $this->Html->script('sidebar', ['block' => 'script']);
?>

<h3>
    <a href="#home" id="home-link">
        Home
    </a>
</h3>

<h3>
    All Counties by Category
</h3>
<ul id="categories" class="unstyled">
    <?php foreach ($sidebar['parentCategories'] as $pcId => $pcName): ?>
        <li>
            <a href="#<?= Text::slug($pcName) ?>" id="showmap-<?= Text::slug($pcName) ?>">
                <?= $pcName ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<h3 id="all-categories-header">
    All Categories by County
</h3>
<form class="form-inline">
    <div class="form-group">
        <label class="sr-only" for="select-county">
            Select a county
        </label>
        <select id="select-county" class="form-control input-sm">
            <option value="">
                Select a county
            </option>
            <option value=""></option>
            <?php foreach ($sidebar['counties'] as $county): ?>
                <option value="<?= $county['Location']['simplified'] ?>">
                    <?= $county['Location']['name'] ?> County
                </option>
            <?php endforeach; ?>
        </select>
        <button id="select-county-button" class="btn btn-default btn-sm">
            Go
        </button>
    </div>
</form>

<h3>
    <?= $this->Html->link(
        'Data Sources and Methodology',
        [
            'controller' => 'Pages',
            'action' => 'sources'
        ]
    ) ?>
</h3>

<h3>
    Download
</h3>
<ul class="unstyled">
    <li>
        <a href="/files/CAIR-Report2012.pdf">
            Print Report 2012 (PDF)
        </a>
    </li>
    <li>
        <a href="/files/CAIR-RawData2012.xls">
            Raw Data Spreadsheet 2012 (Excel)
        </a>
    </li>
</ul>

<h3>
    <a href="#faq" id="faq-link">
        Frequently Asked Questions
    </a>
</h3>

<h3>
    <?= $this->Html->link(
        'Credits',
        [
            'controller' => 'Pages',
            'action' => 'credits'
        ]
    ) ?>
</h3>

<ul id="extra_links">
    <li>
        <a href="http://cberdata.org">
            Ball State CBER Data Center
        </a>
    </li>
    <li>
        <a href="http://bsu.edu/cber">
            Center for Business and Economic Research
        </a>
    </li>
</ul>

<?php $this->append('buffered'); ?>
    setupSidebar();
    setupShowMap(<?= json_encode($sidebar['categorySlugs']) ?>);
<?php $this->end(); ?>
