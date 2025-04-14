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
            <?php echo $this->Table->sort(__d('admin', 'title'), 'BlockPages.title') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'BlockPages.created') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th>
            </th>
        <?php endif; ?>

        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'BlockPages.published') ?>
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
                    <?php if($loggedUser['group_id'] == 1) echo $this->Table->customAction(['action' => 'compose'], 'ballot', 'compose') ?>
                    <?= $this->Table->editAction() ?>
                    <?= $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>

        <?= $this->Table->empty() ?>

    </tbody>
<?= $this->Table->end() ?>
