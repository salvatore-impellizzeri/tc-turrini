<?php
$this->extend('/Admin/Common/index');
?>

<?= $this->Table->start() ?>
    <thead>
        <th></th>
        <th>
            <?= __d('admin', 'title') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th>

            </th>
        <?php endif; ?>

        <th>

        </th>
        <th>
            <?= __d('admin', 'published') ?>
        </th>
        <th></th>

    </thead>

    <?php echo $this->Table->dragStart() ?>
        <tr v-for="record in records" :key="record.id">
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
                <?= $this->Table->btn('Gestisci cookie', $this->Url->build(['controller' => 'Cookies', 'action' => 'list'])) ?>
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

    <?php echo $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
