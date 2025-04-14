    <div class="save">		
		<?= $this->Backend->iconButton(__d('admin', 'save form'), 'done', ['value' => 'stay', 'class' => 'btn--light']) ?>
		<?= $this->Backend->iconButton(__d('admin', 'save exit'), 'done_all', ['class' => 'btn--primary']) ?>			
	</div>
<?php echo $this->Form->control('_stay', ['type' => 'hidden', 'value' => $_SESSION['_stay'] ?? false]); ?>