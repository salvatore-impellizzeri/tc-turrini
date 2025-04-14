<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->element('favicon') ?>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,FILL,GRAD@24,0..1,100 "rel="stylesheet">
	<link href="/admin-assets/js/cropper/cropper.css" rel="stylesheet" />
	<link href="/admin-assets/js/uppy/uppy.min.css" rel="stylesheet" />

    <?= $this->AssetCompress->css('admin'); ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <script src="/admin-assets/js/tinymce/tinymce.min.js"></script>

    <script>
        tinymce.init({
            plugins: 'code',
            selector: '.inline-editor textarea',
            language: 'it_IT',
            menubar: false,
            formats: {
                removeformat: [
                    {
                        selector: 'p,b,strong,em,i,font,u,strike,s,sub,sup,dfn,code,samp,kbd,var,cite,mark,q,del,ins,small',
                        remove: 'all',
                        split: true,
                        block_expand: true,
                        expand: false,
                        deep: true
                    },
                    { selector: 'span', attributes: ['style', 'class'], remove: 'empty', split: true, expand: false, deep: true },
                    { selector: '*', attributes: ['style', 'class'], split: false, expand: false, deep: true }
                ]
            },
            toolbar: "bold italic underline removeformat| code",
            forced_root_block : "",
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });

        tinymce.init({
            height: 300,
            plugins: 'image imagetools paste code link lists media visualblocks responsivefilemanager',
            selector: '.editor textarea',
            language: 'it_IT',
            style_formats: [
            { title: 'Intestazione', format: 'h4'},
            { title: 'Blocks', items: [
                { title: 'Paragraph', format: 'p' },
                { title: 'Blockquote', format: 'blockquote' },
                { title: 'Div', format: 'div' },    
            ]},
            ],
            paste_as_text: true,
            image_caption: true,
            relative_urls: false,
            external_filemanager_path: "/admin-assets/vendor/filemanager/",
            filemanager_title: "Responsive Filemanager",
            external_plugins: {
                "filemanager": "/admin-assets/vendor/filemanager/plugin.min.js"
            },
            menu: {
                file: { title: 'File', items: '' }, // Vuota il menu "File"
                edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext' }, // Specifica solo le opzioni desiderate
                view: { title: 'View', items: 'code' },
                insert: { title: 'Insert', items: 'image link' },
                format: { title: 'Format', items: '' },
                tools: { title: 'Tools', items: '' },
                tools: { title: 'Table', items: '' }
            },
            toolbar: 'undo redo | bold | bullist numlist | h4 h5 h6',
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });

    </script>

</head>

<body class="body <?= $this->fetch('bodyClass') ?? '' ?>">
    <?= $this->element('admin/update-device') ?>

    <main class="main-wrapper">
        <?= $this->element('admin/sidebar') ?>


        <div class="content" id="app">
            <?= $this->fetch('content') ?>
        </div>

		<div id="flash-wrapper"><?= $this->Flash->render() ?></div>

		<div class="quick-edit labelled" id="quickEdit">
			<div class="quick-edit__heading label">
				<?= __d('admin', 'quick edit') ?>
			</div>
            <div class="quick-edit__content">

            </div>

            <div class="quick-edit__btn">
                <div class="btn btn--light" data-button="undo">
                    <?= __d('admin', 'undo') ?>
                </div>
                <div class="btn btn--primary" data-button="save">
                    <?= __d('admin', 'save form') ?>
                </div>
            </div>
		</div>
    </main>
    <div class="preload">
        <div class="preload__progress-container">
            <div class="preload__progress"></div>
        </div>
    </div>

</body>

<?= $this->Html->scriptBlock(sprintf('var csrfToken = %s;', json_encode($this->request->getAttribute('csrfToken'))));?>
<?= $this->Html->script('/admin-assets/js/jquery.js');?>
<?= $this->Html->script('/admin-assets/js/sweetalert2.all.js');?>
<?= $this->Html->script('/admin-assets/js/Sortable.min.js');?>
<?= $this->Html->script('/admin-assets/js/jquery.serialize-object.js');?>
<?= $this->Html->script('/admin-assets/js/jquery.tabs.js');?>
<?= $this->Html->script('/admin-assets/js/jquery.multicheckbox.js'); ?>
<?= $this->Html->script('/admin-assets/js/uppy/uppy.min.js');?>
<?= $this->Html->script('/admin-assets/js/uppy/it_IT.min.js');?>
<?= $this->Html->script('/admin-assets/js/cropper/cropper.js');?>
<?= $this->Html->script('/admin-assets/js/side-menu.js');?>
<?= $this->Html->script('/admin-assets/js/custom.js');?>
<?= $this->element('admin/common-scripts');?>

<?= $this->fetch('scriptBottom') ?>

</html>
