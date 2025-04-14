<?php
$url = $this->Frontend->url('/services/view/'.$item->id);
?>

<div class="service-preview">
    <a class="service-preview__image" href="<?= $url ?>">
        <?php if (!empty($item->preview->path)): ?>
            <img src="<?= $this->Frontend->image($item->preview->path) ?>" alt="">
        <?php endif; ?>
    </a>
    <div class="service-preview__content">
        <h2><a href="<?= $url ?>"><?= $item->title ?></a></h2>

        <?php if (!empty($item->excerpt)): ?>
            <div class="service-preview__excerpt"><?= $item->excerpt ?></div>
        <?php endif; ?>

        <a href="<?= $url ?>" class="service-preview__cta">
            <?= __d('global', 'read more') ?>
        </a>
    </div>
</div>
