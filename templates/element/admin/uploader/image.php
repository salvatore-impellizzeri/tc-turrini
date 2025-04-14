<?php

// l'immagine caricata si trova qui: $images[$scope]
// il path si trova qui: $images[$scope]->backend_path

$isDefaultLanguage = ACTIVE_ADMIN_LANGUAGE == DEFAULT_LANGUAGE ? true : false;

$width = $width ?? '';
$height = $height ?? '';

$mobileWidth = $mobile['width'] ?? '';
$mobileHeight = $mobile['height'] ?? '';

$title = $title ?? 'Carica Immagine';

$dimensioni = '<span class="material-symbols-rounded">tv</span>';
$dimensioni.= empty($width) ? 'auto' : $width;
$dimensioni.= ' x ';
$dimensioni.= empty($height) ? 'auto' : $height;
if(!empty($mobile)) {
    $dimensioni.= ' <span class="material-symbols-rounded">smartphone</span>';
    $dimensioni.= empty($mobileWidth) ? 'auto' : $mobileWidth;
    $dimensioni.= ' x ';
    $dimensioni.= empty($mobileHeight) ? 'auto' : $mobileHeight;
}
$title.= "<small class='uploader__dimensioni'>{$dimensioni}</small>";

$uniqueId = 'uploader_' . bin2hex(random_bytes(10));

// ci serve per identificare le varie versioni (desktop, mobile, ecc...) della stessa immagine
$same = bin2hex(random_bytes(10)) . '_' . uniqid();
?>

<div class="block uploader <?php if(!empty($class)) echo $class; ?>">
	<div class="input uploader uploader--single-image">

		<label for="<?php echo $uniqueId; ?>">
			<div class="label-heading">
				<?php echo $this->Backend->materialIcon('insert_photo') ?>
				<?php echo $title; ?>
			</div>

		</label>

		<div class="upload <?php echo !empty($images[$scope]['backend_path']) ? 'loaded' : ''; ?>" id="<?php echo $uniqueId; ?>">
			<div class="upload__area" <?php if(!empty($video)) echo "data-video"; ?> <?php if(!empty($videoMobile)) echo "data-video-mobile"; ?>>
				<?php if(!empty($images[$scope]['backend_path'])): ?>
				<div class="upload__area__desktop-preview loaded"><img src="<?php echo $images[$scope]['backend_path']; ?>" /></div>
				<?php else: ?>
				<div class="upload__area__desktop-preview"><img /></div>
				<?php endif; ?>

				<?php if(!empty($mobile)): ?>
					<?php if(!empty($images[$scope]['responsive_images']['mobile']['backend_path'])): ?>
					<div class="upload__area__mobile-preview loaded"><img src="<?php echo $images[$scope]['responsive_images']['mobile']['backend_path']; ?>" /></div>
					<?php else: ?>
					<div class="upload__area__mobile-preview"><img /></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php if($isDefaultLanguage): ?>
			<div class="upload__text">
				<div class="upload__text--inner-label"><?php echo __d('admin', 'uploader upload') ?></div>
				<span class="material-symbols-rounded">file_upload</span>
				<div><?php echo __d('admin', 'uploader upload drag') ?></div>
			</div>
			<?php endif; ?>
			<?php if(!empty($images[$scope]['backend_path'])): ?>
			<div class="upload__controls loaded">
			<?php else: ?>
			<div class="upload__controls">
			<?php endif; ?>
				<div class="upload__controls__filename"><?php echo $images[$scope]['filename'] ?? ''; ?></div>
				<div class="upload__controls__actions">
					<?php
					$editParams = [];
					if (!empty($video)) $editParams['video'] = true;
					if (!empty($videoMobile)) $editParams['videoMobile'] = true;
					if (!$isDefaultLanguage) $editParams['lang'] = ACTIVE_ADMIN_LANGUAGE;
					?>
					<span class="material-symbols-rounded" class="nico" id="edit-upload" data-href="/admin/images/edit/<?php echo $images[$scope]['id'] ?? ''; ?>.json?<?= http_build_query($editParams) ?>">edit</span>
					<?php if($isDefaultLanguage): ?>
					<span class="material-symbols-rounded" id="delete-upload" data-href="/admin/images/delete/<?php echo $images[$scope]['id'] ?? ''; ?>.json">delete</span>
					<?php endif; ?>
				</div>
			</div>
			<?php if($isDefaultLanguage): ?>
				<?php echo $this->element('admin/uploader/photo-editor', [
					'mobile' => $mobile ?? null,
					'video' => $video ?? null,
					'videoMobile' => $videoMobile ?? null,
					'width' => $width ?? null,
					'height' => $height ?? null,
					'mobileWidth' => $mobileWidth ? $mobileWidth : "auto",
					'mobileHeight' => $mobileHeight ? $mobileHeight :  "auto",
				]); ?>
			<?php endif; ?>
		</div>

	</div>
</div>

<?php if($isDefaultLanguage): ?>

<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
	(function(){

		var $imageCropper = $('#<?php echo $uniqueId; ?> .desktop-frame__img');

		<?php if(!empty($mobile)): ?>
		var $imageMobileCropper = $('#<?php echo $uniqueId; ?> .mobile-frame__img');
		<?php endif; ?>

		var desktopImage = mobileImage = null;

		var aspectRatio = '<?php echo (empty($width) || empty($height)) ? 'Nan' : $width / $height; ?>';

		var cropperOptions = {
			aspectRatio: aspectRatio,
			autoCropArea: 1,
			viewMode : 2,
			cropBoxResizable: true,
			dragMode: 'move',
			crop: function (event) {
				var $cropBox = $('#<?php echo $uniqueId; ?> .desktop-frame .cropper-crop-box');
				<?php if(empty($width)): ?>
					var calculated = Math.round($cropBox.width() / $cropBox.height() * <?php echo $height; ?>);
					$('#<?php echo $uniqueId; ?> div[data-device="desktop"] .attributes__sizes-width-value').text(calculated);
				<?php elseif(empty($height)): ?>
					var calculated = Math.round($cropBox.height() / $cropBox.width() * <?php echo $width; ?>);
					$('#<?php echo $uniqueId; ?> div[data-device="desktop"] .attributes__sizes-height-value').text(calculated);
				<?php endif; ?>
			}
		};

		<?php if(!empty($mobile)): ?>
		var aspectRatioMobile = '<?php echo (empty($mobileWidth) || empty($mobileHeight)) ? 'Nan' : $mobileWidth / $mobileHeight; ?>';

		var cropperMobileOptions = {
			aspectRatio: aspectRatioMobile,
			autoCropArea: 1,
			viewMode : 2,
			cropBoxResizable: true,
			dragMode: 'move',
			crop: function (event) {
				var $cropBox = $('#<?php echo $uniqueId; ?> .mobile-frame .cropper-crop-box');
				<?php if(empty($mobileWidth)): ?>
					var calculated = Math.round($cropBox.width() / $cropBox.height() * <?php echo $mobileHeight; ?>);
					$('#<?php echo $uniqueId; ?> div[data-device="mobile"] .attributes__sizes-width-value').text(calculated);
				<?php elseif(empty($mobileHeight)): ?>
					var calculated = Math.round($cropBox.height() / $cropBox.width() * <?php echo $mobileWidth; ?>);
					$('#<?php echo $uniqueId; ?> div[data-device="mobile"] .attributes__sizes-height-value').text(calculated);
				<?php endif; ?>
			}
		};
		<?php endif; ?>

		var desktopUppy = new Uppy.Core({
			locale: Uppy.locales.it_IT,
			id: '<?php echo $scope; ?>_desktop',
			restrictions: {
				maxFileSize: 8000000,
				maxNumberOfFiles : 1,
				allowedFileTypes: ['image/*'],
			},
			onBeforeFileAdded: (currentFile, files) => {
				// rimuove i file precedenti dagli spazi di caricamento globale
				removeFilesFromQueue('<?php echo $scope; ?>');
  		    }
		})
		.use(Uppy.DragDrop, { target: '#<?php echo $uniqueId; ?> .upload__area' })

		desktopUppy.on('file-added', (file) => {

			$('#<?php echo $uniqueId; ?> .photo-editor').attr('data-toggle', 'open');

			desktopImage = file;
			$imageCropper[0].src = '';
			<?php if(!empty($mobile)): ?>
			mobileImage = file;
			$imageMobileCropper[0].src = '';
			<?php endif; ?>

			var filename = file.name.replace(new RegExp('\.' + file.extension + '$'), '');
			$('#<?php echo $uniqueId; ?> .attributes__list input[name="_filename"]').val(filename);
			$('#<?php echo $uniqueId; ?> .upload__controls__filename').text(filename);

			var objectURL = URL.createObjectURL(file.data);
			$imageCropper[0].src = objectURL;
			$imageCropper.cropper('destroy').cropper(cropperOptions);
			<?php if(!empty($mobile)): ?>
			$imageMobileCropper[0].src = objectURL;
			$imageMobileCropper.cropper('destroy').cropper(cropperMobileOptions);
			<?php endif; ?>
		})

        desktopUppy.on('error', (error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        desktopUppy.on('upload-error', (file, error, response) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        desktopUppy.on('restriction-failed', (file, error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

		<?php if(!empty($mobile)): ?>
		var mobileUppy = new Uppy.Core({
			debug: false,
			locale: Uppy.locales.it_IT,
			id: '<?php echo $scope; ?>_mobile',
			restrictions: {
				maxFileSize: 8000000,
				maxNumberOfFiles : 1,
				allowedFileTypes: ['image/*'],
			}
		})
		.use(Uppy.DragDrop, { target: '#<?php echo $uniqueId; ?> .alt-image__upload-area' })

		mobileUppy.on('file-added', (file) => {
			mobileImage = file;
			$imageMobileCropper[0].src = '';
			var objectURL = URL.createObjectURL(file.data);
			$imageMobileCropper[0].src = objectURL;
			$imageMobileCropper.cropper('destroy').cropper(cropperMobileOptions);

		})

        mobileUppy.on('error', (error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        mobileUppy.on('upload-error', (file, error, response) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });

        mobileUppy.on('restriction-failed', (file, error) => {
            Swal.fire({
                icon: 'error',
                text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
                confirmButtonColor: '#D81159',
            });
        });
		<?php endif; ?>
		// Annulla
		$("#<?php echo $uniqueId; ?> .undo-photo-editor, #<?php echo $uniqueId; ?> #close-editor").on('click', function(){
			removeFilesFromQueue('<?php echo $scope; ?>');
			desktopUppy.reset();
			<?php if(!empty($mobile)): ?>
			mobileUppy.reset();
			<?php endif; ?>
			$('#<?php echo $uniqueId; ?> .photo-editor').attr('data-toggle', 'closed');
		});

		// elimina file
		$('#<?php echo $uniqueId; ?> #delete-upload').on('click', function(){

			let text = "<?php echo __d('admin', 'Sei sicuro di voler eliminare questo file?'); ?>";
			if (confirm(text) == true) {
				var $trigger = $(this);

				$.ajax({
				  dataType: "json",
				  url: $trigger.attr('data-href'),
				  success: function(data) {
					  $('#<?php echo $uniqueId; ?>').removeClass('loaded');
					  desktopUppy.reset();
					  <?php if(!empty($mobile)): ?>
					  mobileUppy.reset();
					  <?php endif; ?>
					  removeFilesFromQueue('<?php echo $scope; ?>');
				  }
				});
			}

			return false;
		});

		$('#<?php echo $uniqueId; ?> .save-photo-editor').on('click', function(){
			desktopUppy.reset();
			<?php if(!empty($mobile)): ?>
			mobileUppy.reset();
			<?php endif; ?>
			updatePreview();
		});

		function removeFilesFromQueue(scope){

			// desktop: elimina immagini dello scope specificato dal mainUppy
			var toBeRemoved = mainUppy.getFiles().filter(function(item){ return item.source == scope}).map(function(item){ return item.id });
			toBeRemoved.forEach((item, index) => mainUppy.removeFile(item));

			<?php if(!empty($mobile)): ?>
			// mobile: elimina immagini dello scope specificato dal responsiveUppy
			var toBeRemoved = responsiveUppy.getFiles().filter(function(item){ return item.source == scope}).map(function(item){ return item.id });
			toBeRemoved.forEach((item, index) => responsiveUppy.removeFile(item));
			<?php endif; ?>

		}


		async function updatePreview(){

			var width = $('#<?php echo $uniqueId; ?> div[data-device="desktop"] .attributes__sizes-width-value').text();
			var height = $('#<?php echo $uniqueId; ?> div[data-device="desktop"] .attributes__sizes-height-value').text();

			var quality = 100 / 100; // ??? da capire se comprimere in più punti o solo beforeUpload

			var canvasOptions = {
				width : width,
				height: height,
				imageSmoothingEnabled : false,
				//fillColor: '#fff'
			};

			var cropData = JSON.stringify($imageCropper.cropper('getData', canvasOptions));
			var canvas = $imageCropper.cropper('getCroppedCanvas', canvasOptions);

			var imgBlob = await new Promise(resolve => canvas.toBlob(resolve, desktopImage.type, quality));
			var imgFile = new File([imgBlob], desktopImage.name, {type: desktopImage.type});

			<?php if(!empty($mobile)): ?>
			var widthMobile = $('#<?php echo $uniqueId; ?> div[data-device="mobile"] .attributes__sizes-width-value').text();
			var heightMobile = $('#<?php echo $uniqueId; ?> div[data-device="mobile"] .attributes__sizes-height-value').text();

			var canvasMobileOptions = {
				width : widthMobile,
				height: heightMobile,
				imageSmoothingEnabled : false,
				//fillColor: '#fff'
			};

			var canvasMobile = $imageMobileCropper.cropper('getCroppedCanvas', canvasMobileOptions);
			var cropMobileData = JSON.stringify($imageMobileCropper.cropper('getData', canvasMobileOptions));

			var imgMobileBlob = await new Promise(resolveMob => canvasMobile.toBlob(resolveMob, mobileImage.type, quality));
			var imgMobileFile = new File([imgMobileBlob], mobileImage.name, {type: mobileImage.type});
			<?php endif; ?>




			var desktopID = mainUppy.addFile({
			  name: desktopImage.name, // file name
			  type: desktopImage.type, // file type
			  data: desktopImage.data, // file blob
			  isRemote: false,
			  source: '<?php echo $scope; ?>',
			  meta: {
				device: 'desktop',
				relativePath: 'desktop_' + '<?php echo $scope; ?>',
				cropData: cropData,
				finalWidth: width,
				finalHeight: height,
				model : '<?php echo $modelName; ?>',
				scope : '<?php echo $scope; ?>',
				same : '<?php echo $same; ?>',
				filename : $('#<?php echo $uniqueId; ?> input[name="_filename"]').val(),
				caption : $('#<?php echo $uniqueId; ?> input[name="_caption"]').val(),
				title: $('#<?php echo $uniqueId; ?> input[name="_title"]').val(),
				video: $('#<?php echo $uniqueId; ?> input[name="_video"]').val(),
				video_mobile: $('#<?php echo $uniqueId; ?> input[name="_video_mobile"]').val(),
			  }
			})

			<?php if(!empty($mobile)): ?>
			var mobileID = responsiveUppy.addFile({
			  name: mobileImage.name, // file name
			  type: mobileImage.type, // file type
			  data: mobileImage.data, // file blob
			  isRemote: false,
			  source: '<?php echo $scope; ?>',
			  meta: {
				device: 'mobile',
				relativePath: 'mobile_' + '<?php echo $scope; ?>',
				cropData: cropMobileData,
				finalWidth: widthMobile,
				finalHeight: heightMobile,
				same : '<?php echo $same; ?>',
				filename : $('#<?php echo $uniqueId; ?> input[name="_filename"]').val(),
			  }
			})
			<?php endif; ?>

			$('#<?php echo $uniqueId; ?> .upload__controls__filename').text($('#<?php echo $uniqueId; ?> .attributes__list input[name="_filename"]').val());

			$('#<?php echo $uniqueId; ?> .upload__controls').attr('data-file-id', '<?php echo $scope; ?>_' + desktopID),
			$('#<?php echo $uniqueId; ?> .upload__area__desktop-preview').attr('data-file-id', '<?php echo $scope; ?>_' + desktopID),
			$('#<?php echo $uniqueId; ?> .upload__area__desktop-preview img')[0].src = canvas.toDataURL( desktopImage.type );

			<?php if(!empty($mobile)): ?>
			$('#<?php echo $uniqueId; ?> .upload__area__mobile-preview').attr('data-file-id', '<?php echo $scope; ?>_' + mobileID),
			$('#<?php echo $uniqueId; ?> .upload__area__mobile-preview img')[0].src = canvasMobile.toDataURL( mobileImage.type );
			<?php endif; ?>

			$('#<?php echo $uniqueId; ?>').addClass('loaded');

			$('#<?php echo $uniqueId; ?> .photo-editor').attr('data-toggle', 'closed');

			window.startUpload();

		}
	})()


<?php $this->Html->scriptEnd(); ?>

<?php endif; ?>
