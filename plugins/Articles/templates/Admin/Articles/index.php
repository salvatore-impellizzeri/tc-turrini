<?php
$this->extend('/Admin/Common/index');
?>


<?= $this->Table->start() ?>
    <thead>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'title'), 'Articles.title') ?>
        </th>
        <th></th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'Articles.created') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'Articles.published') ?>
        </th>
        <th>

        </th>
    </thead>
    <tbody>
        <tr v-for="record in records">
            <th scope="row">
                <?= $this->Table->editLink() ?>
            </th>
            <td>
                <?= $this->Table->translationStatus() ?>
            </td>
            <td>
                {{ formatDateTime(record.created) }}
            </td>
            <td>
                <?php echo $this->Table->toggler('published') ?>
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
