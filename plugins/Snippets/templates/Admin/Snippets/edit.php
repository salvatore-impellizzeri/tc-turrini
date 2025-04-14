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

	if (!empty($loggedUser['group_id']) && $loggedUser['group_id'] == 1){
		echo $this->Form->control('backend_description', ['label' => __dx($po, 'admin', 'backend_description'), 'extraClass' => 'span-6']);
	}

	if (!empty($loggedUser['group_id']) && $loggedUser['group_id'] == 1){
		echo $this->Form->control('layout', ['label' => __dx($po, 'admin', 'layout'), 'extraClass' => 'span-6']);
	}

	echo $this->Form->editor('text', ['label' => __d('admin', 'text')]);
    ?>
</fieldset>



<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
