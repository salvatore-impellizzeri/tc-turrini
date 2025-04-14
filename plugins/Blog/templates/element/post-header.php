<header class="post__header">
    <div class="post__date">
        <?= $this->Frontend->formatDate($post->date) ?>
    </div>
    <h1 class="post__title"><?= $post->title ?></h1>

    <?php if (!empty($post->tags)): ?>
        <div class="post__tags">
            <?php
            $tagLinks = [];
            foreach ($post->tags as $tag) {
                $tagLinks[] = '<span>'.$tag->title.'</span>';
            }
            echo implode('', $tagLinks);
            ?>
        </div>
    <?php endif; ?>

</header>
