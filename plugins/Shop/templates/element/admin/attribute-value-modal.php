<div class="modal" :class="{open: <?= $vModel ?>.modal}">
    <div class="modal__header">
        <span class="modal__title"><?= __dx($po, 'admin', 'add attribute value') ?></span>
        <span class="modal__close" @click="<?= $closeAction ?>"><?= $this->Backend->materialIcon('close') ?></span>
    </div>
    <div class="modal__main">
        <div class="input text required">
            <input type="text" required v-model="<?= $vModel ?>.title" placeholder="<?= __dx($po, 'admin', 'new value title') ?>">
            <div class="error-message" v-if="getValidationErrors('title', '<?= $vModel ?>')">{{ getValidationErrors('title', '<?= $vModel ?>') }}</div>
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