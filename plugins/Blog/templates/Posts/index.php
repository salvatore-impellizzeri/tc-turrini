<div class="blog">
    

    <div class="blog__grid">
        <?php foreach ($items as $post): ?>
            <?= $this->element('Blog.post-preview', ['post' => $post]) ?>
        <?php endforeach; ?>
    </div>


    <?php echo $this->element('render-block', [
        'id' => 12,
        'name' => 'page-text-media-left',
        ]); ?>

    <?= $this->element('paginator') ?>
</div>
