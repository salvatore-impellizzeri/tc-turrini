<?php
$this->extend('/Admin/Common/setupIndex');
$this->set('currentMenuId', 'ContentBlocks');
$this->set('statusBarSettings', ['search' => true]);
?>



<?= $this->Table->start() ?>
    <thead>
        <th></th>
        <th class="main-column">
            <?= __d('admin', 'title') ?>
        </th>
        <th>
            <?= __d('admin', 'layout') ?>
        </th>

        <th>
            <?= __dx($po, 'admin', 'plugin') ?>
        </th>
        <th>
            <?= __d('admin', 'created') ?>
        </th>
        <th>
            <?= __d('admin', 'published') ?>
        </th>
        <th></th>

    </thead>

    <?= $this->Table->dragStart() ?>
        <tr v-for="record in records" :key="record.id" v-cloak>  
            <td class="drag">
                <?php echo $this->Table->dragHandle() ?>
            </td>
            <th scope="row">
                <?= $this->Table->editLink() ?>
            </th>
            <td>
                {{ record.layout }}
            </td>
            <td>
                {{ record.plugin }}
            </td>
            <td>
                {{ formatDateTime(record.created) }}
            </td>
            <td>
                <?php echo $this->Table->toggler('published') ?>
            </td>
            <td>
                <div class="table__actions">
                    <?php echo $this->Table->editAction() ?>
                    <?php echo $this->Table->deleteAction() ?>
                </div>
            </td>

        </tr>
        <?= $this->Table->empty() ?>
    <?= $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
