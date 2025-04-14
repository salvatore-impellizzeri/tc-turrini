<?php
$this->extend('/Admin/Common/index');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['controller' => 'ContactForms', 'action' => 'index']
        ],
        [
            'title' => $contactForm->title,
        ]
    ]
]);

$this->set('controlsSettings', [
	'backUrl' =>  ['controller' => 'ContactForms', 'action' => 'index'],
    'actions' => [
        [
            'title' => __d('admin', 'add action'),
            'icon' => 'add_circle_outline',
            'url' => ['controller' => 'ContactFields', 'action' => 'add', '?' => ['contact_form_id' => $contactForm->id]]
        ]
    ],
]);

$this->set('conditions', ['ContactFields.contact_form_id' => $contactForm->id]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th></th>
        <th>
            <?= __d('admin', 'title') ?>
        </th>
        <th>
            <?= __dx($po, 'admin', 'type') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

        <th>
            <?= __dx($po, 'admin', 'required') ?>
        </th>
        <th>
            <?= __dx($po, 'admin', 'show_in_mail') ?>
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
            <td>
                {{ record.type }}
            </td>
            <?php if (TRANSLATION_ACTIVE): ?>
                <td>
                    <?= $this->Table->translationStatus() ?>
                </td>
            <?php endif; ?>

            <td>
                <?php echo $this->Table->toggler('required') ?>
            </td>
            <td>
                <?php echo $this->Table->toggler('show_in_mail') ?>
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
