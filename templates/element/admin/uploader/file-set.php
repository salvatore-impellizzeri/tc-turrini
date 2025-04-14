<?php
if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE) return; //gli allegati non sono traducibili, se servono aggiungere un altro uploader
$title = $title ?? 'Carica Allegati';
$uniqueId = 'uploader_' . bin2hex(random_bytes(10));

// ci serve per identificare le varie versioni (desktop, mobile, ecc...) della stessa immagine
$same = bin2hex(random_bytes(10)) . '_' . uniqid();
?>

<div class="block uploader <?php if(!empty($class)) echo $class; ?>">
	<div class="input uploader uploader--files">

		<label for="<?php echo $uniqueId; ?>">
            <div class="label-heading">
				<?php echo $this->Backend->materialIcon('content_copy') ?>
				<?php echo $title; ?>
			</div>
        </label>

		<div class="uploader--multiple">
			<div class="upload <?php echo !empty($attachments[$scope]) ? 'loaded' : ''; ?>" id="<?php echo $uniqueId; ?>">
				<div class="upload__drop">
					<div class="upload__area">

						<div class="onefile onefile--attachment prototype">

							<div class="upload__file-preview">
								<div class="upload__ext"></div>
								<span class="material-symbols-rounded file-placeholder">file_present</span>
								<div class="upload__controls__filename"></div>
								<div class="upload__controls">
								<div class="upload__controls__actions">
									<span class="material-symbols-rounded" id="view-upload"><a href="" target="blank">preview</a></span>
									<span class="material-symbols-rounded" id="edit-upload">edit</span>
									<span class="material-symbols-rounded" id="delete-upload">delete</span>
								</div>
							</div>
							</div>

						</div>

					</div>
					<div class="upload__text">
						<div class="upload__text--inner-label"><?php echo __d('admin', 'uploader upload files'); ?></div>
						<span class="material-symbols-rounded">file_upload</span>
						<div><?php echo __d('admin', 'uploader upload drag files'); ?></div>
					</div>
				</div>
				<div class="upload__content">
					<?php // mostra le immagini caricate a database	?>
					<?php if(!empty($attachments[$scope])): ?>
						<?php foreach($attachments[$scope] as $attachment): ?>
							<div class="onefile onefile--attachment" data-db-id="<?php echo $attachment['id']; ?>">

								<div class="upload__file-preview loaded">
									<div class="upload__ext">
										<?php if(!empty($attachment['path'])) echo $attachment['ext']; ?>
									</div>
									<span class="material-symbols-rounded file-placeholder">file_present</span>
									<div class="upload__controls__filename"><?php echo $attachment['filename']; ?></div>
									<div class="upload__controls">
									<div class="upload__controls__actions">
										<span class="material-symbols-rounded" id="view-upload"><a href="<?php echo $attachment['path']; ?>" target="blank">preview</a></span>
										<span class="material-symbols-rounded" id="edit-upload" data-href="/admin/attachments/edit/<?php echo $attachment['id']; ?>.json">edit</span>
										<span class="material-symbols-rounded" id="delete-upload" data-href="/admin/attachments/delete/<?php echo $attachment['id']; ?>.json">delete</span>
									</div>
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

<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
	(function(){

		var filesetUppy = new Uppy.Core({
			locale: Uppy.locales.it_IT,
			id: '<?php echo $scope; ?>',
			restrictions: {
				maxFileSize: 8000000,
				// allowedFileTypes: ['image/*'],
			}
		})
		.use(Uppy.DragDrop, { target: '#<?php echo $uniqueId; ?> .upload__area' })

		filesetUppy.on('file-added', (file) => {

			var filename = file.name.replace(new RegExp('\.' + file.extension + '$'), '');

			var attID = fileUppy.addFile({
			  name: file.name, // file name
			  type: file.type, // file type
			  data: file.data, // file blob
			  isRemote: false,
			  source: '<?php echo $scope; ?>',
			  meta: {
				relativePath: 'fileset_' + '<?php echo $scope; ?>',
				model : '<?php echo $modelName; ?>',
				scope : '<?php echo $scope; ?>',
				filename : filename,
				multiple : true
			  }
			})

			filesetUppy.setFileMeta(file.id, {
				refID : attID
			})

			var $newBox = $('#<?php echo $uniqueId; ?> .prototype').clone();
				$newBox.removeClass('prototype');
				$newBox.attr('data-file-id', '<?php echo $scope; ?>_' + attID);
				$newBox.find('.upload__ext').text(file.extension);
				$newBox.find('.upload__controls__filename').text(filename);
				$newBox.find('.upload__controls').attr('data-file-id', '<?php echo $scope; ?>_' + attID);
				$newBox.find('.upload__file-preview').attr('data-file-id', '<?php echo $scope; ?>_' + attID);
				$newBox.appendTo('#<?php echo $uniqueId; ?> .upload__content');


			$('#<?php echo $uniqueId;?>').addClass('loaded');

			window.startUpload();
		})

        filesetUppy.on('error', (error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        filesetUppy.on('upload-error', (file, error, response) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        filesetUppy.on('restriction-failed', (file, error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

		function removeFilesFromQueue(scope, id){

			// elimina immagini dello scope specificato dal fileUppy
			var toBeRemoved = fileUppy.getFiles().filter(function(item){ return item.source == scope && item.id == id}).map(function(item){ return item.id });
			toBeRemoved.forEach((item, index) => fileUppy.removeFile(item));

			var toBeRemoved = filesetUppy.getFiles().filter(function(item){ return item.meta.refID == id}).map(function(item){ return item.id });
			toBeRemoved.forEach((item, index) => filesetUppy.removeFile(item));

		}



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

		var sortableFiles = $('#<?php echo $uniqueId; ?> .upload__content')[0];
		var sortable = Sortable.create(sortableFiles, {
			animation: 150,
			ghostClass: 'ghost',
			onEnd: function (evt) {

                var data = {};

				$(sortableFiles).find('.onefile').each(function(index, item){
					var order = index + 1;
					var dbID = $(item).attr('data-db-id');
					data[dbID] = order;
				})

				$.ajax({
					dataType: 'json',
					method: 'POST',
					url: '/admin/attachments/updatePosition',
					data: {params : { position : data }},
					headers: {
						'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
					},
  			    });
			}
		});

	})()

<?php $this->Html->scriptEnd(); ?>
