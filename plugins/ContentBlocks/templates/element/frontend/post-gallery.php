<?php if(!empty($images['gallery'])):?>
    <div class="post-gallery post-section">
        <?php foreach ($images['gallery'] as $image): ?>
            <a href="<?= $this->Frontend->resize($image->path, 1920, 1080) ?>" data-fancybox="gallery-<?= $item->id ?>">
                <img src="<?= $this->Frontend->crop($image->path, 500, 400) ?>" alt="<?= h($image->title) ?>">
            </a>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
