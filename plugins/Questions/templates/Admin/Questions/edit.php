<?php
$this->extend('/Admin/Common/edit');
?>
<?php 
	echo $this->Form->create($item);
?>
<fieldset class="input-group">
    <?php
        echo $this->Form->control('question', ['label' => __dx($po, 'admin', 'question'), 'extraClass' => 'span-11']);
        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-1']);
		echo $this->Form->control('answer', ['label' => __dx($po, 'admin', 'answer')]);
    ?>
</fieldset>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
