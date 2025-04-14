<?php
$this->extend('/Admin/Common/index');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => $contactForm->title,
        ]
    ]
]);

$this->set('controlsSettings', [
    'actions' => [],
]);

$this->set('conditions', ['Contacts.contact_form_id' => $contactForm->id]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th>
            <?= __d('admin', 'email') ?>
        </th>

        <th>
            <?php echo $this->Table->sort(__d('admin', 'created'), 'Contacts.created') ?>
        </th>
  
        <th></th>

    </thead>

    <tbody>
        <tr v-for="record in records" :key="record.id">
            <th scope="row">
                {{ record.email }}
            </th>
            <td>
                {{ formatDateTime(record.created) }}
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
