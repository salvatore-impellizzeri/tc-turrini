<?php
$this->extend('/Admin/Common/index');

$this->set('statusBarSettings', [
    'pathway' => __dx($po, 'admin', 'tags')
]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?php echo $this->Table->sort(__d('admin', 'title'), 'Tags.title') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'Tags.created') ?>
        </th>
        <th>

        </th>
    </thead>
    <tbody>
        <tr v-for="record in records" v-cloak>
            <th scope="row">
                <?= $this->Table->editLink() ?>
            </th>

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
                    <?= $this->Table->editAction() ?>
                    <?= $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>

        <?= $this->Table->empty() ?>

    </tbody>
<?= $this->Table->end() ?>
