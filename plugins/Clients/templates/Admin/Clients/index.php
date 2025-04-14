<?php
$this->extend('/Admin/Common/index');
?>


<?= $this->Table->start() ?>
    <thead>
        <th>
            <?php echo $this->Table->sort(__dx($po, 'admin', 'fullname'), 'Clients.fullname') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__dx($po, 'admin', 'email'), 'Clients.email') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'Clients.created') ?>
        </th>
        <th>
            <?php echo $this->Table->sort(__dx($po, 'admin', 'enabled'), 'Clients.enabled') ?>
        </th>
        <th>

        </th>
    </thead>
    <tbody>
        <tr v-for="record in records">
            <th scope="row">
                <?= $this->Table->editLink('record.login_type == "token" ? record.email : record.fullname', 'view') ?>
            </th>
            <td>
				<?= $this->Table->editLink('record.email', 'view') ?>
            </td>
            <td>
                {{ formatDateTime(record.created) }}
            </td>
            <td>
                <?php echo $this->Table->toggler('enabled') ?>
            </td>
            <td>
                <div class="table__actions">
                    <?= $this->Table->viewAction() ?>
                </div>

            </td>
        </tr>

        <?= $this->Table->empty() ?>

    </tbody>
<?= $this->Table->end() ?>
