<?php

	$isDefaultLanguage = ACTIVE_ADMIN_LANGUAGE == DEFAULT_LANGUAGE ? true : false;

	$title = $title ?? 'Carica set di icone';
	$count = $count ?? 1;

	if($count < 1) $count = 1;
	$uniqueIds = [];

?>

<div class="block uploader <?php if(!empty($class)) echo $class; ?>">
	<div class="input uploader uploader--icon">

		<label>
            <div class="label-heading">
				<?php echo $this->Backend->materialIcon('collections') ?>
				<?php echo $title; ?>
			</div>
        </label>

		<div class="uploader--multiple">
			<?php for($i = 0; $i < $count; $i++): ?>
			<?php
				$uniqueId = 'uploader_' . bin2hex(random_bytes(10));

				// ci serve per identificare le varie versioni (desktop, mobile, ecc...) della stessa immagine
				$same = bin2hex(random_bytes(10)) . '_' . uniqid();
				$uniqueIds[$uniqueId] = $same;
			?>
			<div class="upload <?php echo !empty($images[$scope][$i]['backend_path']) ? 'loaded' : ''; ?>" id="<?php echo $uniqueId; ?>">
				<div class="upload__area">
					<?php if(!empty($images[$scope][$i]['backend_path'])): ?>
					<div class="upload__icon-preview loaded"><img src="<?php echo $images[$scope][$i]['backend_path']; ?>" /></div>
					<?php else: ?>
					<div class="upload__icon-preview"><img /></div>
					<?php endif; ?>
					<input name="_filename" maxlength="255" type="hidden" value="<?php echo $images[$scope][$i]['filename'] ?? ''; ?>" >
				</div>
				<?php if(!empty($images[$scope][$i]['backend_path'])): ?>
				<div class="upload__controls loaded">
				<?php else: ?>
				<div class="upload__controls">
				<?php endif; ?>
					<div class="upload__controls__filename"><?php echo $images[$scope][$i]['filename'] ?? ''; ?></div>
					<div class="upload__controls__actions">
						<span class="material-symbols-rounded" id="edit-upload">edit</span>
						<span class="material-symbols-rounded" id="delete-upload" data-href="/admin/images/delete/<?php echo $images[$scope][$i]['id'] ?? ''; ?>.json">delete</span>
					</div>
				</div>
				<div class="upload__text">
					<div class="upload__text--inner-label"><?php echo __d('admin', 'uploader upload icons') ?></div>
					<span class="material-symbols-rounded">file_upload</span>
					<div><?php echo __d('admin', 'uploader upload drag') ?></div>
				</div>
			</div>
			<?php endfor; ?>

		</div>


	</div>
</div>

<?php if($isDefaultLanguage): ?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
	(function(){

		<?php foreach($uniqueIds as $uniqueId => $same): ?>
		var icon_<?php echo $uniqueId; ?>;

		var iconUppy_<?php echo $uniqueId; ?> = new Uppy.Core({
			locale: Uppy.locales.it_IT,
			id: '<?php echo $scope; ?>_<?php echo $uniqueId; ?>',
			restrictions: {
				maxFileSize: 8000000,
				allowedFileTypes: ['.svg'],
			},
			onBeforeFileAdded: (currentFile, files) => {
				// rimuove i file precedenti dagli spazi di caricamento globale
				removeFilesFromQueue('<?php echo $scope. '_' . $uniqueId; ?>');
  		    }
		})
		.use(Uppy.DragDrop, { target: '#<?php echo $uniqueId; ?> .upload__area' })

		iconUppy_<?php echo $uniqueId; ?>.on('file-added', (file) => {

			icon_<?php echo $uniqueId; ?> = file;
			var filename = file.name.replace(new RegExp('\.' + file.extension + '$'), '');
			$('#<?php echo $uniqueId; ?> .upload__controls__filename').text(filename);

			var iconID = mainUppy.addFile({
			  name: icon_<?php echo $uniqueId; ?>.name, // file name
			  type: icon_<?php echo $uniqueId; ?>.type, // file type
			  data: icon_<?php echo $uniqueId; ?>.data, // file blob
			  isRemote: false,
			  source: '<?php echo $scope . '_' . $uniqueId; ?>',
			  meta: {
				device: 'desktop',
				relativePath: 'icon_' + '<?php echo $scope. '_' . $uniqueId; ?>',
				model : '<?php echo $modelName; ?>',
				scope : '<?php echo $scope; ?>',
				same : '<?php echo $same; ?>',
				//filename : $('#<?php echo $uniqueId; ?> input[name="_filename"]').val(),
				filename : filename,
				multiple: true,
			  }

			})

			var objectURL = URL.createObjectURL(icon_<?php echo $uniqueId; ?>.data);
			$('#<?php echo $uniqueId; ?> .upload__icon-preview img')[0].src = objectURL;
			$('#<?php echo $uniqueId; ?> .upload__controls').attr('data-file-id', '<?php echo $scope. '_' . $uniqueId; ?>_' + iconID),
			$('#<?php echo $uniqueId; ?> .upload__area__icon-preview').attr('data-file-id', '<?php echo $scope. '_' . $uniqueId; ?>_' + iconID),


			$('#<?php echo $uniqueId;?>').addClass('loaded');

			window.startUpload();
		})

        iconUppy_<?php echo $uniqueId; ?>.on('error', (error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        iconUppy_<?php echo $uniqueId; ?>.on('upload-error', (file, error, response) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        iconUppy_<?php echo $uniqueId; ?>.on('restriction-failed', (file, error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

		function removeFilesFromQueue(scope){

			// elimina immagini dello scope specificato dal mainUppy
			var toBeRemoved = mainUppy.getFiles().filter(function(item){ return item.source == scope}).map(function(item){ return item.id });
			toBeRemoved.forEach((item, index) => mainUppy.removeFile(item));

		}


		$('#<?php echo $uniqueId; ?> #delete-upload').on('click', function(){

			let text = "<?php echo __d('admin', 'Sei sicuro di voler eliminare questo file?'); ?>";
			if (confirm(text) == true) {
				var trigger = $(this);

				$.ajax({
				  dataType: "json",
				  url: trigger.attr('data-href'),
				  success: function(data) {
					  $('#<?php echo $uniqueId; ?>').removeClass('loaded');
					  iconUppy_<?php echo $uniqueId; ?>.reset();
					  removeFilesFromQueue('<?php echo $scope; ?>');
				  }
				});
			}

			return false;

		});

		<?php endforeach; ?>

	})()

<?php $this->Html->scriptEnd(); ?>
<?php endif; ?>
