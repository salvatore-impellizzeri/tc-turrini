<?php
use Cake\View\Helper\TextHelper;

$extraClass = $extraClass ?? '';
$url = $this->Frontend->url('/news/view/'.$item->id);
?>

<article class="news-preview <?= $extraClass ?>">

    <?php if (!empty($item->preview)): ?>
        <a class="news-preview__image" href="<?= $url ?>">
            <img src="<?= $this->Frontend->image($item->preview->path) ?>" alt="<?= $item->preview->title ?>">
        </a>
    <?php endif; ?>

    <div class="news-preview__content">
        <span class="news-preview__content__date">
            <?= $this->Frontend->formatDate($item->date) ?>
        </span>
        <h2>
            <a href="<?= $url ?>"><?= $item->title ?></a>
        </h2>

        <?php if (!empty($item->excerpt)): ?>
            <div class="news-preview__content__excerpt">
                <?= $item->excerpt ?>
            </div>
        <?php endif; ?>

        <a href="<?= $url ?>" class="news-preview__content__cta"><?= __d('global', 'read more') ?></a>
    </div>

</article>
