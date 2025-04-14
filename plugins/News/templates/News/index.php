<?php foreach ($items as $item): ?>
    <?= $this->element('News.news-preview', ['item' => $item]) ?>
<?php endforeach; ?>

<?= $this->element('paginator') ?>
