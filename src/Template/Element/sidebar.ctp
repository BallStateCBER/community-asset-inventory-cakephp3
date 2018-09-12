<?php
    /**
     * @var \App\View\AppView $this
     * @var array $sidebar
     */
    $this->Html->script('sidebar', ['block' => 'script']);
?>

<h3>
    <a href="/">
        Home
    </a>
</h3>

<h3>
    Categories
</h3>
<ul id="categories" class="unstyled">
    <?php foreach ($sidebar['parentCategories'] as $category): ?>
        <?php
            $url = \Cake\Routing\Router::url([
                'controller' => 'Categories',
                'action' => 'view',
                $category->slug
            ]);
            $active = $this->request->getRequestTarget() == $url;
        ?>
        <li <?php if ($active): ?>class="selected"<?php endif; ?>>
            <?= $this->Html->link(
                $category->name,
                $url
            ) ?>
        </li>
    <?php endforeach; ?>
</ul>

<h3 id="all-categories-header">
    Indiana Counties
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
                <option value="<?= $county->simplified ?>">
                    <?= $county->name ?> County
                </option>
            <?php endforeach; ?>
        </select>
        <button id="select-county-button" class="btn btn-default btn-sm">
            Go
        </button>
    </div>
</form>

<h3>
    Download Report
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
    More Information
</h3>

<ul class="unstyled">
    <li>
        <?= $this->Html->link(
            'Data Sources and Methodology',
            [
                'controller' => 'Pages',
                'action' => 'sources'
            ]
        ) ?>
    </li>
    <li>
        <?= $this->Html->link(
            'Frequently Asked Questions',
            [
                'controller' => 'Pages',
                'action' => 'faq'
            ]
        ) ?>
    </li>
    <li>
        <?= $this->Html->link(
            'Credits',
            [
                'controller' => 'Pages',
                'action' => 'credits'
            ]
        ) ?>
    </li>
</ul>

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
<?php $this->end(); ?>
