<?php 
use Cake\Core\Configure;
use Cake\Collection\Collection;

$this->extend('/Admin/Common/edit'); 
$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'products'),
            'url' => ['controller' => 'ShopProducts', 'action' => 'index']
        ],
        [
            'title' => $item->shop_product->admin_title ?? '',
            'url' => ['controller' => 'ShopProducts', 'action' => 'edit', $item->shop_product_id]
        ],
        [
            'title' => __dx($po, 'admin', 'edit variant'),
        ]
    ]
]);
?>

<?php echo $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']); ?>
    <div class="tabs" data-tabs>
        <?php echo $this->element('admin/tabs-menu');?>
        <div class="tabs__content">
            <div class="tabs__tab" data-tab="edit">
                <fieldset class="input-group" v-if="!hasVariants">
                    <legend class="input-group__info">
                        Dettagli variante
                    </legend>
                    <?php
                    echo $this->Form->control('sku', ['label' => __dx($po, 'admin', 'sku'), 'extraClass' => 'span-8']);
                    echo $this->Form->control('is_default', ['label' => __dx($po, 'admin', 'default variant'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
                    echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-2']);

                    if (Configure::read('Shop.vatIncuded')) {
                        echo $this->Form->control('price', ['label' => __dx($po, 'admin', 'price'), 'extraClass' => 'span-6']);
                        echo $this->Form->control('discounted_price', ['label' => __dx($po, 'admin', 'discounted_price'), 'extraClass' => 'span-6']);
                    } else {
                        echo $this->Form->control('price_net', ['label' => __dx($po, 'admin', 'price_net'), 'extraClass' => 'span-6']);
                        echo $this->Form->control('discounted_price_net', ['label' => __dx($po, 'admin', 'price_net'), 'extraClass' => 'span-6']);
                    }

                    echo $this->Form->control('stock', ['label' => __dx($po, 'admin', 'stock'), 'extraClass' => 'span-6']);
                    echo $this->Form->control('weight', ['label' => __dx($po, 'admin', 'weight'), 'extraClass' => 'span-6']);
                    
                    ?>

                    <?php if (!empty($item->shop_product->variant_attribute_groups)):
                        
                        $currentAttributes =  !empty($item->attributes) ? $item->attributes : [];
                        $currentAttributes = new Collection($currentAttributes); 
                        ?>
                        
                        <?php foreach ($item->shop_product->variant_attribute_groups as $i => $attributeGroup): ?>
                            <?php 
                            if (!empty($attributeGroup->attributes)) {
                                $options = (new Collection($attributeGroup->attributes))->combine('id', 'title')->toArray();
                            } else {
                                $options = [];
                            } 

                            $currentAttribute = $currentAttributes->firstMatch([
                                'attribute_group_id' => $attributeGroup->id
                            ]);
                            
                            echo $this->Form->hidden("attributes.$i.attribute_group_id", ['value' => $attributeGroup->id]);
                            echo $this->Form->control("attributes.$i.id", [
                                'required' => true,
                                'options' => $options,
                                'type' => 'select',
                                'label' => $attributeGroup->title,
                                'value' => !empty($currentAttribute) ? $currentAttribute->id : null,
                                'empty' => __dx($po, 'admin', 'choose attribute'), 
                                'addOption' => true, 
                                'newOptionIndex' => 'new',
                                'newOptionFieldName' => "attributes.$i.title",
                                'newOptionPlaceholder' => __dx($po, 'admin', 'new attribute value title') 
                            ]) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </fieldset>
                

                <fieldset class="input-group">
                    <legend class="input-group__info">
                        Media
                    </legend>
                    <?php
                    echo $this->element('admin/uploader/image', [
                        'scope' => 'preview',
                        'title' => __d('admin', 'preview'),
                        'width' => 800,
                    ]);

                    echo $this->element('admin/uploader/gallery', [
                        'scope' => 'gallery',
                        'title' => __dx($po, 'admin', 'gallery'),
                    ]);
                    ?>
                </fieldset>

                <fieldset class="input-group input-group--forcemargin">
                    <legend class="input-group__info">
                        Contenuto
                    </legend>
                    <?php
                    echo $this->Form->editor('excerpt', ['label' => __d('admin', 'excerpt')]);
                    echo $this->Form->editor('description', ['label' => __d('admin', 'text')]);
                    ?>
                </fieldset>

                
            </div>
            <?php echo $this->element('admin/tab-seo');?>
			<?php echo $this->element('admin/tab-social');?> 
        </div>
    </div>

    <?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
