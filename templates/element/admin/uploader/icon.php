<?php

	$isDefaultLanguage = ACTIVE_ADMIN_LANGUAGE == DEFAULT_LANGUAGE ? true : false;

	$title = $title ?? 'Carica Icona';

	$uniqueId = 'uploader_' . bin2hex(random_bytes(10));

	// ci serve per identificare le varie versioni (desktop, mobile, ecc...) della stessa immagine
	$same = bin2hex(random_bytes(10)) . '_' . uniqid();
?>

<div class="block uploader <?php if(!empty($class)) echo $class; ?>">
	<div class="input uploader uploader--icon">

		<label for="<?php echo $uniqueId; ?>">
            <div class="label-heading">
				<?php echo $this->Backend->materialIcon('insert_photo') ?>
				<?php echo $title; ?>
			</div>
        </label>

		<div class="uploader__multiple">

			<div class="upload <?php echo !empty($images[$scope]['backend_path']) ? 'loaded' : ''; ?>" id="<?php echo $uniqueId; ?>">
				<div class="upload__area">
					<?php if(!empty($images[$scope]['backend_path'])): ?>
					<div class="upload__icon-preview loaded"><img src="<?php echo $images[$scope]['backend_path']; ?>" /></div>
					<?php else: ?>
					<div class="upload__icon-preview"><img /></div>
					<?php endif; ?>
					<input name="_filename" maxlength="255" type="hidden" value="<?php echo $images[$scope]['filename'] ?? ''; ?>" >
				</div>
				<?php if(!empty($images[$scope]['backend_path'])): ?>
				<div class="upload__controls loaded">
				<?php else: ?>
				<div class="upload__controls">
				<?php endif; ?>
					<div class="upload__controls__filename"><?php echo $images[$scope]['filename'] ?? ''; ?></div>
					<div class="upload__controls__actions">
						<span class="material-symbols-rounded" id="edit-upload">edit</span>
						<?php if($isDefaultLanguage): ?>
						<span class="material-symbols-rounded" id="delete-upload" data-href="/admin/images/delete/<?php echo $images[$scope]['id'] ?? ''; ?>.json">delete</span>
						<?php endif; ?>
					</div>
				</div>
				<?php if($isDefaultLanguage): ?>
				<div class="upload__text">
					<div class="upload__text--inner-label"><?php echo __d('admin', 'uploader upload icon') ?></div>
					<span class="material-symbols-rounded">file_upload</span>
					<div><?php echo __d('admin', 'uploader upload drag') ?></div>
				</div>
				<?php endif; ?>
			</div>
		</div>


	</div>
</div>

<?php if($isDefaultLanguage): ?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
	(function(){

		var icon;

		var iconUppy = new Uppy.Core({
			locale: Uppy.locales.it_IT,
			id: '<?php echo $scope; ?>',
			restrictions: {
				maxFileSize: 8000000,
				maxNumberOfFiles : 1,
				allowedFileTypes: ['.svg'],
			},
			onBeforeFileAdded: (currentFile, files) => {
				// rimuove i file precedenti dagli spazi di caricamento globale
				removeFilesFromQueue('<?php echo $scope; ?>');
  		    }
		})
		.use(Uppy.DragDrop, { target: '#<?php echo $uniqueId; ?> .upload__area' })

		iconUppy.on('file-added', (file) => {

			icon = file;
			var filename = file.name.replace(new RegExp('\.' + file.extension + '$'), '');
			$('#<?php echo $uniqueId; ?> .upload__controls__filename').text(filename);

			var iconID = mainUppy.addFile({
			  name: icon.name, // file name
			  type: icon.type, // file type
			  data: icon.data, // file blob
			  isRemote: false,
			  source: '<?php echo $scope; ?>',
			  meta: {
				device: 'desktop',
				relativePath: 'icon_' + '<?php echo $scope; ?>',
				model : '<?php echo $modelName; ?>',
				scope : '<?php echo $scope; ?>',
				same : '<?php echo $same; ?>',
				//filename : $('#<?php echo $uniqueId; ?> input[name="_filename"]').val(),
				filename : filename
			  }

			})

			var objectURL = URL.createObjectURL(icon.data);
			$('#<?php echo $uniqueId; ?> .upload__icon-preview img')[0].src = objectURL;
			$('#<?php echo $uniqueId; ?> .upload__controls').attr('data-file-id', '<?php echo $scope; ?>_' + iconID),
			$('#<?php echo $uniqueId; ?> .upload__area__icon-preview').attr('data-file-id', '<?php echo $scope; ?>_' + iconID),


			$('#<?php echo $uniqueId;?>').addClass('loaded');

			window.startUpload();
		})

        iconUppy.on('error', (error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        iconUppy.on('upload-error', (file, error, response) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        iconUppy.on('restriction-failed', (file, error) => {
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
				var $trigger = $(this);

				$.ajax({
				  dataType: "json",
				  url: $trigger.attr('data-href'),
				  success: function(data) {
					  $('#<?php echo $uniqueId; ?>').removeClass('loaded');
					  iconUppy.reset();
					  removeFilesFromQueue('<?php echo $scope; ?>');
				  }
				});
			}

			return false;

		});

	})()

<?php $this->Html->scriptEnd(); ?>
<?php endif; ?>
