<div class="save">		
    <?= $this->Backend->iconButton(__d('admin', 'save form'), 'done', ['value' => 'stay', 'class' => 'btn--light', '@click' => 'save()']) ?>
    <?= $this->Backend->iconButton(__d('admin', 'save exit'), 'done_all', ['class' => 'btn--primary', '@click' => 'save(true)']) ?>			
</div>
