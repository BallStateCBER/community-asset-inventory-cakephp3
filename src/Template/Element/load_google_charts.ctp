<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?php $this->append('script'); ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {packages: ['corechart']});
    </script>
<?php $this->end(); ?>
