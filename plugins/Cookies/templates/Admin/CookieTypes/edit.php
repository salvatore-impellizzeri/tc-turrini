<?php
$this->extend('/Admin/Common/edit');
?>
<?php 
	echo $this->Form->create($item);
?>
<div class="grid-builder grid-builder--top">
    <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-8']);
        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-1']);

    ?>
</div>

<div class="grid-builder grid-builder--blocks">
    <div class="editor">
        <?php
			echo $this->Form->control('text', ['label' => __d('admin', 'text')]);
		?>
    </div>
</div>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
