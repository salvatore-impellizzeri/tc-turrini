<div class="contacts">
    <div class="contacts__infos">
        <h1><?= $item->title ?></h1>

        <?= $item->text_1 ?>
    </div>

    <div class="contacts__form">
        <?= $this->element('Contacts.contact-form', ['id' => 1]) ?>
    </div>
</div>
