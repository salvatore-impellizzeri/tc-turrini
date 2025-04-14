<?php
$this->extend('/Admin/Common/setupIndex');
$this->set('currentMenuId', 'Users');
$this->set('statusBarSettings', ['search' => true]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th class="main-column">
            <?php echo $this->Table->sort(__d('admin', 'username'), 'Users.username') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'users group'), 'Users.group_id') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'Users.created') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'active'), 'Users.active') ?>
        </th>
        <th>

        </th>
    </thead>
    <tbody>
        <tr v-for="record in records" v-cloak>
            <th scope="row">
                <?php echo $this->Table->editLink('record.username') ?>
            </th>
            <td>
                {{ record.group.title }}
            </td>
            <td>
                {{ formatDateTime(record.created) }}
            </td>
            <td>
                <?php echo $this->Table->toggler('active') ?>
            </td>
            <td>
                <div class="table__actions">
                    <?php echo $this->Table->customAction(['action' => 'change_password'], 'password', 'change password') ?>
                    <?php echo $this->Table->editAction() ?>
                    <?php echo $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>
        <?= $this->Table->empty() ?>
    </tbody>
<?= $this->Table->end() ?>
