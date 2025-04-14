<?php
$this->extend('/Admin/Common/edit');
?>
<?php 
	echo $this->Form->create($item);
?>
<fieldset class="input-group">
    <?php
		echo $this->Form->control('rewritten', ['label' => __dx($po, 'admin', 'rewritten'), 'extraClass' => 'span-5']);
		echo $this->Form->control('original', ['label' => __dx($po, 'admin', 'original'), 'extraClass' => 'span-5']);
		echo $this->Form->control('locale', ['label' => __dx($po, 'admin', 'locale'), 'extraClass' => 'span-1']);
		echo $this->Form->control('code', ['label' => __dx($po, 'admin', 'code'), 'extraClass' => 'span-1']);
		
		echo $this->Form->control('plugin', ['label' => __dx($po, 'admin', 'plugin'), 'extraClass' => 'span-3']);
		echo $this->Form->control('controller', ['label' => __dx($po, 'admin', 'controller'), 'extraClass' => 'span-3']);
		echo $this->Form->control('action', ['label' => __dx($po, 'admin', 'action'), 'extraClass' => 'span-3']);
		echo $this->Form->control('param', ['label' => __dx($po, 'admin', 'param'), 'extraClass' => 'span-3']);
		
		echo $this->Form->control('custom', ['label' => __dx($po, 'admin', 'custom'), 'extraClass' => 'span-3']);
	?>
</fieldset>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
