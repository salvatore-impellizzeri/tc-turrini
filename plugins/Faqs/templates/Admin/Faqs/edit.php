<?php $this->extend('/Admin/Common/edit'); ?>

<?php echo $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']); ?>
    <fieldset class="input-group">
        <legend class="input-group__info">
            Generali        
        </legend>
        <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-10']);
        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
        echo $this->Form->editor('excerpt', ['label' => __d('admin', 'text')]);
        ?>
    </fieldset>

    <?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
