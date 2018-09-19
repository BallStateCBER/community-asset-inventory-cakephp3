<?php
/**
 * @var array $barometerTableData
 */
?>
<table class="table" id="barometer-table">
    <thead>
        <tr>
            <?php foreach (array_keys($barometerTableData) as $header): ?>
                <th class="barom-<?= $header ?>">
                    <?= ucwords($header) ?>
                </th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php foreach ($barometerTableData as $header => $counties): ?>
                <td class="barom-<?= $header ?>">
                    <?php foreach ($counties as $countyName): ?>
                        <?= $countyName ?>
                        <br />
                    <?php endforeach; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    </tbody>
</table>
