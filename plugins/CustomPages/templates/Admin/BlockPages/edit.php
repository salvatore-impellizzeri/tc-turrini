<?php $this->extend('/Admin/Common/edit');?>

<?= $this->Form->create($item) ?>
<div class="tabs" data-tabs>
    <?php echo $this->element('admin/tabs-menu');?>
    <div class="tabs__content">
        <div class="tabs__tab" data-tab="edit">
            <fieldset class="input-group">
                <legend class="input-group__info">
                    Generale
                </legend>
                <?php
                echo $this->Form->control('title', ['extraClass' => 'span-10', 'label' => __d('admin', 'title')]);
                echo $this->Form->control('published', ['type' => 'checkbox', 'extraClass' => 'span-2', 'label' => __d('admin', 'published')]);    

                if ($loggedUser['group_id'] == 1) {
                    echo $this->Form->control('fixed_header', ['label' => __dx($po, 'admin', 'fixed_header'), 'type' => 'checkbox', 'extraClass' => 'span-6']);
                    echo $this->Form->control('light_header', ['label' => __dx($po, 'admin', 'light_header'), 'type' => 'checkbox', 'extraClass' => 'span-6']);
                }
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
