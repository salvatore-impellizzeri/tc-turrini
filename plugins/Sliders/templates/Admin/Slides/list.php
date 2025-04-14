<?php
$this->extend('/Admin/Common/index');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['controller' => 'Sliders', 'action' => 'index']
        ],
        [
            'title' => $slider->title,
        ]
    ]
]);

$this->set('controlsSettings', [
	'backUrl' =>  ['controller' => 'sliders', 'action' => 'index'],
    'actions' => [
        [
            'title' => __d('admin', 'add action'),
            'icon' => 'add_circle_outline',
            'url' => ['controller' => 'Slides', 'action' => 'add', '?' => ['slider_id' => $slider->id]]
        ]
    ],
]);

$this->set('conditions', ['Slides.slider_id' => $slider->id]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th></th>
        <th>
            <?= __d('admin', 'title') ?>
        </th>
        <th>
            <?= __d('admin', 'created') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>

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
                    <?php echo $this->Table->editAction() ?>
                    <?php echo $this->Table->deleteAction() ?>
                </div>
            </td>

        </tr>

        <?= $this->Table->empty() ?>

    <?php echo $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
