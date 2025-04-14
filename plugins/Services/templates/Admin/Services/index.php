<?php
$this->extend('/Admin/Common/index');
?>

<?= $this->Table->start() ?>
    <thead>
        <th>
            <?php echo $this->Table->defaultSort() ?>
        </th>
        <th class="main-column">
            <?= __d('admin', 'title') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

        <th>
            <?= __d('admin', 'created') ?>
        </th>
        <th>
            <?= __d('admin', 'published') ?>
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
                    <?php echo $this->Table->editAction() ?>
                    <?php echo $this->Table->deleteAction() ?>
                </div>
            </td>

        </tr>

        <?= $this->Table->empty() ?>

    <?php echo $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
