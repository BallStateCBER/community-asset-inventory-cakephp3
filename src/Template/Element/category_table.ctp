<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Model\Entity\County[] $counties
 * @var array $scores
 */
?>
<table class="table">
	<thead>
		<tr>
			<th>
                County
            </th>
			<?php if ($scores['grade']): ?>
				<th>
                    Grade
                </th>
			<?php endif; ?>
			<th colspan="1">
                Points
            </th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($counties as $county): ?>
			<tr>
				<th>
                    <a href="#" id="showreport_<?= $county->id ?>">
                        <?= $county->name ?>
                    </a>
				</th>
				<?php if ($scores['grade']): ?>
					<td>
						<?php if (isset($scores['grade'][$county->id])): ?>
							<?= $scores['grade'][$county->id] ?>
						<?php endif; ?>
					</td>
				<?php endif; ?>
				<td>
					<?= round($scores['index'][$county->id], 1) ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php $this->append('buffered'); ?>
    <?php foreach ($counties as $county): ?>
        $('#showreport_<?= $county->id ?>').click(function(event) {
            event.preventDefault();
            showFullReport('<?= $county->simplified ?>');
        });
    <?php endforeach; ?>
<?php $this->end(); ?>
