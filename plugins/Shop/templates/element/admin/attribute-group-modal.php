<div class="modal" :class="{open: <?= $vModel ?>.modal }">
    <div class="modal__header">
        <span class="modal__title"><?= $modalTitle ?></span>
        <span class="modal__close" @click="<?= $closeAction ?>"><?= $this->Backend->materialIcon('close') ?></span>
    </div>
    <div class="modal__main">
        <div class="input select">
            <select v-model="<?= $vModel ?>.group">
                <option disabled value=""><?= __dx($po, 'admin', 'choose attribute group') ?></option>
                <option v-for="attributeGroup in <?= $optionList ?>" :value="attributeGroup.id">{{ attributeGroup.title }}</option>
                <option value="new"><?= __dx($po, 'admin', 'add new attribute group option') ?></option>
            </select>
        </div>

        <div v-if="<?= $vModel ?>.group == 'new'">
            <div class="input select required">
                <select v-model="<?= $vModel ?>.type">
                    <option disabled value=""><?= __dx($po, 'admin', 'choose attribute type') ?></option>
                    <option v-for="attributeType in attributeTypes" :value="attributeType.id">{{ attributeType.title }}</option>
                </select>
                <div class="error-message" v-if="getValidationErrors('attribute_type_id', '<?= $vModel ?>')">{{ getValidationErrors('attribute_type_id', '<?= $vModel ?>') }}</div>
            </div>

            <div class="input text required">
                <input type="text" required  v-model="<?= $vModel ?>.title" placeholder="<?= __dx($po, 'admin', 'new attribute title') ?>">
                <div class="error-message" v-if="getValidationErrors('title', '<?= $vModel ?>')">{{ getValidationErrors('title', '<?= $vModel ?>') }}</div>
            </div>
        </div>
    </div>
    <div class="modal__footer modal__footer--grid2">
        <span class="btn btn--icon btn--primary" @click="<?= $addAction ?>">
            <?= $this->Backend->materialIcon('add') ?>
            <span><?= __d('admin', 'add') ?></span>
        </span>

        <span class="btn btn--icon btn--light" @click="<?= $closeAction ?>">
            <?= $this->Backend->materialIcon('undo') ?>
            <span><?= __d('admin', 'undo') ?></span>
        </span>
    </div>
</div>