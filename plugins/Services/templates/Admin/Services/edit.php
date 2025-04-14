<?php $this->extend('/Admin/Common/edit'); ?>

<?php echo $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']); ?>
    <div class="tabs" data-tabs>
        <?php echo $this->element('admin/tabs-menu');?>
        <div class="tabs__content">
            <div class="tabs__tab" data-tab="edit">
                <fieldset class="input-group">
                    <legend class="input-group__info">
                        Generali        
                    </legend>
                    <?php
                    echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-10']);
                    echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
                    ?>
                </fieldset>
                
                <fieldset class="input-group">
                    <legend class="input-group__info">
                        Contenuto 
                    </legend>
                        <?php
                            echo $this->element('admin/uploader/image', [
                            'scope' => 'preview',
                            'title' => __dx('news', 'admin', 'preview'),
                            'width' => 500,
                            'height' => 500
                        ]);

                        echo $this->Form->editor('excerpt', ['label' => __d('admin', 'excerpt')]);
                        echo $this->Form->editor('text', ['label' => __d('admin', 'text')]);

                        echo $this->element('admin/uploader/gallery', [
                            'scope' => 'gallery'
                        ]);
                        ?>
                 
                </fieldset>
            </div>
            <?php echo $this->element('admin/tab-seo');?>
			<?php echo $this->element('admin/tab-social');?> 
        </div>
    </div>

    <?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
