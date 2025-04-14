<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $image
 */
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.4/cropper.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.4/cropper.css" rel="stylesheet"/>  
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Images'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="images form content">
            <?= $this->Form->create($image, ['enctype'=>'multipart/form-data', 'id' => 'upload-form', 'class' => 'dropzone']) ?>
            <fieldset>
                <legend><?= __('Add Image') ?></legend>
                <?php                    
                    //echo $this->Form->control('title');                    
                    //echo $this->Form->control('path', ['type' => 'file']);
                ?>			 
            </fieldset>            
            <?= $this->Form->end() ?>
			<div id="image-editor">
			
				<div class="inner">
					<div class="cropper-container">
						<img id="image-cropper"/>
					</div>
					
					<div class="preview-container" style="display:none" >
						<img id="image-preview"/>
					</div>							 
					
					<div class="tab-pane" id="tab-cropper">
						<div class="row">
							<form name="toolbarForm" class="col-md-3">        
							
								<div class="btn-group" data-toggle="buttons">
					  
									<label class="btn btn-primary active">
										<input type="radio" class="sr-only" name="printSize" value="800,350" checked/>
										800/350
									</label>								
									<label class="btn btn-primary active">
										<input type="radio" class="sr-only" name="printSize" value="1600,1600" />
										1/1
									</label>
									<label class="btn btn-primary active">
										<input type="radio" class="sr-only" name="printSize" value="900,1600" />
										9/16
									</label>
									<br /><br />
								</div>                

								<div class="btn-group">            
									<button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
										<span class="fa fa-search-plus">+</span>
									</button>
									<button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
										<span class="fa fa-search-minus">-</span>
									</button>								
								</div>          
								
								<label class="btn">
									<span id="dataZoom">100</span>%								
								</label>
								<div id="zoom-slider"></div>                                                              
				  
								<div class="btn-group">            
									<br /><br />							
									<button type="button" class="btn btn-success" id="btn-upload">upload</button>
								</div>
					 
							</form>
						</div>
					</div>
				</div>	
				
			</div>
        </div>
    </div>
</div>


    <script type="text/javascript">
	
		var $imageCropper = $('#image-cropper');
		var $imagePreview = $('#image-preview');
		var $imageEditor = $('#image-editor');
		var $dataZoom = $('#dataZoom');		
		
		$imageEditor.hide();
		
		var cropperOptions = {
			aspectRatio: 16 / 9,
			autoCropArea: 1,
			viewMode : 2,
			cropBoxResizable: true,
			dragMode: 'move'
		};
		var cachedFilename;
		var croppingSrc;
		var compressedFile;
	
		Dropzone.autoDiscover = false;
		
		var myDropzone = new Dropzone(
			"#upload-form", {
				autoProcessQueue: false,
				maxFilesize: 5,
				acceptedFiles: '.jpeg,.jpg,.png,.gif,.bmp',
				disablePreviews : true,
				addedfile(file) {
					$imageEditor.hide();
					if (this.element === this.previewsContainer) {
					  this.element.classList.add("dz-started");
					}

					if (this.previewsContainer && !this.options.disablePreviews) {
					  file.previewElement = Dropzone.createElement(
						this.options.previewTemplate.trim()
					  );
					  file.previewTemplate = file.previewElement; // Backwards compatibility

					  this.previewsContainer.appendChild(file.previewElement);
					  for (var node of file.previewElement.querySelectorAll("[data-dz-name]")) {
						node.textContent = file.name;
					  }
					  for (node of file.previewElement.querySelectorAll("[data-dz-size]")) {
						node.innerHTML = this.filesize(file.size);
					  }

					  if (this.options.addRemoveLinks) {
						file._removeLink = Dropzone.createElement(
						  `<a class="dz-remove" href="javascript:undefined;" data-dz-remove>${this.options.dictRemoveFile}</a>`
						);
						file.previewElement.appendChild(file._removeLink);
					  }

					  let removeFileEvent = (e) => {
						e.preventDefault();
						e.stopPropagation();
						if (file.status === Dropzone.UPLOADING) {
						  return Dropzone.confirm(
							this.options.dictCancelUploadConfirmation,
							() => this.removeFile(file)
						  );
						} else {
						  if (this.options.dictRemoveFileConfirmation) {
							return Dropzone.confirm(
							  this.options.dictRemoveFileConfirmation,
							  () => this.removeFile(file)
							);
						  } else {
							return this.removeFile(file);
						  }
						}
					  };

					  for (let removeLink of file.previewElement.querySelectorAll(
						"[data-dz-remove]"
					  )) {
						removeLink.addEventListener("click", removeFileEvent);
					  }
					}
				  },
				thumbnail: function(file) {
					// ignore files which were already cropped and re-rendered
					// to prevent infinite loop
					if (file.cropped) {
						return;
					}
					
					$imageCropper[0].src = '';
										
					// cache filename to re-assign it to cropped file
					cachedFilename = file.name;
					// remove not cropped file from dropzone (we will replace it later)
					this.removeFile(file);

					var reader = new FileReader();
					reader.onloadend = function(event) {
						$imageCropper[0].onload = function() {
							$imageCropper.cropper('destroy').cropper(cropperOptions);
							$imageCropper[0].onload = null;							
						}

						$imageEditor.show();
						$imageCropper[0].src = event.target.result;						
					}
					// read uploaded file (triggers code above)
					reader.readAsDataURL(file);	
				}	
			}
		);		
		
		
		$('input[name="printSize"]').on('change', function() {
			var printSize = $(this).val().split(',');
			var width = printSize[0];
			var height = printSize[1];
			
			$imageCropper.cropper('setAspectRatio', width / height);
		});
		
		$('[data-method]').on('click', function () {
		  var $this = $(this);
		  var data = $this.data();
		  var result;

		  if ($imageCropper.data('cropper') && data.method) {
			
			result = $imageCropper.cropper(data.method, data.option, data.secondOption);

		  }
		});
		
		
		$('#btn-upload').on('click', function(){
			$('.toolbar-cropper, .cropper-container').hide()
			$('.toolbar-preview, .preview-container').show();
			updatePreview(true);			
		});
		
		
		function updatePreview(upload){
			//var canvasOptions = {fillColor: '#fff'};
			var canvasOptions = {};
			var printSize = $('input[name="printSize"]').val().split(',');
			var orientation = 'landscape';
			var width = orientation == 'landscape' ? Math.max.apply(null, printSize) : Math.min.apply(null, printSize);
			var height = orientation == 'landscape' ? Math.min.apply(null, printSize) : Math.max.apply(null, printSize);			
			var quality = 85 / 100;
			if (width && height){
				// caclulate pixel size according to print size and DPI
				canvasOptions.width = width;
				canvasOptions.height = height;
			}
			var canvas = $imageCropper.cropper('getCroppedCanvas', canvasOptions);                                
			// get cropped image data
			var dataUrl = canvas.toDataURL('image/png', quality);
			//var dataUrl = canvas.toDataURL('image/jpeg', quality);
			// transform it to Blob object
			compressedFile = dataURItoBlob(dataUrl);
			// set 'cropped to true' (so that we don't get to that listener again)
			compressedFile.cropped = true;
			// assign original filename
			compressedFile.name = cachedFilename;

			if($imagePreview.data('cropper')){
				$imagePreview.cropper('replace', dataUrl);
			} else {
				$imagePreview
				  .attr('src', dataUrl)
				  .cropper({
					center: false,
					autoCrop: false,
					dragMode: 'move'
				  });
				}
			if(upload) {
				// add cropped file to dropzone
				myDropzone.addFile(compressedFile);
				// upload cropped file with dropzone
				myDropzone.processQueue();				
			}	
				
		}
		$('.toolbar-preview input').change(updatePreview);
		
		
		
		function dataURItoBlob(dataURI) {
			// convert base64/URLEncoded data component to raw binary data held in a string
			var byteString;
			if (dataURI.split(',')[0].indexOf('base64') >= 0)
				byteString = atob(dataURI.split(',')[1]);
			else
				byteString = unescape(dataURI.split(',')[1]);

			// separate out the mime component
			var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

			// write the bytes of the string to a typed array
			var ia = new Uint8Array(byteString.length);
			for (var i = 0; i < byteString.length; i++) {
				ia[i] = byteString.charCodeAt(i);
			}

			return new Blob([ia], {type:mimeString});
		}
		
		
    </script>