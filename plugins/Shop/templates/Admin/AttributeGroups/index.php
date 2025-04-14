<?php
$this->extend('/Admin/Common/index');
$this->set('statusBarSettings', [
    'pathway' => __dx($po, 'admin', 'attribute groups')
]);

$this->set('controlsSettings', [
    'actions' => []
]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th>
            <?php echo $this->Table->defaultSort() ?>
        </th>
        <th class="main-column">
            <?= __d('admin', 'title') ?>
        </th>

        <?php /*
        <th>
            <?= __dx($po, 'admin', 'products count') ?>
        </th>
        */ ?>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

        
        <th></th>
        <th>
            <?= __d('admin', 'created') ?>
        </th>
        <th></th>

    </thead>

    <?php echo $this->Table->dragStart() ?>
        <tr v-for="record in records" :key="record.id" v-cloak>
            <td class="drag">
                <?php echo $this->Table->dragHandle() ?>
            </td>
            <th scope="row">
                <?= $this->Table->editLink() ?>
            </th>

            <?php /*
            <td>
                {{ record.product_variant_count }}
            </td>
             */ ?>

            <?php if (TRANSLATION_ACTIVE): ?>
                <td>
                    <?= $this->Table->translationStatus() ?>
                </td>
            <?php endif; ?>

            <td>
                <?= $this->Table->btn(__dx($po, 'admin', 'manage attribute values'), $this->Url->build(['controller' => 'Attributes', 'action' => 'list'])) ?>
            </td>

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

    <?php echo $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
