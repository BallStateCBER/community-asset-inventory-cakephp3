<?php
    /**
     * @var \App\View\AppView $this
     * @var array $sidebar
     */

    use Cake\Utility\Text;

    $this->extend('DataCenter.default');
    $this->assign('sidebar', $this->element('sidebar'));
    $this->Html->css('/DataCenter/css/jquery.qtip.min.css', ['block' => true]);
    $this->Html->script('script.js', ['block' => 'scriptBottom']);
    $this->Html->script('/DataCenter/js/jquery.svg.js', ['block' => 'script']);
    $this->Html->script('/DataCenter/js/jquery.svgdom.js', ['block' => 'script']);
    $this->Html->script('/DataCenter/js/jquery.qtip.js', ['block' => 'script']);

    /* Tell Javascript what the counties and categories are
     * so that the processHash() function can interpret the hash fragment correctly */
    $jsCategoryDefinitions = array();
    foreach ($sidebar['parentCategories'] as $pcId => $pcName) {
        $jsCategoryDefinitions[] = Text::slug($pcName);
    }
    $jsCountyDefinitions = array();
    foreach ($sidebar['counties'] as $county) {
        $jsCountyDefinitions[] = $county['Location']['simplified'];
    }
?>

<?php $this->append('buffered'); ?>
    var categories = <?= json_encode($jsCategoryDefinitions) ?>;
    var counties = <?= json_encode($jsCountyDefinitions) ?>;
    processHash(categories, counties);
<?php $this->end(); ?>

<?php $this->start('subsite_title'); ?>
    <h1 id="subsite_title" class="max_width">
        <a href="">
            <img src="/img/CommtyAsset.png" alt="Indiana Community Asset Inventory and Rankings" />
        </a>
    </h1>
<?php $this->end(); ?>

<?php $this->append('scriptBottom'); ?>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>$.fn.modal || document.write('<script src="/js/bootstrap.min.js"><\/script>')</script>
<?php $this->end(); ?>

<?php $this->append('footer_about'); ?>
    <h2>
        About the Community Asset Inventory and Rankings
    </h2>
    <p>
        This site was created through a partnership between <a href="http://www.bsu.edu/bbc">Ball State's
            Building Better Communities</a> and the Center for Business and Economic Research.
    </p>
    <p>
        The <a href="http://www.cberdata.org/">CBER Data Center</a> is a product of the Center for Business
        and Economic Research at Ball State University.  CBER's mission is to conduct relevant and timely
        public policy research on a wide range of economic issues affecting the state and nation.
        <a href="http://www.bsu.edu/cber">Learn more</a>.
    </p>
<?php $this->end(); ?>

<div id="content">
    <?= $this->element('DataCenter.flash_messages_bootstrap') ?>
    <?= $this->fetch('content') ?>
</div>
