<?php
$this->extend('/Admin/Common/index');

if ($loggedUser['group_id'] != 1){
    // solo superadmin possono creare nuove pagine
    $this->set('controlsSettings', [
        'actions' => false
    ]);
}
?>


<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?php echo $this->Table->sort(__d('admin', 'title'), 'CustomPages.title') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__dx($po, 'admin', 'layout'), 'CustomPages.layout') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'CustomPages.created') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th>
            </th>
        <?php endif; ?>

        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'CustomPages.published') ?>
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
                {{ record.layout }}
            </td>
            <td>
                {{ formatDateTime(record.created) }}
            </td>

            <?php if (TRANSLATION_ACTIVE): ?>
                <td>
                    <?= $this->Table->translationStatus() ?>
                </td>
            <?php endif; ?>

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
