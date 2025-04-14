<?php  
if (empty($images['image'])) return;
?>
<div class="post-image post-section">
    <img src="<?= $this->Frontend->image($images['image']->path) ?>" alt="<?= h($images['image']->alt) ?>">
</div>