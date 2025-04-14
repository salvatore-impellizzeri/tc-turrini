$(function () {
	
	const $submenuLinks = $('.side-menu__item--hasmenu .side-menu__link');
	
	$submenuLinks.on('click', function(e){
		toggleSubmenu(this);
		e.preventDefault();
	});

	function toggleSubmenu(link) {		
		let item = 	$(link).closest('.side-menu__item--hasmenu');
		let submenu = $(item).find('.side-menu__submenu-wrapper');				
		let submenuID = $(item).data('submenu-id');
		
		$(item).toggleClass('side-menu__item--open');
            isOpen = Number($(item).hasClass('side-menu__item--open'));
            console.log(isOpen)
            $.get('/admin/magic/setSubmenuStatus/' + submenuID + '/' + isOpen, function(response){
			
		});
		
	}


})