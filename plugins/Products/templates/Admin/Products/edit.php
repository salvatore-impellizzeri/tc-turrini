<?php $this->extend('/Admin/Common/edit');?>

<?= $this->Form->create($item) ?>
    <div class="tabs" data-tabs>
        <?php echo $this->element('admin/tabs-menu');?>
        <div class="tabs__content">
            <div class="tabs__tab" data-tab="edit">
                <fieldset class="input-group">
                    <legend class="input-group__info">
                        Generali
                    </legend>
                    <?php

					echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-6']);
					echo $this->Form->control('product_category_id', ['label' => __dx($po, 'admin', 'product_category_id'), 'options' => $productCategories, 'empty' => __d('admin', 'Seleziona'), 'extraClass' => 'span-5']);
					echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-1']);
                    ?>
                </fieldset>

                <fieldset class="input-group">
                    <legend class="input-group__info">
                        Contenuto
                    </legend>
                        <?php
                            echo $this->element('admin/uploader/image', [
                                'scope' => 'preview',
                                'title' => __d('admin', 'preview'),
                                'width' => 720 * 1.5,
                            ]);

                            echo $this->Form->editor('excerpt', ['label' => __d('admin', 'excerpt')]);

                            echo $this->Form->editor('text', ['label' => __d('admin', 'text')]);

                            echo $this->element('admin/uploader/gallery', [
                                'scope' => 'gallery'
                            ]);
                        ?>
                
                </fieldset>

            </div>
            <?php echo $this->element('admin/tab-seo');?>
			<?php echo $this->element('admin/tab-social');?>           
        </div>
    </div>
    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
