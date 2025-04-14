<?php foreach ($items as $item): ?>
    <?= $this->element('Events.event-preview', ['item' => $item]) ?>
<?php endforeach; ?>

<?= $this->element('paginator'); ?>
