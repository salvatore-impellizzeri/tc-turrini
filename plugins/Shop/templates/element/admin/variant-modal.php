<?php 
use Cake\Core\Configure;
?>
<div class="modal" :class="{open: variantModal}">
    <div class="modal__header">
        <span class="modal__title"><?= __dx($po, 'admin', 'add new variant') ?></span>
        <span class="modal__close" @click="closeVariantModal"><?= $this->Backend->materialIcon('close') ?></span>
    </div>
    <div class="modal__main">
        <div class="input text required">
            <label class="label-heading" for="newVariantSku"><?= __dx($po, 'admin', 'sku') ?></label>
            <input type="text" required v-model="newVariant.sku" id="newVariantSku">
            <div class="error-message" v-if="getValidationErrors('sku', 'newVariant')">{{ getValidationErrors('sku', 'newVariant') }}</div>
        </div>

        <div class="input select required" v-for="(attributeGroup, index) in variantAttributeGroups" v-bind:key="attributeGroup.attribute_group_id"> 
            <label class="label-heading" for="newVariantSku">{{ attributeGroup.title }}</label>
            <div data-vue-select-add :class="[newVariant.attributes[index].id == 'new' ? 'new-value' : '' ]">
                <select v-model="newVariant.attributes[index].id">
                    <option disabled value="null"><?= __dx($po, 'admin', 'choose attribute') ?></option>
                    <option v-for="attribute in getFilteredAttributes(attributeGroup.id)" :value="attribute.id" :key="attribute.id">
                        {{ attribute.title }}
                    </option>
                    <option value="new"><?= __dx($po, 'admin', 'add new attribute') ?></option>
                </select>
                <div data-vue-select-add-new>
                    <input type="text" required v-model="newVariant.attributes[index].title" >
                    <span data-vue-select-add-close class="material-symbols-rounded" @click="newVariant.attributes[index].id = null">close</span>
                </div>
                
            </div>
            <div class="error-message" v-if="getAttributeValidationErrors(index)" v-html="getAttributeValidationErrors(index)"></div>
            
        </div>

        <?php if (Configure::read('Shop.vatIncuded')): ?>
            <div class="input number required">
                <label class="label-heading" for="newVariantPrice"><?= __dx($po, 'admin', 'price') ?></label>
                <input type="number" required v-model="newVariant.price" id="newVariantPrice">
                <div class="error-message" v-if="getValidationErrors('price', 'newVariant')">{{ getValidationErrors('price', 'newVariant') }}</div>
            </div>

            <div class="input number">
                <label class="label-heading" for="newVariantDiscountedPrice"><?= __dx($po, 'admin', 'discounted_price') ?></label>
                <input type="text" v-model="newVariant.discounted_price" id="newVariantDiscountedPrice">
                <div class="error-message" v-if="getValidationErrors('discounted_price', 'newVariant')">{{ getValidationErrors('discounted_price', 'newVariant') }}</div>
            </div>

        <?php else: ?>
            <div class="input number required">
                <label class="label-heading" for="newVariantPriceNet"><?= __dx($po, 'admin', 'price_net') ?></label>
                <input type="number" required v-model="newVariant.price_net" id="newVariantPriceNet">
                <div class="error-message" v-if="getValidationErrors('price_net', 'newVariant')">{{ getValidationErrors('price_net', 'newVariant') }}</div>
            </div>

            <div class="input number required">
                <label class="label-heading" for="newVariantDiscountedPriceNet"><?= __dx($po, 'admin', 'discounted_price_net') ?></label>
                <input type="text" required v-model="newVariant.discounted_price_net" id="newVariantDiscountedPriceNet">
                <div class="error-message" v-if="getValidationErrors('discounted_price_net', 'newVariant')">{{ getValidationErrors('discounted_price_net', 'newVariant') }}</div>
            </div>
        <?php endif; ?>


        <div class="input text required">
            <label class="label-heading" for="newVariantStock"><?= __dx($po, 'admin', 'stock') ?></label>
            <input type="text" required v-model="newVariant.stock" id="newVariantWeight">
            <div class="error-message" v-if="getValidationErrors('stock', 'newVariant')">{{ getValidationErrors('stock', 'newVariant') }}</div>
        </div>

        <div class="input text required">
            <label class="label-heading" for="newVariantWeight"><?= __dx($po, 'admin', 'weight') ?></label>
            <input type="text" required v-model="newVariant.weight" id="newVariantWeight">
            <div class="error-message" v-if="getValidationErrors('weight', 'newVariant')">{{ getValidationErrors('weight', 'newVariant') }}</div>
        </div>
    </div>
    <div class="modal__footer modal__footer--grid2">
        <span class="btn btn--icon btn--primary" @click="addVariant">
            <?= $this->Backend->materialIcon('add') ?>
            <span><?= __d('admin', 'add') ?></span>
        </span>

        <span class="btn btn--icon btn--light" @click="closeVariantModal">
            <?= $this->Backend->materialIcon('undo') ?>
            <span><?= __d('admin', 'undo') ?></span>
        </span>
    </div>
</div>