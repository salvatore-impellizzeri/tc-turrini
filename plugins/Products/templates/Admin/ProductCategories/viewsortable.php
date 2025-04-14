<?php
	$this->extend('/Admin/Common/index');
	$this->set('statusBarSettings', [
		'title' =>  __dx($po, 'admin', 'categories')		
    ]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th></th>
        <th>
            <?= __dx($po, 'admin', 'title') ?>
        </th>
        <th>
            <?= __d('admin', 'created') ?>
        </th>
        <th></th>

    </thead>

    <?php echo $this->Table->dragStart() ?>
        <tr v-for="record in records" :key="record.id">
            <td class="drag">
                <?php echo $this->Table->dragHandle() ?>
            </td>
            <th scope="row">
                <?= $this->Table->editLink('record.title') ?>
            </th>
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
