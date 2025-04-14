<?php
$this->extend('/Admin/Common/index');
$this->set('statusBarSettings', [
    'pathway' => __dx($po, 'admin', 'vat rates')
]);
?>


<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?php echo $this->Table->sort(__dx($po, 'admin', 'value'), 'VatRates.value') ?>
        </th>
        <th>
            <?= __dx($po, 'admin', 'products count') ?>
        </th>
        <th>
            <?= __dx($po, 'admin', 'is_default') ?>
        </th>
        <th>

        </th>
    </thead>
    <tbody>
        <tr v-for="record in records" v-cloak>
            <th scope="row">
                <?= $this->Table->editLink('record.value') ?>
            </th>
            <td>
                {{ record.product_count }}
            </td>
            <td>
                <template v-if="record.is_default">
                    <?= $this->Backend->materialIcon('check'); ?>
                </template>
            </td>
            <td>
                <div class="table__actions">
                    <?= $this->Table->editAction() ?>
                    <?= $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>

        <?= $this->Table->empty() ?>

    </tbody>
<?= $this->Table->end() ?>
