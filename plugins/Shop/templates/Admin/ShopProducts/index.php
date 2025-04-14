<?php
$this->extend('/Admin/Common/index');

$this->set('statusBarSettings', [
    'pathway' => __dx($po, 'admin', 'products')
]);

$this->set('controlsSettings', [
	'filters' => [
		[
			'field' => 'shop_category_id',
			'options' => $shopCategories,
			'label' => __dx($po, 'admin', 'shop_category_id'),
			'empty' => __d('admin', 'all f')
        ],
        [
			'field' => 'brand_id',
			'options' => $brands,
			'label' => __dx($po, 'admin', 'brand_id'),
			'empty' => __d('admin', 'all')
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
            <?php echo $this->Table->sort(__d('admin', 'title'), 'ShopProducts.admin_title') ?>
        </th>
        <th>
            <?= __dx($po, 'admin', 'brand_id') ?>
        </th>
        <th>
            <?= __dx($po, 'admin', 'variants') ?>
        </th>

		<?php if (TRANSLATION_ACTIVE): ?>
			<th></th>
		<?php endif; ?>


        <th>
            <?php echo $this->Table->sort(__d('admin', 'published'), 'ShopProducts.published') ?>
        </th>
        <th>

        </th>
    </thead>

	<?php echo $this->Table->dragStart() ?>
        <tr v-for="record in records" :key="record.id" v-cloak>
			<td class="drag">
                <?php echo $this->Table->dragHandle() ?>
            </td>
            <th scope="row" v-bind:class="{ 'error-color': record.draft }">
                <?= $this->Table->editLink('record.admin_title') ?>
            </th>
            <td>
                {{ record.brand !== null ? record.brand.title : '' }}
            </td>

            <td>
                {{ !record.has_variants ? '<?= __d('admin', 'no') ?>' : record.product_variant_count }}
            </td>

			<?php if (TRANSLATION_ACTIVE): ?>
				<td>
	                <?= $this->Table->translationStatus() ?>
	            </td>
			<?php endif; ?>

            <td>
                <template v-if="!record.draft">
                    <?php echo $this->Table->toggler('published') ?>
                </template>
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
