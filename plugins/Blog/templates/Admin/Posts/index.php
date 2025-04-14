<?php
$this->extend('/Admin/Common/index');
?>


<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?php echo $this->Table->sort(__d('admin', 'title'), 'Posts.title') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__dx($po, 'admin', 'date'), 'Posts.date') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'Posts.created') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'Posts.published') ?>
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
                {{ formatDate(record.date) }}
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
                    <?php echo $this->Table->customAction(['action' => 'compose'], 'ballot', 'compose') ?>
                    <?php echo $this->Table->editAction() ?>
                    <?php echo $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>

        <?= $this->Table->empty() ?>
    </tbody>
<?= $this->Table->end() ?>
