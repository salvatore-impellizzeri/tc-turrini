<?php
$this->extend('/Admin/Common/index');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'attribute groups'),
            'url' => ['controller' => 'AttributeGroups', 'action' => 'index']
        ],
        [
            'title' => $item->title,
        ]
    ]
]);

$this->set('controlsSettings', [
	'backUrl' =>  ['controller' => 'AttributeGroups', 'action' => 'index'],
    'actions' => [
        [
            'title' => __d('admin', 'add action'),
            'icon' => 'add_circle_outline',
            'url' => ['controller' => 'Attributes', 'action' => 'add', '?' => ['attribute_group_id' => $item->id]]
        ]
    ],
]);

$this->set('conditions', ['Attributes.attribute_group_id' => $item->id]);
?>

<?= $this->Table->start() ?>
    <thead>
        <th></th>
        <th>
            <?= __d('admin', 'title') ?>
        </th>
        
        <th>
            <?= __dx($po, 'admin', 'products count') ?>
        </th>

        <th>
            <?= __dx($po, 'admin', 'variants count') ?>
        </th>

        <?php if (TRANSLATION_ACTIVE): ?>
            <th></th>
        <?php endif; ?>


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
                {{ record.product_count }}
            </td>
            <td>
                {{ record.product_variant_count }}
            </td>
            <?php if (TRANSLATION_ACTIVE): ?>
                <td>
                    <?= $this->Table->translationStatus() ?>
                </td>
            <?php endif; ?>

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
