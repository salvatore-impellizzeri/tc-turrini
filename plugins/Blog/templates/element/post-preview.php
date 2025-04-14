<?php
use Cake\View\Helper\TextHelper;

$extraClass = $extraClass ?? '';
$url = $this->Frontend->url('/posts/view/'.$post->id);
?>

<article class="post-preview <?= $extraClass ?>">

    <?php if (!empty($post->preview)): ?>
        <a class="post-preview__image" href="<?= $url ?>">
            <img src="<?= $this->Frontend->image($post->preview->path) ?>" alt="<?= h($post->preview->title) ?>">
        </a>
    <?php endif; ?>

    <div class="post-preview__content">
        <span class="post-preview__date">
            <?= $this->Frontend->formatDate($post->date) ?>
        </span>
        <h2 class="post-preview__title">
            <a href="<?= $url ?>"><?= $post->title ?></a>
        </h2>

        <?php if (!empty($post->excerpt)): ?>
            <div class="post-preview__excerpt">
                <?= $post->excerpt ?>
            </div>
        <?php endif; ?>

    </div>

</article>
