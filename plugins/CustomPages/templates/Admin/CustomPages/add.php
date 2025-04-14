<?php
$this->extend('/Admin/Common/edit');
?>

<?= $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']) ?>

    <fieldset class="input-group">
        <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-6']);
        echo $this->Form->control('layout', ['label' => __dx($po, 'admin', 'layout'), 'extraClass' => 'span-4']);
        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
        ?>
    </fieldset>


<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
