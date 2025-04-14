<?php

$isDefaultLanguage = ACTIVE_ADMIN_LANGUAGE == DEFAULT_LANGUAGE ? true : false;

$title = $title ?? 'Carica Immagini';
$uniqueId = 'uploader_' . bin2hex(random_bytes(10));

// ci serve per identificare le varie versioni (desktop, mobile, ecc...) della stessa immagine
$same = bin2hex(random_bytes(10)) . '_' . uniqid();
?>

<div class="block uploader <?php if(!empty($class)) echo $class; ?>">
	<div class="input uploader uploader--gallery">

		<label for="<?php echo $uniqueId; ?>">
            <div class="label-heading">
				<?php echo $this->Backend->materialIcon('collections') ?>
				<?php echo $title; ?>
			</div>
        </label>

		<div class="uploader uploader--multiple">
			<div class="upload <?php echo !empty($images[$scope]) ? 'loaded' : ''; ?>" id="<?php echo $uniqueId; ?>">
				<div class="upload__drop">
					<div class="upload__area" <?php if(!empty($video)) echo "data-video"; ?> <?php if(!empty($videoMobile)) echo "data-video-mobile"; ?>>

						<div class="onefile prototype">
							<div class="upload__image-preview loading-img"><img /></div>
							<input name="_filename[]" maxlength="255" type="hidden">

							<div class="upload__controls">
								<div class="upload__controls__actions">
									<span class="material-symbols-rounded" id="edit-upload">edit</span>
									<span class="material-symbols-rounded" id="delete-upload">delete</span>
								</div>
							</div>
						</div>

					</div>
					<?php if($isDefaultLanguage): ?>
					<div class="upload__text">
						<div class="upload__text--inner-label"><?php echo __d('admin', 'uploader upload image') ?></div>
						<span class="material-symbols-rounded">file_upload</span>
						<div><?php echo __d('admin', 'uploader upload drag images') ?></div>
					</div>
					<?php endif; ?>
				</div>
				<div class="upload__content">
					<?php // mostra le immagini caricate a database	?>
					<?php if(!empty($images[$scope])): ?>
						<?php foreach($images[$scope] as $image): ?>
							<div class="onefile" data-db-id="<?php echo $image['id']; ?>">

								<div class="upload__image-preview loaded"><img src="<?php echo $image['backend_path']; ?>" /></div>

								<div class="upload__controls">
									<!--<div class="upload__controls__filename"><?php echo $image['filename']; ?></div>-->
									<div class="upload__controls__actions">
										<?php
										$editParams = [];
										if (!empty($video)) $editParams['video'] = true;
										if (!empty($videoMobile)) $editParams['videoMobile'] = true;
										if (!$isDefaultLanguage) $editParams['lang'] = ACTIVE_ADMIN_LANGUAGE;
										?>
										<span class="material-symbols-rounded" id="edit-upload" data-href="/admin/images/edit/<?php echo $image['id']; ?>.json?<?= http_build_query($editParams) ?>">edit</span>
										<?php if($isDefaultLanguage): ?>
										<span class="material-symbols-rounded" id="delete-upload" data-href="/admin/images/delete/<?php echo $image['id']; ?>.json">delete</span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>


	</div>
</div>

<?php if($isDefaultLanguage): ?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
	(function(){

		var galleryUppy = new Uppy.Core({
			locale: Uppy.locales.it_IT,
			id: '<?php echo $scope; ?>',
			restrictions: {
				maxFileSize: 8000000,
				allowedFileTypes: ['image/*'],
			}
		})
		.use(Uppy.DragDrop, { target: '#<?php echo $uniqueId; ?> .upload__area' })

		galleryUppy.on('file-added', (file) => {

			var filename = file.name.replace(new RegExp('\.' + file.extension + '$'), '');

			var imgID = mainUppy.addFile({
			  name: file.name, // file name
			  type: file.type, // file type
			  data: file.data, // file blob
			  isRemote: false,
			  source: '<?php echo $scope; ?>',
			  meta: {
				device: 'desktop',
				relativePath: 'gallery_' + '<?php echo $scope; ?>',
				model : '<?php echo $modelName; ?>',
				scope : '<?php echo $scope; ?>',
				filename : filename,
				multiple : true
			  }

			})

			galleryUppy.setFileMeta(file.id, {
				refID : imgID
			})

			var objectURL = URL.createObjectURL(file.data);

			var $newBox = $('#<?php echo $uniqueId; ?> .prototype').clone();
				$newBox.removeClass('prototype');
				$newBox.attr('data-file-id', '<?php echo $scope; ?>_' + imgID);
				$newBox.find('.upload__image-preview img')[0].src = objectURL;
				$newBox.find('.upload__controls').attr('data-file-id', '<?php echo $scope; ?>_' + imgID);
				$newBox.find('.upload__image-preview').attr('data-file-id', '<?php echo $scope; ?>_' + imgID);
				$newBox.appendTo('#<?php echo $uniqueId; ?> .upload__content');



			$('#<?php echo $uniqueId;?>').addClass('loaded');

			window.startUpload();
		})

        galleryUppy.on('error', (error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        galleryUppy.on('upload-error', (file, error, response) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        galleryUppy.on('restriction-failed', (file, error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

		function removeFilesFromQueue(scope, id){

			// elimina immagini dello scope specificato dal mainUppy
			var toBeRemoved = mainUppy.getFiles().filter(function(item){ return item.source == scope && item.id == id}).map(function(item){ return item.id });
			toBeRemoved.forEach((item, index) => mainUppy.removeFile(item));

			var toBeRemoved = galleryUppy.getFiles().filter(function(item){ return item.meta.refID == id}).map(function(item){ return item.id });
			toBeRemoved.forEach((item, index) => galleryUppy.removeFile(item));

		}

		// elimina file
		$('#<?php echo $uniqueId; ?>').on('click', '#delete-upload', function(){

			let text = "<?php echo __d('admin', 'Sei sicuro di voler eliminare questo file?'); ?>";
			if (confirm(text) == true) {
				var $trigger = $(this);
				$trigger.closest('.onefile').remove();

				$.ajax({
				  dataType: "json",
				  url: $trigger.attr('data-href'),
				  success: function(data) {
					  if($('#<?php echo $uniqueId; ?> .upload__content .onefile').length < 1) {
						$('#<?php echo $uniqueId; ?>').removeClass('loaded');
					  }
					  removeFilesFromQueue('<?php echo $scope; ?>', $trigger.attr('data-id'));
				  }
				});
			}

			return false;

		});

		var sortableGallery = $('#<?php echo $uniqueId; ?> .upload__content')[0];
		var sortable = Sortable.create(sortableGallery, {
			animation: 150,
			ghostClass: 'ghost',
			onEnd: function (evt) {

                var data = {};

				$(sortableGallery).find('.onefile').each(function(index, item){
					var order = index + 1;
					var dbID = $(item).attr('data-db-id');
					data[dbID] = order;
				})

				$.ajax({
					dataType: 'json',
					method: 'POST',
					url: '/admin/images/updatePosition',
					data: {params : { position : data }},
					headers: {
						'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
					},
  			    });
			}
		});

	})()

<?php $this->Html->scriptEnd(); ?>
<?php endif; ?>

<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
	(function(){
		// modifica dati sul file
		$('#<?php echo $uniqueId; ?>').on('#edit-upload', 'click', function(){

			var $trigger = $(this);

			$.ajax({
			  dataType: "json",
			  url: $trigger.attr('data-href'),
			  success: function(response) {
				 $('#quickEdit').html($('<form/>', { action: trigger.attr('data-href'), method: 'POST' }).append(
					$('<input>', {type: 'hidden', name: 'id', value: response.image.id}),
					$('<input>', {name: 'filename', value: response.image.filename}),
					$('<input>', {name: 'title', value: response.image.title}),
					$('<button>', {name: 'submit', 'type' : 'submit', text: 'Salva', class: 'btn btn--primary'}),
				));
			  }
			});

			return false;
		});
	})
<?php $this->Html->scriptEnd(); ?>
