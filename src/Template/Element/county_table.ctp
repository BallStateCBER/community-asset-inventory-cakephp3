<?php
/**
 * @var \App\Model\Entity\Category[] $categories
 * @var \App\View\AppView $this
 * @var array $scores
 * @var int $highYear
 * @var int $lowYear
 */
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>
                Category
            </th>
            <th colspan="2" class="grade">
                Grade
            </th>
            <th colspan="2" class="index">
                Points
            </th>
        </tr>
        <tr class="years">
            <th>

            </th>
            <th class="grade">
                <?= $lowYear ?>
            </th>
            <th class="grade">
                <?= $highYear ?>
            </th>
            <th class="index">
                <?= $lowYear ?>
            </th>
            <th class="index">
                <?= $highYear ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <th>
                    <?= $this->Html->link(
                        $category->name,
                        [
                            'controller' => 'Categories',
                            'action' => 'view',
                            $category->slug
                        ]
                    ) ?>
                </th>
                <?php foreach ([$lowYear, $highYear] as $year): ?>
                    <td class="grade">
                        <?php if (isset($scores[$category->name]['Grade'])): ?>
                            <?php if (isset($scores[$category->name]['Grade'][$year])): ?>
                                <?= $scores[$category->name]['Grade'][$year] ?>
                            <?php else: ?>
                                <span class="na">TBA</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="na">n/a</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
                <?php foreach ([$lowYear, $highYear] as $year): ?>
                    <td class="index">
                        <?php if (isset($scores[$category->name]['Index'][$year])): ?>
                            <?= round($scores[$category->name]['Index'][$year], 2) ?>
                        <?php else: ?>
                            <span class="na">TBA</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                N/A: Only points are used when assessing the
                changeable and static amenities categories.
            </td>
        </tr>
    </tfoot>
</table>
