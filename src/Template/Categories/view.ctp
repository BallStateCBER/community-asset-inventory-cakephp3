<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\County[] $counties
 * @var \App\Model\Entity\Category $parentCategory
 * @var array $scores
 * @var array $downloadOptions
 * @var array $urlParams
 */
$inlineColorAssignments = [];
foreach ($counties as $county) {
	// Period in IDs (as for st._joseph) need to be escaped for jQuery to be able to select those elements
	$countyName = str_replace('.', '\\\.', $county->simplified);
	
	if (isset($scores['grade'][$county->id])) {
		$grade = strtolower($scores['grade'][$county->id]);
		$grade = str_replace(['-', '+'], '', $grade);
		$inlineColorAssignments[] = "$('#in-map-$countyName').addClass('grade-$grade');";
	} else {
		$index = $scores['index'][$county->id];
		if ($index >= 115) {
			$indexGrade = 115;
		} elseif ($index >= 105) {
			$indexGrade = 105;
		} elseif ($index >= 95) {
			$indexGrade = 95;
		} elseif ($index >= 85) {
			$indexGrade = 85;
		} else {
			$indexGrade = 70;
		}
		$inlineColorAssignments[] = "$('#in-map-$countyName').addClass('index-$indexGrade');";
	}
}
$this->Html->script('category', ['block' => 'script']);
?>
<?php $this->append('buffered'); ?>
    viewCategory.setupMap();
    <?= implode("\n", $inlineColorAssignments) ?>
<?php $this->end(); ?>

<div id="category-report">
	<aside>
		<h2>
            About <?= $parentCategory->name ?>
        </h2>
		<p>
            <?= $parentCategory->description ?>
        </p>
		
		<h2>
            Sources
        </h2>
		<ul class="sources">
			<?php foreach ($parentCategory->sources as $source): ?>
				<li>
					<?= $source['name'] ?>
				</li>
			<?php endforeach; ?>
		</ul>
		
		<div id="report-view-controls">
			<button id="show-map" class="btn btn-default btn-sm active">
                <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                <span>Map</span>
			</button>
			<button id="show-table" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
				<span>Table</span>
			</button>
            <br />
			<div id="download-options-wrapper">
                <strong>
                    Download this data set:
                </strong>
                <ul>
                    <?php foreach ($downloadOptions as $downloadOption): ?>
                        <li>
                            <?= $this->Html->link(
                                sprintf(
                                    '%s <span>%s</span>',
                                    $this->Html->image($downloadOption['icon']),
                                    $downloadOption['displayed_type']
                                ),
                                array_merge($urlParams, [$downloadOption['type_param']]),
                                ['escape' => false]
                            ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
			</div>
		</div>
	</aside>
	<h1>
        <?= $parentCategory->name ?>
    </h1>
	
	<div class="map-wrapper">
		<p>
            Click on a county to view its full report profile.
        </p>
		<?= $this->element('indiana', [
            'width' => 330,
            'height' => 500,
            'classes' => ['interactive']
        ]) ?>
		<?= $this->element('legend') ?>
	</div>
	<div class="table-wrapper" style="display: none;">
		<?php echo $this->element('category_table'); ?>
	</div>
</div>

<div id="county-tooltips">
	<?php foreach ($counties as $county): ?>
		<div id="in-map-<?= $county->simplified ?>-details" style="display: none;">
			<h2>
				<?= $county->name ?> County
			</h2>
			<p>
				Points: <?= round($scores['index'][$county->id], 1) ?>
				<?php if (isset($scores['grade'][$county->id])): ?>
					<br />
					Grade: <?= $scores['grade'][$county->id] ?>
				<?php endif; ?>
			</p>
		</div>
	<?php endforeach; ?>
</div>
