<script>
<?php
//use Cake\View\Helper;
$this->Html->scriptStart(['block' => 'scriptBottom']);
?>

var $quickEditReference;

var mainImages = [];
var responsiveImages = [];
var attachments = []

var uploadArray = [];

if($('#superForm').length > 0 ) {
	var fileUppy = new Uppy.Core({
		locale: Uppy.locales.it_IT,
		restrictions: {
			maxFileSize: 8000000,
			//allowedFileTypes: ['image/*'],
		}
	})
	.use(Uppy.Form, {
		target: '#superForm',
		getMetaFromForm: false,
		addResultToForm: false,
		submitOnSuccess: false,
		triggerUploadOnSubmit: false
	})
	.use(Uppy.XHRUpload, {
		endpoint: '/admin/attachments/uppy.json',
		headers: {
			'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
		}
	})


	fileUppy.on('progress', (progress) => {
		// progress: integer (total progress percentage)
		//console.log(progress)
	})

	fileUppy.on('upload-success', (file, response) => {
		var dbID = response.body.id;
		//$('.upload__area__desktop-preview[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');
		//$('.upload__area__icon-preview[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');


		$('.onefile[data-file-id="' + file.source + '_' + file.id + '"] #view-upload a').attr('href', '/media/files/' + response.body.relative_path);
		$('.onefile[data-file-id="' + file.source + '_' + file.id + '"] #edit-upload').attr('data-href', '/admin/attachments/edit/' + dbID);
		$('.onefile[data-file-id="' + file.source + '_' + file.id + '"] #delete-upload').attr('data-href', '/admin/attachments/delete/' + dbID);

		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"] #view-upload a').attr('href', '/media/files/' + response.body.relative_path);
		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"] #edit-upload').attr('data-href', '/admin/attachments/edit/' + dbID);
		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"] #delete-upload').attr('data-href', '/admin/attachments/delete/' + dbID);
		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');
		$('.onefile[data-file-id="' + file.source + '_' + file.id + '"]').attr('data-db-id', dbID);

		var file = {
			id: dbID,
			model: response.body.model
		}
		attachments.push(file);
	})

    fileUppy.on('error', (error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    fileUppy.on('upload-error', (file, error, response) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    fileUppy.on('restriction-failed', (file, error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

	var mainUppy = new Uppy.Core({
		locale: Uppy.locales.it_IT,
		restrictions: {
			maxFileSize: 8000000,
			allowedFileTypes: ['image/*'],
		}
	})
	.use(Uppy.Form, {
		target: '#superForm',
		getMetaFromForm: false,
		addResultToForm: false,
		submitOnSuccess: false,
		triggerUploadOnSubmit: false
	})
	//.use(Uppy.Compressor, {quality: 0.9, limit: 10}) // tolto perché dava problemi con svg!
	.use(Uppy.XHRUpload, {
		endpoint: '/admin/images/uppy.json',
		headers: {
			'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
		}
	})


	mainUppy.on('progress', (progress) => {
		// progress: integer (total progress percentage)
		//console.log(progress)
	})

	mainUppy.on('upload-success', (file, response) => {
		var dbID = response.body.id;

		$('.upload__area__desktop-preview[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');
		$('.upload__area__icon-preview[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');
		$('.onefile[data-file-id="' + file.source + '_' + file.id + '"] .upload__image-preview').addClass('loaded');

		var queryParams = '';
		if ($('.upload__area').is('[data-video]')) queryParams += 'video=true';
		if ($('.upload__area').is('[data-video-mobile]')) queryParams += '&videoMobile=true';

		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"] #edit-upload').attr('data-href', '/admin/images/edit/' + dbID + '?' + queryParams);
		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"] #delete-upload').attr('data-href', '/admin/images/delete/' + dbID).attr('data-id', file.id);
		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');
		$('.onefile[data-file-id="' + file.source + '_' + file.id + '"]').attr('data-db-id', dbID);

		var file = {
			id: dbID,
			model: response.body.model
		}

		mainImages.push(file);
	})

    mainUppy.on('error', (error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    mainUppy.on('upload-error', (file, error, response) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    mainUppy.on('restriction-failed', (file, error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

	var responsiveUppy = new Uppy.Core({
		locale: Uppy.locales.it_IT,
		restrictions: {
			maxFileSize: 8000000,
			allowedFileTypes: ['image/*'],
		}
	})
	.use(Uppy.Form, {
		target: '#superForm',
		getMetaFromForm: false,
		addResultToForm: false,
		submitOnSuccess: false,
		triggerUploadOnSubmit: false
	})
	//.use(Uppy.Compressor, {quality: 0.9, limit: 10})
	.use(Uppy.XHRUpload, {
		endpoint: '/admin/responsive_images/uppy.json',
		headers: {
			'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
		}
	})

	responsiveUppy.on('progress', (progress) => {
		// progress: integer (total progress percentage)
		//console.log(progress)
	})


	responsiveUppy.on('upload-success', (file, response) => {
		var dbID = response.body.id;
		$('.upload__area__mobile-preview[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');

		$('.upload__controls[data-file-id="' + file.source + '_' + file.id + '"]').addClass('loaded');

		var file = {
			id: dbID,
			model: response.body.model
		}
		responsiveImages.push(file);
	})

    responsiveUppy.on('error', (error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    responsiveUppy.on('upload-error', (file, error, response) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    responsiveUppy.on('restriction-failed', (file, error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

}


$('#superForm').on('click', '.save button', function(){
	var $button = $(this);
	if($button.val() == 'stay') {
		$('input[name="_stay"]').val(true)
	} else {
		$('input[name="_stay"]').val(false)
	}
})

$('#superForm').on('submit', function(){
    document.querySelector('body').classList.add('body--loading');
	if(mainUppy.getFiles()) {
		mainUppy.upload().then((result) => {
			if(responsiveUppy.getFiles()) {
				responsiveUppy.upload().then((result) => {
					ajaxSubmit();
				})
			} else {
				ajaxSubmit();
			}
		})
	} else {
		ajaxSubmit();
	}

	return false;
})

function startUpload() {

	if(mainUppy.getFiles()) {
		mainUppy.upload().then((result) => {
			if(responsiveUppy.getFiles()) {
				responsiveUppy.upload().then((result) => {

				})
			}
		})
	}

	if(fileUppy.getFiles()) {
		fileUppy.upload().then((result) => {})
	}
}

function ajaxSubmit() {

	var $form = $('#superForm');
	var data = $form.serializeObject();
	var formURL = $form.attr('action');


	if(mainImages.length > 0) data['images'] = mainImages;
	if(attachments.length > 0) data['attachments'] = attachments;

	$.ajax({
		dataType: "json",
		url : formURL,
		method: 'POST',
		data : data,
		headers: {
			'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
		},
		beforeSend: function(){
			$('.alert--error').removeClass('visible hidden');
			$form.find('div.error').each(function(index, errorDiv){
				$(errorDiv).removeClass('error');
				$(errorDiv).find('.form-error').removeClass('form-error');
				$(errorDiv).find('.error-message').remove();
			})
		},
		success: function(response) {
            document.querySelector('body').classList.remove('body--loading');
			$('.alert').remove();

			if(response.errors) {
				for(var field in response.errors) {
					var field_errors = response.errors[field];
					var field_message = field_errors[Object.keys(field_errors)[0]];

					if (typeof field_message === 'string' || field_message instanceof String) {
						$form.find('*[name="' + field + '"]')
							.addClass('form-error')
							.closest('div')
							.addClass('error')
							.append('<div class="error-message">' + field_message + '</div>');
					} else if (typeof field_message === 'object' && field_message !== null) {
						// ci sono dei messaggi nei modelli associati
						var nestedField = field;
						var nestedFieldErrors = field_errors;
                        
                        console.log(nestedFieldErrors);
                        Object.keys(nestedFieldErrors).forEach(function(i){
						

                            Object.keys(nestedFieldErrors[i]).forEach(function(key){


                                var nestedFieldSelector = key;
                                var nestedFieldMessage = nestedFieldErrors[i][key][Object.keys(nestedFieldErrors[i][key])[0]];
                                
                                $form.find('*[name="' + nestedField + '[' + i + '][' + nestedFieldSelector + ']"]')
                                    .addClass('form-error')
                                    .closest('div')
                                    .addClass('error')
                                    .append('<div class="error-message">' + nestedFieldMessage + '</div>');
                            });
                            
							
						});
					}
				}
			} else {
				if($form.find('input[name="_stay"]').val() == 'false') {
					location.replace(response.redirect);
				} else if(response.forceRedirect) {
					location.replace(response.forceRedirect);
				}
			}

			getFlash();
		}
	})
}

function getFlash(){

	$.ajax({
		url : '/admin/magic/get-flash.json'
	}).done(function(data){
		$('#flash-wrapper').html(data);
		setTimeout(function(){ $('.alert').addClass('visible'); }, 100);
		setTimeout(function(){ $('.alert').addClass('hidden'); }, 3000);
	})

}

$(window).on('load', function(){
	setTimeout(function(){ $('.alert').addClass('visible'); }, 150);
})


// Apri chiudi popup generici.  popupToggle(boolean)
function popupToggle(isOpen) {

	let status = isOpen;

	if(status){
	  $('body').addClass('popup-open');
	}else{
	  $('body').removeClass('popup-open');
	}


}

// modifica dati file/immagini caricate
$('.uploader').on('click', '#edit-upload', function (e) {

	popupToggle(true);

	$('#quickEdit').removeClass('closed');
	$('#quickEdit').addClass('open');

	var $trigger = $(this);
	$quickEditReference = $trigger;

	$.ajax({
	  dataType: "json",
	  url: $trigger.attr('data-href'),
	  beforeSend: function(){
		 $('#quickEdit .quick-edit__content').addClass('loading').html('');
	  },
	  success: function(response) {
		 $('#quickEdit .quick-edit__content').removeClass('loading').html(response['html']);
	  }
	});

	return false;

});

$('#quickEdit').on('submit', 'form', function(){

	var $form   = $(this);
	var data    = $form.serializeArray();
	var formURL = $form.attr('action');

	$.ajax({
		url : formURL,
		method: 'POST',
		data : data,
		headers: {
			'X-CSRF-Token': "<?php echo $this->request->getAttribute('csrfToken'); ?>"
		},
		success: function(response) {
			//immagini
			$quickEditReference.closest('.upload__controls').find('.upload__controls__filename').text(response['file']['filename']);
			//allegati
			$quickEditReference.closest('.onefile').find('.upload__controls__filename').text(response['file']['filename']);
		}
	})

	return false;

})


$('#quickEdit').on('click', '.btn[data-button="undo"]',function () {
	$('#quickEdit').removeClass('open');
	$('#quickEdit').addClass('closed');
	popupToggle(false);
})

$('#quickEdit').on('click', '.btn[data-button="save"]',function () {

	$('#quickEdit form').trigger('submit');

	$('#quickEdit').removeClass('open');
	$('#quickEdit').addClass('closed');
	popupToggle(false);
})

$('[data-toggle-field]').on('click', function(ev){
	ev.preventDefault();
	let trigger = $(this),
        newValue = !trigger.hasClass('active');

    trigger.toggleClass('active', newValue);

	$.ajax({
		dataType: "json",
		url: '<?= $this->Url->build(['action' => 'toggleField']) ?>.json',
		data: {
			field: trigger.data('toggle-field'),
			id: trigger.data('toggle-id')
		},
		success: function(data) {
            if (data.newValue != newValue) trigger.toggleClass('active', data.newValue);
		}
	});
});

$('[data-select-add]').each(function(i,el){
    let $wrapper = $(this),
        $select = $('select', $wrapper),
        $close = $('[data-select-add-close]', $wrapper);

    $select.on('change', function(){
        $wrapper.toggleClass('new-value', $(this).val() == -1 || $(this).val() == 'new');
    }).trigger('change');

    $close.on('click', function(){
        $wrapper.removeClass('new-value');
        $select.val('');
    })

        
});

$('[data-multicheckbox]').multiCheckbox();

<?php
$this->Html->scriptEnd();
?>
</script>
