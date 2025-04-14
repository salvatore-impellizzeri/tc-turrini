<?php
$this->extend('/Admin/Common/index');

if(!empty( $this->request->getQuery('product_category_id'))) {
	$this->set('conditions', ['Products.product_category_id' => $this->request->getQuery('product_category_id')]);
}

$this->set('controlsSettings', [
	'filters' => [
		[
			'field' => 'product_category_id',
			'options' => $productCategories,
			'label' => __dx($po, 'admin', 'product_category_id'),
			'empty' => __d('admin', 'all f')
		]
	]
]);
?>

<?= $this->Table->start() ?>
    <thead>
		<th>
			<?php echo $this->Table->defaultSort() ?>
		</th>
        <th class="main-column">
            <?php echo $this->Table->sort(__d('admin', 'title'), 'Products.title') ?>
        </th>
		<th>
            <?php echo $this->Table->sort(__dx($po, 'admin', 'product_category_id'), 'ProductCategories.title') ?>
        </th>

		<?php if (TRANSLATION_ACTIVE): ?>
			<th></th>
		<?php endif; ?>


        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'Products.published') ?>
        </th>
        <th>

        </th>
    </thead>

	<?php echo $this->Table->dragStart() ?>
        <tr v-for="record in records" :key="record.id" v-cloak>
			<td class="drag">
                <?php echo $this->Table->dragHandle() ?>
            </td>
            <th scope="row">
                <?= $this->Table->editLink() ?>
            </th>
			<td>
				{{ record.product_category.title }}
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
                    <?= $this->Table->editAction() ?>
                    <?= $this->Table->deleteAction() ?>
                </div>

            </td>
        </tr>

        <?= $this->Table->empty() ?>

    <?php echo $this->Table->dragEnd() ?>

<?= $this->Table->end() ?>
