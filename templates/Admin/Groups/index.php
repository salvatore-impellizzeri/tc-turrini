<?php
$this->extend('/Admin/Common/setupIndex');
$this->set('currentMenuId', 'Groups');
$this->set('statusBarSettings', ['search' => true]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th></th>
        <th class="main-column">
            <?= __d('admin', 'title') ?>
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
