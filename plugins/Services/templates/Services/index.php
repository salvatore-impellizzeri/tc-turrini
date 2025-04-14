<?php foreach ($items as $item): ?>
    <?= $this->element('Services.service-preview', ['item' => $item]) ?>
<?php endforeach; ?>
