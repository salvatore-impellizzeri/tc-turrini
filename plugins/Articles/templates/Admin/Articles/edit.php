<?php
$this->extend('/Admin/Common/edit');
?>
<?php 
	echo $this->Form->create($item);
?>
<fieldset class="input-group">
    <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-6']);
        echo $this->Form->control('layout_name', [
            'type' => 'select',
            'label' => __dx($po, 'admin', 'layout_name'),
            'extraClass' => 'span-5',
            'options' => ['base' => 'modello base', 'complesso' => 'modello waw'], 'empty' => __d('admin','choose one')], );

        echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-1']);

    ?>
</fieldset>

<div class="blocks blocks__base">

    <fieldset class="input-group">
			<div class="block text">
                <?php
                    echo $this->Form->control('date', ['label' => __d('admin', 'date')]);
                ?>
            </div>
			
			<div class="block text">
                <?php
                    echo $this->Form->control('datetime', ['label' => __d('admin', 'datetime')]);
                ?>
            </div>
			
			<div class="block text">
                <?php
                    echo $this->Form->control('time', ['label' => __d('admin', 'time')]);
                ?>
            </div>

			<?php
				echo $this->element('admin/uploader/image', [
					'scope' => 'nomobile',
					'title' => 'Immagine senza mobile',
					'width' => 500,
					'height' => 500,
				]);
            ?>
             <?php
	
				echo $this->element('admin/uploader/file', [
					'scope' => 'allegato',
					'title' => 'Allegato',
                    'icon' => 'attachment',
                    'class' => 'span-12'
				]);

            ?>
			 <?php
              
				echo $this->element('admin/uploader/gallery', [
					'scope' => 'gallery',
					'title' => 'Gallery di immagini'
				]);
                 
             ?>
			 
			 <?php
				echo $this->element('admin/uploader/file-set', [
					'scope' => 'allegati',
					'title' => 'Allegati'
				]);
               
             ?>
        </div>
    </div>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
