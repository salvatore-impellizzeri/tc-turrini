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

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
