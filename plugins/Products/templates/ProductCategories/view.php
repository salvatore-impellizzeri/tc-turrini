<?php $this->extend('/Common/catalog') ?>
<?php $this->set('currentCategory', $item->id) ?>

<?php foreach ($products as $item): ?>
    <?= $this->element('product-preview', [
        'url' => $this->Frontend->url('/products/view/'.$item->id),
        'title' => $item->title,
        'image' => $item->preview->path ?? null,
        'excerpt' => $item->excerpt ?? null
    ]) ?>
<?php endforeach; ?>
