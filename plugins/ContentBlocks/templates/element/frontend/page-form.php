<div class="page-form page-section">

    <div class="page-form__intro">

        <?php if (!empty($item->title_1)) : ?>
            <h2 class="page-form__title"><?= $item->title_1 ?></h2>
        <?php endif; ?>

        <?php if (!empty($item->text_1)) : ?>
            <div class="page-form__content"><?= $item->text_1 ?></div>
        <?php endif; ?>

    </div>

    <?= $this->element('Contacts.contact-form', ['id' => 1]) ?>
</div>