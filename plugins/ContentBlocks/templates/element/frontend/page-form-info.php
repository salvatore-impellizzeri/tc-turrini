<div class="page-form-info page-section">

    <div class="page-form-info__intro">

        <?php if (!empty($item->title_1)) : ?>
            <h2 class="page-form-info__title"><?= $item->title_1 ?></h2>
        <?php endif; ?>

        <?php if (!empty($item->text_1)) : ?>
            <div class="page-form-info__text"><?= $item->text_1 ?></div>
        <?php endif; ?>

    </div>

    <div class="page-form-info__content">
        <?= $this->element("contact-info", [
            "phone" => $item->title_2,
            "email" => $item->title_3,
            "address" => $item->title_4,            
        ]) ?>
        <?= $this->element('Contacts.contact-form', ['id' => 1]) ?>
    </div>
</div>