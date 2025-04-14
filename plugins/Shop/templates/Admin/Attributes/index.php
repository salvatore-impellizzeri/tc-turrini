<?php
$this->extend('/Admin/Common/index');
$this->set('statusBarSettings', [
    'pathway' => __dx($po, 'admin', 'attributes')
]);

$this->set('controlsSettings', [
    'actions' => []
]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?= __d('admin', 'title') ?>
        </th>

        <th>
            <?= __dx($po, 'admin', 'attribute_group_id') ?>
        </th>

        <th>
            <?= __dx($po, 'admin', 'products count') ?>
        </th>

        <th>
            <?= __dx($po, 'admin', 'variants count') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

        

        <th>
            <?= __d('admin', 'created') ?>
        </th>
        <th></th>

    </thead>

    <tbody>
        <tr v-for="record in records"  v-cloak>
            <th scope="row">
                <?= $this->Table->editLink() ?>
            </th>

            <td>
                {{ record.attribute_group ? record.attribute_group.title : '' }}
            </td>
            
            <td>
                {{ record.product_count }}
            </td>

            <td>
                {{ record.product_variant_count }}
            </td>
            
            <?php if (TRANSLATION_ACTIVE): ?>
                <td>
                    <?= $this->Table->translationStatus() ?>
                </td>
            <?php endif; ?>

            <td>
                {{ formatDateTime(record.created) }}
            </td>
            <td>
                <div class="table__actions">
                    <?php echo $this->Table->editAction() ?>
                    <?php echo $this->Table->deleteAction() ?>
                </div>
            </td>

        </tr>

        <?= $this->Table->empty() ?>

    </tbody>

<?= $this->Table->end() ?>
