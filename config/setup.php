<?php
	return [
		'Setup' => [
			'domain' => 'http://crispy.webmotion.it',
			'sitename' => 'Crispy',
			'mailto' => 'webmaster@webmotion.it', //si gestisce da pannello, da usare solo con la vecchia form.
			'mailfrom' => 'sitoweb@webmotion.it',
			'locale' => [
				'it' => 'it_IT.UTF-8',
				'de' => 'de_DE.UTF-8',
				'en' => 'en_US.UTF-8',
				'fr' => 'fr_FR.UTF-8',
				'es' => 'es_ES.UTF-8',
				'pt' => 'pt_PT.UTF-8',
				'ru' => 'ru_RU.UTF-8',
				'ar' => 'ar_AR.UTF-8',
				'zh' => 'zh_CN.UTF-8'
			],
            'home' => ['plugin' => 'CustomPages', 'controller' => 'BlockPages', 'action' => 'view', 2], //url della pagina home -> cambiare se si usano le CustomPages
            'adminHomeController' => 'BlockPages', //controller di default admin -> cambiare se si usano le CustomPages
			'allowedImages' => ['jpeg','jpg','png','gif','bmp','svg'],
			'maxImageSize' => 4, // in MB
			'allowedFiles' =>  ['jpeg','jpg','png','gif','bmp','svg','pdf','docx','doc','txt','csv','rtf','zip','rar','mp3','ppt','xls','pptx','xlsx'],
			'maxFileSize' => 10, // in MB
			'admin_language' => 'it',
			'default_language' => 'it',
			'languages' => array(),
			'menu_depth' => 2,
			'font' => ''
		],
        'Shop' => [
            'vatIncuded' => true, //se true i prezzi inseriti nel pannello sono comprensivi di IVA
            'currency' => '€', // valuta
            'productsPerPage' => 4, // prodotti per pagina nel catalogo
        ]
	]
?>
