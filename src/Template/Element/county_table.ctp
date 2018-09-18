<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category[] $categories
 * @var array $scores
 */
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Category</th>
            <th>Grade</th>
            <th>Points</th>
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
                <td class="grade">
                    <?php if (isset($scores[$category->name]['Grade'])): ?>
                        <?= $scores[$category->name]['Grade'] ?>
                    <?php else: ?>
                        <span class="na">n/a</span>
                    <?php endif; ?>
                </td>
                <td class="index">
                    <?= round($scores[$category->name]['Index'], 2) ?>
                </td>
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
