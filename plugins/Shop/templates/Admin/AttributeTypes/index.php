<?php
$this->extend('/Admin/Common/index');
$this->set('statusBarSettings', [
    'pathway' => __dx($po, 'admin', 'attribute types')
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

        <th>
            <?= __dx($po, 'admin', 'attribute group count') ?>
        </th>

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

            <td>
                {{ record.attribute_group_count }}
            </td>

            <td>
                {{ formatDateTime(record.created) }}
            </td>
            <td>
                <div class="table__actions">
                    <?php echo $this->Table->editAction() ?>
                </div>
            </td>

        </tr>

        <?= $this->Table->empty() ?>

    <?php echo $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
