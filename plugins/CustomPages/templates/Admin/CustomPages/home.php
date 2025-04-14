<?php
$this->extend('/Admin/Common/edit');
?>

<?= $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']) ?>

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
                    echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-1']);
                    ?>
                </fieldset>

                <fieldset class="input-group">
                    <legend class="input-group__info">
                        Parte alta
                    </legend>
                    <?php echo $this->Form->editor('text_1', ['label' => 'Editor', 'class' => 'span-6']); ?> 
                    <?php echo $this->Form->inlineEditor('string_2', ['label' => 'Editor inline', 'class' => 'span-6']); ?>
                    <?php echo $this->element('admin/uploader/image', ['scope' => 'image-1', 'title' => 'Hero image', 'width' => 1920, 'height' => 1080, 'mobile' => ['width' => 480, 'height' => 890]]); ?>
                    <?php echo $this->element('admin/uploader/file', ['scope' => 'file-1', 'title' => 'Allegato:  catalogo']); ?>
                    <?php echo $this->element('admin/uploader/icon', ['scope' => 'icon-1', 'title' => 'Icona della sezione']); ?>
                    <?php echo $this->element('admin/uploader/gallery', ['scope' => 'gallery', 'title' => 'Griglia immagini']); ?>
                    <?php echo $this->element('admin/uploader/file-set',['scope' => 'fileset-1', 'title' => 'Allegati fine news']); ?>
                    <?php echo $this->element('admin/uploader/icon-set', ['scope' => 'iconset', 'title' => 'Set icone servizi']); ?>

                    <?php echo $this->Form->geocode('geocode'); ?>
                </fieldset>

            </div>
            <?php echo $this->element('admin/tab-seo');?>
			<?php echo $this->element('admin/tab-social');?>            
        </div>
    </div>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>

<?php 
$coords = !empty($item->geocode) ? $item->geocode : null;
$this->element('admin/geocode-script', array('coords' => $coords));
?>