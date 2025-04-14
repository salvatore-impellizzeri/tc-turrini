<?php
$this->extend('/Admin/Common/edit');
?>

<?= $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']) ?>

    <fieldset class="input-group">
        <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-6']);
        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-2']);

        if ($loggedUser['group_id'] == 1) {
            echo $this->Form->control('fixed_header', ['label' => __dx($po, 'admin', 'fixed_header'), 'type' => 'checkbox', 'extraClass' => 'span-6']);
            echo $this->Form->control('light_header', ['label' => __dx($po, 'admin', 'light_header'), 'type' => 'checkbox', 'extraClass' => 'span-6']);
        }
        ?>
    </fieldset>


<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
