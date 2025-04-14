<?php $this->extend('/Admin/Common/edit');?>

<?= $this->Form->create($item) ?>
<div class="tabs" data-tabs>
    <?php echo $this->element('admin/tabs-menu');?>
    <div class="tabs__content">
        <div class="tabs__tab" data-tab="edit">
            <fieldset class="input-group">
                <?php
                echo $this->Form->control('title', ['extraClass' => 'span-7', 'label' => __d('admin', 'title')]);
                echo $this->Form->control('published', ['type' => 'checkbox', 'extraClass' => 'span-2', 'label' => __d('admin', 'published')]);
                echo $this->Form->control('date', ['extraClass' => 'span-3', 'label' => __dx($po, 'admin', 'date')]);

                echo $this->element('admin/uploader/image', [
                    'scope' => 'preview',
					'width' => 440*1.5,
					'height' => 320*1.5,
				]);

                echo $this->Form->editor('excerpt', ['label' => __d('admin', 'excerpt')]);
                ?>
            </fieldset>

            <?php if (!empty($item->content_blocks)): ?>
                <?php foreach ($item->content_blocks as $blockIndex => $contentBlock): 
                    if (empty($contentBlock->content_block_type->layout)) continue;

                    //verifico se esiste l'element corrispondente
                    $includePath = ROOT.DS.'plugins'.DS.'ContentBlocks'.DS.'templates'.DS.'element'.DS.'admin'.DS.$contentBlock->content_block_type->layout.'.php';
                    if(!file_exists($includePath)) continue;
                    ?>
                    <fieldset class="input-group">
                        <legend class="input-group__info"><?= $contentBlock->title ?></legend>
                        <?= $this->element('ContentBlocks.admin/'.$contentBlock->content_block_type->layout, [
                            'blockIndex' => $blockIndex,
                            'blockId' => $contentBlock->id
                        ]) ?>
                    </fieldset>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php echo $this->element('admin/tab-seo');?>
        <?php echo $this->element('admin/tab-social');?> 
    </div>

</div>




<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
