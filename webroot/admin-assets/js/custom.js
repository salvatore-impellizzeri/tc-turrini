$(function() {

	const body = document.querySelector('body');
	const quickEdit = document.querySelector('#quickEdit');
	const editUpload = document.querySelector('#edit-upload');

	$('#close-editor, .photo-editor__modal--submit .btn').click(() => {
		$('.photo-editor').attr('data-toggle', 'closed');
		$('.device--selector').attr('data-toggle-device', 'desktop');
		$('.desktop-frame').attr('data-toggle', 'true');
		$('.mobile-frame, .alt-image').attr('data-toggle', 'false');
	})

	$('.save-photo-editor').click(() =>{
		$('.upload__area__desktop-preview , .upload__area__mobile-preview').addClass('loading-img');
	})

	$('#desktop.device').click(() => {
		$('.device--selector').attr('data-toggle-device', 'desktop');
		$('.desktop-frame').attr('data-toggle', 'true');
		$('.mobile-frame, .alt-image').attr('data-toggle', 'false');
		$('[data-device="mobile"]').removeClass('active');
		$('[data-device="desktop"]').addClass('active');
	})

	$('#mobile.device').click(() => {
		$('.device--selector').attr('data-toggle-device', 'mobile');
		$('.desktop-frame').attr('data-toggle', 'false');
		$('.mobile-frame, .alt-image').attr('data-toggle', 'true');
		$('[data-device="desktop"]').removeClass('active');
		$('[data-device="mobile"]').addClass('active');
	})

	$('#flash-wrapper').on('click', '[close-alert]', function () {
		$(this).closest('.alert').removeClass('visible');
		$(this).closest('.alert').addClass('hidden');
	})


	$(document).click(function(e){
		if($(e.target).closest(quickEdit).length != 0) return false;
		$(quickEdit).removeClass('open');
		$(body).removeClass('popup-open');
     });

     $('[data-tabs]').tabs();


});
