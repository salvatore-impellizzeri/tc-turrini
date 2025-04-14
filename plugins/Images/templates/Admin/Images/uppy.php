<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $image
 */
?>

<!-- uppy.io -->
<link href="/admin-assets/js/uppy/uppy.min.css" rel="stylesheet">
<script src="/admin-assets/js/uppy/uppy.min.js"></script>
<script src="/admin-assets/js/uppy/it_IT.min.js"></script>

<!-- cropper.js -->
<script src="/admin-assets/js/jquery.js"></script>
<script src="/admin-assets/js/cropper/cropper.js"></script>
<link href="/admin-assets/js/cropper/cropper.css" rel="stylesheet"/>


  <style>
	*, *::after, *::before {
		box-sizing: border-box;
	}
	#pop {
		position: fixed;
		background: rgba(0,0,0,.75);
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		pointer-events: none;
		transition: opacity .5s;
		opacity: 0;
		z-index: 9999;
	}

	#pop.visible {
		opacity: 1;
		pointer-events: all;
	}

	#pop .inner {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		max-width: 800px;
		max-height: 80vh;
	}

    .cntn {
      margin: 20px auto;
      max-width: 640px;
	  max-height: 70vh;
    }

    .cntn img {
      max-width: 100%;
	  max-height: 100%;
	  display: block;
	  object-fit: contain;
    }

	.cntn button {
		position: absolute;
		bottom: -40px;
		margin: 0;
	}

	#switch {
		position: absolute;
		top: -20px;
	}

	#switch label {
		display: inline;
	}

	#drag-drop-area-mobile {
		position: absolute;
	}

	.image-holder {
		position: relative;
		height: 100%;
		width: 100%;
	}

	.layer {
		position: absolute;
		height: 100%;
		width: 100%;
		transition: opacity .5s;
	}
	.hidden {
		pointer-events: none;
		opacity: 0;
	}

  </style>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Images'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="images form content">

			<div id="drag-drop-area"></div>

			<div id="pop">
				<div class="inner">
					<div class="cntn">
						<div id="switch">
							<form autocomplete="off">
								<input type="radio" id="desktop" name="switch" value="desktop" checked>
								<label for="desktop">desktop</label>
								<input type="radio" id="mobile" name="switch" value="mobile">
								<label for="mobile">mobile</label>
							</form>
						</div>
						<div class="image-holder">
							<div class="layer desktop">
								<img id="image-cropper"/>
							</div>
							<div class="layer mobile hidden">
								<img id="image-mobile-cropper"/>
							</div>
							<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" width="400" height="400">
						</div>
						<div id="drag-drop-area-mobile"></div>
						<button id="cancel">Annulla</button>
						<button id="save" style="background: green; left: 130px; border-color: green;">Salva</button>
					</div>
				</div>
			</div>

        </div>
    </div>
</div>

 <?php

 // params
 // desktop: width, height
 // moboile: width, height


	$ratio = $_GET['ratio'];
	list($width, $height) = explode('/', $ratio);
 ?>
 <script>
	var $imageCropper = $('#image-cropper');
	var $imageMobileCropper = $('#image-mobile-cropper');
	var desktopImage = mobileImage = null;
	var cropperOptions = {
		aspectRatio: <?php echo $ratio; ?>,
		autoCropArea: 1,
		viewMode : 2,
		cropBoxResizable: true,
		dragMode: 'move',
	};

	var cropperMobileOptions = {
		aspectRatio: 300/600,
		autoCropArea: 1,
		viewMode : 2,
		cropBoxResizable: true,
		dragMode: 'move',
	};

	var uppy = new Uppy.Core({
		debug: false,
		id: 'main',
		locale: Uppy.locales.it_IT,
		restrictions: {
			maxFileSize: null,
			allowedFileTypes: ['image/*'],
		  }
	})
	.use(Uppy.Dashboard, {
	  proudlyDisplayPoweredByUppy : false,
	  inline: true,
	  target: '#drag-drop-area'
	})
	.use(Uppy.Compressor, {quality: 0.9, limit: 10})
	.use(Uppy.XHRUpload, {
			endpoint: '/admin/images/uppy',
			headers: {
				'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
			}
		})

	uppy.on('file-added', (file) => {

		$('#pop').addClass('visible');
		desktopImage = file;
		mobileImage = file;
		$imageCropper[0].src = '';
		$imageMobileCropper[0].src = '';

		var objectURL = URL.createObjectURL(file.data);
		$imageCropper[0].src = objectURL;
		$imageMobileCropper[0].src = objectURL;
		$imageCropper.cropper('destroy').cropper(cropperOptions);
		$imageMobileCropper.cropper('destroy').cropper(cropperMobileOptions);

	})


	var mobileUppy = new Uppy.Core({
		debug: false,
		locale: Uppy.locales.it_IT,
		id: 'mobile',
		restrictions: {
			maxFileSize: null,
			allowedFileTypes: ['image/*'],
		},
		onBeforeFileAdded: (currentFile, files) => {
			files = null;
  	    }
	})
	.use(Uppy.DragDrop, { target: '#drag-drop-area-mobile' })
	.use(Uppy.Compressor, {quality: 0.9, limit: 10})
	.use(Uppy.XHRUpload, {
			endpoint: '/admin/images/uppy',
			headers: {
				'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
			}
		})

	mobileUppy.on('file-added', (file) => {
		mobileImage = file;
		$imageMobileCropper[0].src = '';
		var objectURL = URL.createObjectURL(file.data);
		$imageMobileCropper[0].src = objectURL;
		$imageMobileCropper.cropper('destroy').cropper(cropperMobileOptions);

	})


	$('#cancel').on('click', function(){
		uppy.reset();
		$('#pop').removeClass('visible');
	});

	$('#save').on('click', function(){
		updatePreview(true);
	});

	$('input[name="switch"]').on('change', function(){
		$('.layer').toggleClass('hidden');
	})


	async function updatePreview(){

		var width = <?php echo $width; ?>;
		var height = <?php echo $height; ?>;
		var finalSize = [width, height];
		var quality = 100 / 100; // ??? da capire se comprimere in più punti o solo beforeUpload

		var widthMobile = 300;
		var heightMobile = 600;

		var canvasOptions = {
			width : width,
			height: height,
			imageSmoothingEnabled : false,
			//fillColor: '#fff'
		};


		var canvasMobileOptions = {
			width : widthMobile,
			height: heightMobile,
			imageSmoothingEnabled : false,
			//fillColor: '#fff'
		};


		var cropData = JSON.stringify($imageCropper.cropper('getData', canvasOptions));
		var canvas = $imageCropper.cropper('getCroppedCanvas', canvasOptions);
		var canvasMobile = $imageMobileCropper.cropper('getCroppedCanvas', canvasMobileOptions);
		var cropMobileData = JSON.stringify($imageMobileCropper.cropper('getData', canvasMobileOptions));

		var imgBlob = await new Promise(resolve => canvas.toBlob(resolve, desktopImage.type, quality));
		var imgFile = new File([imgBlob], desktopImage.name, {type: desktopImage.type});

		var imgMobileBlob = await new Promise(resolveMob => canvasMobile.toBlob(resolveMob, mobileImage.type, quality));
		var imgMobileFile = new File([imgMobileBlob], mobileImage.name, {type: mobileImage.type});

		uppy.setFileMeta(desktopImage.id, {
			cropData: cropData,
			finalWidth: width,
			finalHeight: height
		})

		var mobileID = uppy.addFile({
		  name: mobileImage.name, // file name
		  type: mobileImage.type, // file type
		  data: mobileImage.data, // file blob
		  meta: {
			relativePath: 'mobile',
			cropData: cropMobileData,
			finalWidth: widthMobile,
			finalHeight: heightMobile
		  }
		})

		$('#pop').removeClass('visible');

	}

</script>
