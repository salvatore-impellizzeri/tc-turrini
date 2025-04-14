<?php
use Cake\View\Helper\TextHelper;

$extraClass = $extraClass ?? '';
$url = $this->Frontend->url('/events/view/'.$item->id);
?>

<article class="event-preview <?= $extraClass ?>">

    <?php if (!empty($item->preview)): ?>
        <a class="event-preview__image" href="<?= $url ?>">
            <img src="<?= $this->Frontend->image($item->preview->path) ?>" alt="<?= $item->preview->title ?>">
        </a>
    <?php endif; ?>

    <div class="event-preview__content">
        <span class="event-preview__content__date">
            <?= $this->Frontend->formatDate($item->date) ?>
        </span>
        <h2>
            <a href="<?= $url ?>"><?= $item->title ?></a>
        </h2>

        <?php if (!empty($item->excerpt)): ?>
            <div class="event-preview__content__excerpt">
                <?= $item->excerpt ?>
            </div>
        <?php endif; ?>

        <a href="<?= $url ?>" class="event-preview__content__cta"><?= __d('global', 'read more') ?></a>
    </div>

</article>
