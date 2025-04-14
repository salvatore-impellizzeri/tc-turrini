<?php $this->extend('/Admin/Common/edit'); ?>
<?= $this->Form->create($item) ?>

    <fieldset class="input-group">
        <?php
        echo $this->Form->control('title', ['extraClass' => 'span-10']);
        echo $this->Form->control('published', ['type' => 'checkbox', 'extraClass' => 'span-2']);
        ?>
    </fieldset>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
