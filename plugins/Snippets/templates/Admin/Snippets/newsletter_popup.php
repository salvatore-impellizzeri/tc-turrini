<?php
$this->extend('/Admin/Common/edit');
?>
<?php
echo $this->Form->create($item);
?>
    <fieldset class="input-group">
        <legend class="input-group__info">
            Generali
        </legend>
        <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-11']);
        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-1']);
        
        
        ?>
    </fieldset>

    <fieldset class="input-group">
        <legend class="input-group__info">
            Contenuto popup
        </legend>

        <?php
        echo $this->Form->control('title_2', ['label' => __d('admin', 'title')]);
        echo $this->Form->editor('text', ['label' => __d('admin', 'text')]);
        echo $this->Form->control('title_3', ['label' => 'CTA', 'extraClass' => 'span-12']);
        echo $this->element('admin/uploader/image', [
            'scope' => "preview", 
            'width' => 480 * 1.5, 
            'height' => 680 * 1.5, 
        ]);
        ?>

    </fieldset>



<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
