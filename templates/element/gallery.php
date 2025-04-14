<?php if (!empty($images)): ?>
    <div class="gallery">
        <?php foreach ($images as $img): ?>
            <a href="<?= $this->Frontend->resize($img->path, 1920) ?>" data-fancybox="gallery">
                <img src="<?= $this->Frontend->crop($img->path, 600, 400) ?>" alt="<?= $img->title ?>">
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
