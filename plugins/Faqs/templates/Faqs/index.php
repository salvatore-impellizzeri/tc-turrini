<?php foreach ($items as $item): ?>
    <?= $this->element('Faqs.faq-preview', ['item' => $item]) ?>
<?php endforeach; ?>
