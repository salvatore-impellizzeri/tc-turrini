<?php
$this->extend('/Admin/Common/index');
?>


<?= $this->Table->start() ?>
    <thead>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'title'), 'SefUrls.rewritten') ?>
        </th>
        <th></th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'SefUrls.created') ?>
        </th>
        <th>            
        </th>
        <th>

        </th>
    </thead>
    <tbody>
        <tr v-for="record in records">
            <th scope="row">
                <?= $this->Table->editLink('record.rewritten') ?>
            </th>
            <td>             
            </td>
            <td>
                {{ formatDateTime(record.created) }}
            </td>
            <td>                
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
