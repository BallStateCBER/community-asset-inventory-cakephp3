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
                    <?= $this->Html->link(
                        $county->name,
                        [
                            'controller' => 'Counties',
                            'action' => 'view',
                            'slug' => $county->simplified
                        ]
                    ) ?>
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
