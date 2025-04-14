<?php
$this->extend('/Admin/Common/setupIndex');
$this->set('currentMenuId', 'BackendMenu');
$this->set('statusBarSettings', ['search' => true]);
?>


<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?php echo $this->Table->sort(__d('admin', 'title'), 'Articles.title') ?>
        </th>
        <th>

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
                <?= $this->Table->btn(__dx($po, 'admin', 'manage items'), $this->Url->build(['controller' => 'MenuItems', 'action' => 'list'])) ?>
            </td>
            <td>
                <div class="table__actions">
                    <?php echo $this->Table->editAction() ?>
                    <?php echo $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>
        <?php echo $this->table->empty() ?>
    </tbody>
<?= $this->Table->end() ?>
