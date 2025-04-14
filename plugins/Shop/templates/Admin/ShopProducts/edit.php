<?php 
use Cake\Core\Configure;
$vatIncluded = Configure::read('Shop.vatIncuded');

$this->assign('bodyClass', 'body--loading');
$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'products'),
            'url' => ['action' => 'index']
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>

<?= $this->Form->create($item) ?>
    <div class="tabs" data-tabs>
        <?php echo $this->element('admin/tabs-menu');?>
        <div class="tabs__content">
            <div class="tabs__tab" data-tab="edit">
                
                <fieldset class="input-group">
                    <legend class="input-group__info">
                        Generale
                    </legend>
                    <?php
					echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-4']);
                    echo $this->Form->control('brand_id', [
                        'label' => __dx($po, 'admin', 'brand_id'), 
                        'extraClass' => 'span-4', 
                        'options' => $brands, 
                        'empty' => __d('global', 'select'), 
                        'addOption' => true, 
                        'newOptionPlaceholder' => __dx($po, 'admin', 'new brand title') 
                    ]);
                    echo $this->Form->control('vat_rate_id', ['label' => __dx($po, 'admin', 'vat_rate_id'), 'extraClass' => 'span-2']);
					echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
                    echo $this->Backend->belongsToMany('shop_categories', $item, [
                        'label' => __dx($po, 'admin', 'shop_category'),
                        'url' => ['controller' => 'ShopCategories', 'action' => 'checkbox']
                    ]);
                    ?>
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
                
                <?php // QUESTA PARTE VARIANTI e ATTRIBUTI LA GESTISCO CON VUE ?>
                <div id="product" v-cloak>
               
                    <fieldset class="input-group">
                        <legend class="input-group__info">
                            Opzioni prodotto
                        </legend>
                        <?php
                        echo $this->Form->control('has_variants', ['label' => __dx($po, 'admin', 'has_variants'), 'v-model' => 'hasVariants']);
                        ?>
                        <div class="input multiCheckbox" v-if="hasVariants">
                            <label><?= __dx($po, 'admin', 'choose/add variant options') ?></label>
                            <div class="multiCheckbox__selected" is="draggable" :list="variantAttributeGroups" @change="updateVariantAttributeGroupsOrder">
                               
                                <span class="multiCheckbox__tag" v-for="attributeGroup in variantAttributeGroups"> 
                                    {{ attributeGroup.title }}
                                    <span class="multiCheckbox__tag__remove material-symbols-rounded" @click="deleteVariantAttributeGroup(attributeGroup.id)">close</span>
                                </span>
                            </div>

                            <div class="multimultiCheckbox__actions">
                                <span class="btn btn--primary-outline btn--small" @click="openVariantAttributeGroupModal"><?= __d('admin','add action') ?></span>
                            </div>
                        </div> 
                    </fieldset>

                    <fieldset class="input-group" v-if="hasVariants && variantAttributeGroups.length"> 
                        <legend class="input-group__info">
                            Varianti prodotto
                        </legend>

                        <div class="input-group__subsection input-group__subsection--overflow" v-if="productVariants.length">
                            <table class="table table--compact table--last-column-sticky">
                                <thead>
                                    <tr>
                                        <th style="min-width: 120px"><?= __dx($po, 'admin', 'variant preview') ?></th>
                                        <th><?= __d('admin', 'title') ?></th>
                                        <th><?= __dx($po, 'admin', 'sku') ?></th>
                                        <th v-for="attributeGroup in variantAttributeGroups">{{ attributeGroup.title }}</th>
                                        <th><?= $vatIncluded ? __dx($po, 'admin', 'price') : __dx($po, 'admin', 'price_net') ?></th>
                                        <th><?= __dx($po, 'admin', 'stock') ?></th>
                                        <th><?= __dx($po, 'admin', 'weight') ?></th>
                                        <th><?= __dx($po, 'admin', 'default variant') ?></th>
                                        <th><?= __d('admin', 'published') ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="productVariant in productVariants">
                                        <td>
                                            <img v-if="productVariant.preview !== undefined && productVariant.preview !== null" :src="productVariant.preview.backend_resized_path" class="thumb thumb--small" >
                                        </td>
                                        <th scope="row" :class="[ productVariant.incomplete ? 'error-color' : '' ]">
                                            {{ productVariant.title }}
                                        </th>
                                        <td>{{ productVariant.sku }}</td>
                                        <td v-for="attributeGroup in variantAttributeGroups">
                                            {{ productVariant.attributes.find(item => item.attribute_group_id === attributeGroup.id)?.title }}
                                        </td>
                                        <td v-html="productVariant.display_discounted_price ? '<del>' + formatPrice(productVariant.display_price) + '</del>' + ' ' + formatPrice(productVariant.display_discounted_price) : formatPrice(productVariant.display_price)"></td>
                                        <td>{{ productVariant.stock }}</td>
                                        <td>{{ productVariant.weight }}</td>
                                        <td>
                                            <template v-if="productVariant.is_default">
                                                <?= $this->Backend->materialIcon('check'); ?>
                                            </template>
                                        </td>
                                        <td>
                                        
                                            <?= $this->Html->tag('span', $this->Html->tag('span', '', ['class' => 'onoff']), ['@click' => "toggleVariantField(productVariant.id,'published')", 'v-bind:class' => "[ productVariant.published ? 'toggle active' : 'toggle' ]"]);  ?>
                                        </td>
                                        <td>
                                            <div class="table__actions">
                                                <?= $this->Html->tag('span', $this->Backend->materialIcon('edit') . "\n" . $this->Html->div('action__tooltip', __d('admin', 'edit')), ['@click' => 'goToVariant(productVariant.id)', 'class' => 'action']); ?>
                                                <?= $this->Html->tag('span', $this->Backend->materialIcon('delete') . "\n" . $this->Html->div('action__tooltip', __d('admin', 'delete')), ['@click' => 'deleteVariant(productVariant.id)', 'class' => 'action']); ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="input-group__actions">
                            <span class="btn btn--primary btn--icon span-3" @click="openVariantModal">
                                <?= $this->Backend->materialIcon('add') ?>
                                <span><?= __dx($po, 'admin', 'add new variant') ?></span>
                            </span>
                        </div>
                    </fieldset>
                    
                    <fieldset class="input-group" v-if="!hasVariants">
                        <legend class="input-group__info">
                            Dettagli prodotto
                        </legend>
                        <?php
                        echo $this->Form->hidden('shop_product_variants.0.shop_product_id', ['value' => $item->id]);
                        if (!empty($item->shop_product_variants)) echo $this->Form->hidden('shop_product_variants.0.id', ['value' => $item->shop_product_variants[0]->id]);
                        echo $this->Form->control('shop_product_variants.0.sku', ['label' => __dx($po, 'admin', 'sku'), 'extraClass' => 'span-12']);

                        if (Configure::read('Shop.vatIncuded')) {
                            echo $this->Form->control('shop_product_variants.0.price', ['label' => __dx($po, 'admin', 'price'), 'extraClass' => 'span-6']);
                            echo $this->Form->control('shop_product_variants.0.discounted_price', ['label' => __dx($po, 'admin', 'discounted_price'), 'extraClass' => 'span-6']);
                        } else {
                            echo $this->Form->control('shop_product_variants.0.price_net', ['label' => __dx($po, 'admin', 'price_net'), 'extraClass' => 'span-6']);
                            echo $this->Form->control('shop_product_variants.0.discounted_price_net', ['label' => __dx($po, 'admin', 'price_net'), 'extraClass' => 'span-6']);
                        }

                        echo $this->Form->control('shop_product_variants.0.stock', ['label' => __dx($po, 'admin', 'stock'), 'extraClass' => 'span-6']);
                        echo $this->Form->control('shop_product_variants.0.weight', ['label' => __dx($po, 'admin', 'weight'), 'extraClass' => 'span-6']);
                        
                        ?>
                    </fieldset>



                    <fieldset class="input-group">
                        <legend class="input-group__info">
                            Attributi prodotto
                        </legend>

                        <div is="draggable" handle=".attribute__handle" :list="productAttributeGroups" @change="updateAttributeGroupsOrder">
                            <div class="attribute" v-for="attributeGroup in productAttributeGroups">
                                <div class="attribute__header">
                                    <div class="attribute__handle">
                                        <?= $this->Backend->materialIcon('drag_indicator') ?>
                                    </div>
                                    <div class="attribute__title">{{ attributeGroup.title }}</div>
                                    <div class="attribut__actions">
                                        <span class="action" @click="deleteAttributeGroup(attributeGroup.id)">
                                            <?= $this->Backend->materialIcon('delete') ?>
                                            <span class="action__tooltip"><?= __d('admin', 'delete') ?></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="attribute__values">
                                    <div class="input default-checkbox" v-for="attribute in getFilteredAttributes(attributeGroup.id)" :key="attribute.id">
                                        <label :for="'attribute_' + attribute.id"> 
                                            <input type="checkbox" :id="'attribute_' + attribute.id" :checked="isAttributeSelected(attribute.id)" @change="toggleAttribute(attribute.id)">
                                            <span>{{ attribute.title }} </span>
                                        </label>
                                    </div>

                                    <span class="btn btn--small btn--light btn--icon" @click="openAttributeModal(attributeGroup.id)">
                                        <span><?= __dx($po, 'admin', 'add attribute value') ?></span>
                                        <?= $this->Backend->materialIcon('add') ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                    
                        
                        <div class="input-group__actions">
                            <span class="btn btn--primary btn--icon" @click="openAttributeGroupModal">
                                <?= $this->Backend->materialIcon('add') ?>
                                <span><?= __dx($po, 'admin', 'add new attribute group') ?></span>
                            </span>
                        </div>
                        
                    </fieldset>
                    

                    <?php //Modale nuovi attributi ?>
                    <?= $this->element('admin/attribute-group-modal', [
                        'modalTitle' => __dx($po, 'admin', 'manage attributes'),
                        'vModel' => 'newAttributeGroup',
                        'closeAction' => 'closeAttributeGroupModal',
                        'optionList' => 'selectableAttributeGroups',
                        'addAction' => 'addAttributeGroup'
                    ]);?>
                    

                    <?php //Modale nuovo valore attributi ?>
                    <?= $this->element('admin/attribute-value-modal', [
                        'closeAction' => 'closeAttributeModal',
                        'vModel' => 'newAttribute',
                        'addAction' => 'addAttribute'
                    ]);?>

                    <?php  //Modale nuovi attributi varianti ?>
                    <?= $this->element('admin/attribute-group-modal', [
                        'modalTitle' => __dx($po, 'admin', 'manage variant attributes'),
                        'vModel' => 'newVariantAttributeGroup',
                        'closeAction' => 'closeVariantAttributeGroupModal',
                        'optionList' => 'selectableVariantAttributeGroups',
                        'addAction' => 'addVariantAttributeGroup'
                    ]); ?>

                    <?php  //Modale nuova variante ?>
                    <?php // Qui creo la variante con i campi base + attributi. Immagini ed altro le modifico in nuova pagina edit ?>
                    <?= $this->element('admin/variant-modal'); ?>
                    
                </div>
            </div>
            <?php echo $this->element('admin/tab-seo');?>
			<?php echo $this->element('admin/tab-social');?>           
        </div>
    </div>
    <?= $this->Form->hidden('draft', ['value' => 0]); ?>
    <?= $this->element('admin/save');?>
<?= $this->Form->end() ?>


<script>
<?php
$this->Html->script('/admin-assets/js/vue.js', ['block' => 'scriptBottom']);
$this->Html->script('/admin-assets/js/axios.min.js', ['block' => 'scriptBottom']);
$this->Html->script('/admin-assets/js/vuedraggable.umd.min.js', ['block' => 'scriptBottom']);
$this->Html->scriptStart(['block' => 'scriptBottom', 'type' => 'module']);
?>

axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;


let productApp = new Vue({
    el: '#product',
    components: {
        draggable: window['vuedraggable']
    },
    data :  {
        loading: true,
        resourceLoaded: 0,
        resourceRequired: 7,
        productId: <?= $item->id ?>,
        hasVariants: <?= $item->has_variants ? 'true' : 'false' ?>,
        ingnoreHasVariantsChange: false,
        attributeTypes: [], //tipologie attributi globali
        attributeGroups: [], //gruppi attributi globali
        attributes: [], //elenco completo attributi
        productAttributeGroups: [], //gruppi attributi associati al prodotto
        productAttributes: [], //elenco attributi associati al prodotto
        variantAttributeGroups: [], //gruppi attributi varianti associati al prodotto
        productVariants: [],
        
        newAttributeGroupPosition: 0,
        newVariantAttributeGroupPosition: 0,

        // attributi
        newAttributeGroup: {
            modal: false,
            position: 0,
            group: '',
            title: '',
            tyle: '',
            errors: {},
        },
        newAttribute: {
            modal: false,
            title: '',
            group: null,
            errors: {},
        },

        // varianti
        newVariantAttributeGroup: {
            modal: false,
            position: 0,
            group: '',
            title: '',
            tyle: '',
            errors: {},
        },

        variantModal: false,
        newVariant: {
            published: true,
            shop_product_id: <?= $item->id ?>,
            sku: null,
            stock: null,
            weight: null,
            price: null,
            discounted_price: null,
            price_net: null,
            discounted_price_net: null,
            attributes: [],
            errors: {}
        }


    },  

    computed: {
        selectableAttributeGroups() {
            return this.attributeGroups.filter(item1 => 
                !this.productAttributeGroups.some(item2 => item1.id === item2.id)
            );
        },
        selectableVariantAttributeGroups() {
            return this.attributeGroups.filter(item1 => 
                !this.variantAttributeGroups.some(item2 => item1.id === item2.id)
            );
        },
        filteredAttributes() {
            return this.attributes;
        },
    },
    
    mounted : function () {
        this.getAttributeTypes();
        this.getAttributeGroups();
        this.getAttributes();
        this.getProductAttributeGroups();
        this.getVariantAttributeGroups();
        this.getProductAttributes();
        this.getProductVariants();
    },

    methods: {
        //recupero valori attributo
        getFilteredAttributes: function(groupId) {
            return this.filteredAttributes.filter(item => item.attribute_group_id === groupId);
        },

        // valore selezionato ?
        isAttributeSelected: function(attributeId) {
            return this.productAttributes.some(item => item.id === attributeId);
        },

        isVariantAttributeSelected: function(attributeId) {
            return this.variantAttributes.some(item => item.id === attributeId);
        },

        // stampa errori
        getValidationErrors(field, target) {
        
            if (this[target].errors[field] == undefined || this[target].errors[field] == null) return false;
            return Object.values(this[target].errors[field]).join("<br>");
        },

        // collega scollega prodotto/attributo
        toggleAttribute: function(attributeId) {
            let isActive = this.isAttributeSelected(attributeId),
                action = !isActive ? '<?= $this->Url->build(['action' => 'link-attribute.json']) ?>' : '<?= $this->Url->build(['action' => 'unlink-attribute.json']) ?>';

                axios.post(action, {
                    attribute_id: attributeId,
                    shop_product_id: this.productId,
                })
                .then(function (response) {
                    if (response.status == 200){
                        if (isActive) {
                            if (response.data == 'success') {
                                let recordIndex = productApp.productAttributes.findIndex(el => el.id === attributeId);
                                if (recordIndex != -1) productApp.productAttributes.splice(recordIndex,1);
                            }
                            
                        } else {
                            productApp.productAttributes.push(response.data);
                        }
                        

                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
     
        },

        // gruppi attributi
        addAttributeGroup: function() {
            this.createJoinAttributeGroup('<?= $this->Url->build(['action' => 'add-attribute-group.json']) ?>', '<?= $this->Url->build(['action' => 'link-attribute-group.json']) ?>', 'newAttributeGroup', 'productAttributeGroups', 'newAttributeGroupPosition');            
        },

        //gruppi attribui variante
        addVariantAttributeGroup: function() {
            this.createJoinAttributeGroup('<?= $this->Url->build(['action' => 'add-variant-attribute-group.json']) ?>', '<?= $this->Url->build(['action' => 'link-variant-attribute-group.json']) ?>', 'newVariantAttributeGroup', 'variantAttributeGroups', 'newVariantAttributeGroupPosition');            
        },

        // funzione comunque per collegare un prodotto a dei Gruppi attributi (sia per filtri che per varianti)
        createJoinAttributeGroup: function(addAction, joinAction, model, target, positionTarget) {
            if (this[model].group == 'new') {
                this.showLoading();
                axios.post(addAction, {
                    title: this[model].title,
                    attribute_type_id: this[model].type,
                    shop_product_id: this.productId,
                    position: this[positionTarget]
                })
                .then(function (response) {

                    if (response.status == 200){
                        if (response.data.success) {
                            productApp[target].push(response.data.record);
                            productApp.attributeGroups.push(response.data.record);
                            productApp.closeAttributeGroupModal();
                            productApp.closeVariantAttributeGroupModal();
                            productApp[positionTarget]++;

                            if (target == 'variantAttributeGroups') {
                                // se stiamo modificando gli attributi della variante
                                productApp.newVariant.attributes.push({
                                    id: null,
                                    attribute_group_id: response.data.record.id,
                                    title: ''
                                });

                                if (productApp.productVariants.length) {
                                    productApp.setVariantsIncomplete();
                                }
                            }
             
                        } else {
                            productApp[model].errors = response.data.errors;
                        }
                        productApp.hideLoading();
                        
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            } else if (this[model].group != '') {
                this.showLoading();
                axios.post(joinAction, {
                    attribute_group_id: this[model].group,
                    shop_product_id: this.productId,
                    position: this[positionTarget]
                })
                .then(function (response) {
                    if (response.status == 200){
                        productApp[target].push(response.data);
                        productApp.closeAttributeGroupModal();
                        productApp.closeVariantAttributeGroupModal();
                        productApp[positionTarget]++;

                        if (target == 'variantAttributeGroups') {
                            productApp.newVariant.attributes.push({
                                id: null,
                                attribute_group_id: response.data.id,
                                title: ''
                            });

                            if (productApp.productVariants.length) {
                                productApp.setVariantsIncomplete();
                            }
                        }
                    }
                    productApp.hideLoading();
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
            
        },

        // elimina gruppo attributi
        deleteAttributeGroup: function(id) {
            this.confirmUnlinkAttributeGroup('<?= $this->Url->build(['action' => 'unlink-attribute-group.json']) ?>', id, 'productAttributeGroups', 'productAttributes');
        },

        // elimina gruppo attributi varianti
        deleteVariantAttributeGroup: function(id) {
            let confirmMessage = null;
            if (this.productVariants.length) {
                if (productApp.variantAttributeGroups.length > 1) {
                    // messaggio varianti aggiornate
                    confirmMessage = "<?=  __dx($po, 'admin', 'delete attribute group confirm with variant update')?>";
                } else {
                    //messaggio varianti eliminate
                    confirmMessage = "<?= __dx($po, 'admin', 'warning variants will be deleted') ?>";
                }
            }
     
            this.confirmUnlinkAttributeGroup('<?= $this->Url->build(['action' => 'unlink-variant-attribute-group.json']) ?>', id, 'variantAttributeGroups', null, confirmMessage);
        },

        // funzione comunque per scollegare un prodotto a dei Gruppi attributi (sia per filtri che per varianti)
        confirmUnlinkAttributeGroup: function(url, id, target, attributesTarget, message = null) {
            Swal.fire({
                title: "<?=  __dx($po, 'admin', 'delete attribute group confirm')?>",
                text: message,
                showCancelButton: true,
                confirmButtonText: "<?=  __d('admin', 'yes')?>",
                cancelButtonText: "<?=  __d('admin', 'undo')?>"
            }).then((result) => {
                if (!result.isConfirmed) return;
                this.unlinkAttributeGroup(url, id, target, attributesTarget);
                
            });
        },

        unlinkAttributeGroup: function(url, id, target, attributesTarget) { 
            axios.post(url, {
                    attribute_group_id: id,
                    shop_product_id: this.productId,
                })
                .then(function (response) {
                    if (response.status == 200){
                        let recordIndex = productApp[target].findIndex(el => el.id === id);
                        if (recordIndex != -1) productApp[target].splice(recordIndex,1);

                        

                        if (attributesTarget) productApp[attributesTarget] =  productApp[attributesTarget].filter(obj => obj.attribute_group_id !== id);

                        if (target == 'variantAttributeGroups') {
                            productApp.newVariant.attributes = productApp.newVariant.attributes.filter(obj => obj.attribute_group_id !== id);

                            if (productApp.productVariants.length) {
                                if (productApp.variantAttributeGroups.length) {
                                    // scollego gli attributi del gruppo dalle varianti
                                    productApp.unlinkVariantAttributes(id);
                                } else {
                                    //elimino tutte le varianti se non ci sono più gruppi variante
                                    productApp.deleteAllVariants();
                                }
                                
                            }
                        }
                       
                        
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },


        // aggiorna ordine attributi
        updateAttributeGroupsOrder: function() {
            let updatedOrder = this.productAttributeGroups.map(el => el.id);
            

            axios.post('<?= $this->Url->build(['action' => 'sort-attribute-groups.json']) ?>', {
                attribute_group_ids: updatedOrder,
                shop_product_id: this.productId,
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        // aggiorna ordine attributi varianti
        updateVariantAttributeGroupsOrder: function() {
            let updatedOrder = this.variantAttributeGroups.map(el => el.id);
            

            axios.post('<?= $this->Url->build(['action' => 'sort-variant-attribute-groups.json']) ?>', {
                attribute_group_ids: updatedOrder,
                shop_product_id: this.productId,
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        // valori attributi
        addAttribute: function() {
            this.showLoading();
            axios.post('<?= $this->Url->build(['action' => 'add-attribute.json']) ?>', {
                title: this.newAttribute.title,
                attribute_group_id: this.newAttribute.group,
                shop_product_id: this.productId,
            })
            .then(function (response) {

                if (response.status == 200){
                    if (response.data.success) {
                        productApp.attributes.push(response.data.record);
                        productApp.productAttributes.push(response.data.record);
                        productApp.closeAttributeModal();
                    } else {
                        productApp[model].errors = response.data.errors;
                    }
                    productApp.hideLoading();
                    
                }
            })
            .catch(function (error) {
                console.log(error);
            });      
        },

        //modali
        openAttributeGroupModal: function() {
            this.closeAttributeModal();
            this.newAttributeGroup.modal = true;
            document.body.classList.add('modal-open');
        },  
        closeAttributeGroupModal: function() {
            this.closeModal('newAttributeGroup', 'attributeGroup');
        },
        openVariantAttributeGroupModal: function() {
            if (!this.productVariants.length) {
                this.closeAttributeModal();
                this.newVariantAttributeGroup.modal = true;
                document.body.classList.add('modal-open');
            } else {
                Swal.fire({
                    title: "<?=  __dx($po, 'admin', 'warning')?>",
                    text: "<?=  __dx($po, 'admin', 'warning variant attributes add')?>",
                    showCancelButton: true,
                    confirmButtonText: "<?=  __d('admin', 'yes')?>",
                    cancelButtonText: "<?=  __d('admin', 'undo')?>"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closeAttributeModal();
                        this.newVariantAttributeGroup.modal = true;
                        document.body.classList.add('modal-open');
                    }
                    
                });
            }
            
        }, 
        closeVariantAttributeGroupModal: function() {
            this.closeModal('newVariantAttributeGroup', 'attributeGroup');
        },
        openAttributeModal: function(groupId) {
            this.closeAttributeGroupModal();
            this.newAttribute.modal = true;
            this.newAttribute.group = groupId;
            document.body.classList.add('modal-open');
        },  
        closeAttributeModal: function() {
            this.closeModal('newAttribute', 'attribute');
        },

        closeModal: function(target, type) {
            if (type == 'attributeGroup') {
                this[target].modal = false;
                this[target].group = '';
                this[target].title = '';
                this[target].type = '';
                this[target].errors = {};
                
            } else {
                this[target].modal = false;
                this[target].title = '';
                this[target].group = null;
                this[target].errors = {};
            }
            document.body.classList.remove('modal-open');
        },


        // varianti
        openVariantModal: function() {
            this.variantModal = true;
            document.body.classList.add('modal-open');
        },
        closeVariantModal: function() {
            this.variantModal = false;

            this.newVariant.sku = null;
            this.newVariant.stock = null;
            this.newVariant.weight = null;
            this.newVariant.price = null;
            this.newVariant.discounted_price = null;
            this.newVariant.price_net = null;
            this.newVariant.discounted_price_net = null;
            this.newVariant.errors =  {};

            this.newVariant.attributes.forEach((element, index) => {
                this.newVariant.attributes[index].id = null;
                this.newVariant.attributes[index].title = '';
            });

            document.body.classList.remove('modal-open');
        },
        addVariant: function() {
            this.showLoading();
            axios.post('<?= $this->Url->build(['action' => 'add-variant.json']) ?>', this.newVariant)
            .then(function (response) {
               
                if (response.status == 200){
                    if (response.data.success) {
                        productApp.productVariants.push(response.data.record);
                        productApp.closeVariantModal();
                        productApp.getAttributes();
                    } else {
                        productApp.newVariant.errors = response.data.errors;
                    }
                    productApp.hideLoading();
                    window.getFlash();
                    
                }
            })
            .catch(function (error) {
                console.log(error);
            });
            return;
        },
        deleteVariant: function(id) {
            Swal.fire({
                title: "<?=  __d('admin', 'delete confirm')?>",
                showCancelButton: true,
                confirmButtonText: "<?=  __d('admin', 'yes')?>",
                cancelButtonText: "<?=  __d('admin', 'undo')?>"
            }).then((result) => {
                if (!result.isConfirmed) return;

                axios.get('<?= $this->Url->build(['action' => 'deleteVariant.json']) ?>', {
                    params: {
                        id: id
                    }
                })
                .then(function (response) {
                    if (response.status == 200){
                        if (response.data.delete) {
                            let recordIndex = productApp.productVariants.findIndex(el => el.id === id);
                            if (recordIndex != -1) productApp.productVariants.splice(recordIndex,1);
                            window.getFlash();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.data.message,
                            });
                        }
                        
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            });
        },
        toggleVariantField : function (id,field) {
            let recordIndex = this.productVariants.findIndex(el => el.id === id);
            if (recordIndex == -1) return false; 

            this.productVariants[recordIndex][field] = !this.productVariants[recordIndex][field];


            axios.get('<?= $this->Url->build(['controller' => 'ShopProductVariants', 'action' => 'toggleField.json']) ?>', {
                params: {
                    id: id,
                    field: field
                }
            })
            .then(function (response) {
                if (response.status == 200 && response.data.newValue != productApp.productVariants[recordIndex][field]){
                    productApp.productVariants[recordIndex][field] = response.data.newValue;
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        goToVariant(id) {
            Swal.fire({
                title: "<?=  __dx($po, 'admin', 'warning')?>",
                text: "<?= __dx($po, 'admin', 'have you saved') ?>",
                showCancelButton: true,
                confirmButtonText: "<?=  __d('admin', 'yes')?>",
                cancelButtonText: "<?=  __d('admin', 'undo')?>"
            }).then((result) => {
                if (!result.isConfirmed) return;
                window.location.href = '/admin/shop-product-variants/edit/' + id;
            
            });
        },
        getAttributeValidationErrors(index) {
            if (this.newVariant.errors.attributes == undefined || this.newVariant.errors.attributes[index] == undefined || this.newVariant.errors.attributes[index] == null) return false;
            if (this.newVariant.errors.attributes[index].id !== undefined) {
                return Object.values(this.newVariant.errors.attributes[index].id).join("<br>");   
            }
            return false;
              
        },
        setVariantsIncomplete() {
            axios.post('<?= $this->Url->build(['action' => 'setVariantsIncomplete.json']) ?>', {
                id: this.productId
            })
            .then(function (response) {
                if (response.status == 200){

                    if (response.data == 'success') {
                        // svuoto le varianti
                        productApp.productVariants = [];
                        productApp.getProductVariants();
                    }
                    
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        unlinkVariantAttributes(attributeGroupId) {
            axios.post('<?= $this->Url->build(['action' => 'unlinkAllAttributes.json']) ?>', {
                shop_product_id: this.productId,
                attribute_group_id: attributeGroupId
            })
            .then(function (response) {
                if (response.status == 200){

                    if (response.data == 'success') {
                        // svuoto le varianti
                        productApp.productVariants = [];
                        productApp.getProductVariants();
                    }
                    
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        deleteAllVariants() {
            axios.post('<?= $this->Url->build(['action' => 'deleteAllVariants.json']) ?>', {
                id: this.productId
            })
            .then(function (response) {
                if (response.status == 200){

                    if (response.data.delete) {
                        // svuoto le varianti
                        productApp.productVariants = [];

                        // scollego tutti i gruppi variante
                        if (productApp.variantAttributeGroups.length){
                            productApp.variantAttributeGroups.forEach(element => {
                                productApp.unlinkAttributeGroup('<?= $this->Url->build(['action' => 'unlink-variant-attribute-group.json']) ?>', element.id, 'variantAttributeGroups');
                            });
                        }
                    }
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        //utilities
        formatPrice: function(price) {
            return new Intl.NumberFormat('it-IT', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2, // Mostra sempre due decimali
            }).format(price);
        },

        //popolo i dati
        getAttributeTypes: function() {
            this.getData('<?= $this->Url->build(['controller' => 'AttributeTypes', 'action' => 'get.json']) ?>', {}, 'attributeTypes');
        },
        getAttributeGroups: function() {
            this.getData('<?= $this->Url->build(['controller' => 'AttributeGroups', 'action' => 'get.json']) ?>', {}, 'attributeGroups');
        },
        getProductAttributeGroups: function() {
            this.getData('<?= $this->Url->build(['controller' => 'AttributeGroups', 'action' => 'get.json']) ?>', {productId: this.productId}, 'productAttributeGroups', () => {
                let max = 0;
                this.productAttributeGroups.forEach(el => {
                    if (el._matchingData.AttributeGroupsShopProducts.position > max) max = el._matchingData.AttributeGroupsShopProducts.position;
                });
                this.newAttributeGroupPosition =  max + 1;
            });
        },
        getVariantAttributeGroups: function() {
            this.getData('<?= $this->Url->build(['controller' => 'AttributeGroups', 'action' => 'get.json']) ?>', {productId: this.productId, variant: true}, 'variantAttributeGroups', () => {
                let max = 0;
                this.variantAttributeGroups.forEach(el => {
                    if (el._matchingData.AttributeGroupsShopVariants.position > max) max = el._matchingData.AttributeGroupsShopVariants.position;
                    this.newVariant.attributes.push({
                        id: null,
                        attribute_group_id: el.id,
                        title: ''
                    });
                });
                this.newVariantAttributeGroupPosition =  max + 1;
            });
        },
        getAttributes: function() {
            this.getData('<?= $this->Url->build(['controller' => 'Attributes', 'action' => 'get.json']) ?>', {}, 'attributes');
        },
        getProductAttributes: function() {
            this.getData('<?= $this->Url->build(['controller' => 'Attributes', 'action' => 'get.json']) ?>', {productId: this.productId}, 'productAttributes');
        },
        getProductVariants: function() {
            this.getData('<?= $this->Url->build(['controller' => 'ShopProductVariants', 'action' => 'get.json']) ?>', {productId: this.productId}, 'productVariants');
        },
        getData: function(url, params, target, callbackFunction = null) {
            axios.get(url, {
                params: params
            })
			.then(function (response) {
				if (response.status == 200){
                    productApp.resourceLoaded++;
                    productApp[target] = response.data;
                    if( typeof callbackFunction == "function" ) callbackFunction();
				}
			})
			.catch(function (error) {
				console.log(error);
			});
        },
        showLoading: function() {
            document.querySelector('body').classList.add('body--loading');
        },
        hideLoading: function() {
            document.querySelector('body').classList.remove('body--loading');
        }

    },
    watch: {
        resourceLoaded: function(n,o) {
            if (n == this.resourceRequired){
                this.loading = false;
                this.hideLoading();
            }
        },
        hasVariants: function(n,o) {
            
            if (this.ingnoreHasVariantsChange) return;

            // se modifico il valore hasVariants chiedo conferma prima di procedere 
            // e in caso cancello eventuali varianti/variante per prodotto singolo create
            let showWarning = false,
                variantsWarning = '<?=  __dx($po, 'admin', 'warning variants switch')?>',
                noVariantsWarning = '<?=  __dx($po, 'admin', 'warning no variants switch')?>';
           

            if (!n && (this.productVariants.length || this.variantAttributeGroups.length)) showWarning = true; // se passo da un prodotto con a senza varianti e ci sono varianti mostro il warning
            if (n) showWarning = true;

            if (showWarning) {
                Swal.fire({
                    title: '<?= __dx($po, 'admin', 'warning') ?>',
                    text: n ? variantsWarning : noVariantsWarning,
                    showCancelButton: true,
                    confirmButtonText: "<?=  __d('admin', 'yes')?>",
                    cancelButtonText: "<?=  __d('admin', 'undo')?>"
                }).then((result) => {
                    if (!result.isConfirmed) {
                        this.ingnoreHasVariantsChange = true;
                        this.hasVariants = o;
                        this.$nextTick(() => {
                            this.ingnoreHasVariantsChange = false;
                        });
                        return;
                    }

                    this.deleteAllVariants();
                    // salvo has_variants sul cambio
                    axios.get('<?= $this->Url->build(['controller' => 'ShopProducts', 'action' => 'toggleField.json']) ?>', {
                        params: {
                            id: productApp.productId,
                            field: 'has_variants'
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                });
            }

            
        }

        
    },
    
});
<?php
$this->Html->scriptEnd();
?>
</script>