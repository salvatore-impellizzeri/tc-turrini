<?php
$this->extend('/Admin/Common/index');

if ($loggedUser['group_id'] != 1) {
    $this->set('controlsSettings', ['actions' => null]);
}
?>


<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?php echo $this->Table->sort(__d('admin', 'title'), 'Snippets.title') ?>
        </th>
        <th>
            <?php echo __dx($po, 'admin', 'backend_description') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'Snippets.created') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'Snippets.published') ?>
        </th>
        <th>

        </th>
    </thead>
    <tbody>
        <tr v-for="record in records" v-cloak>
            <th scope="row">
                <?= $this->Table->editLink() ?>
            </th>
            <td>
                {{ record.backend_description }}
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
