<?php
if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE) return; //gli allegati non sono traducibili, se servono aggiungere un altro uploader
$title = $title ?? 'Carica File';
$uniqueId = 'uploader_' . bin2hex(random_bytes(10));

// ci serve per identificare le varie versioni (desktop, mobile, ecc...) della stessa immagine
$same = bin2hex(random_bytes(10)) . '_' . uniqid();
?>

<div class="block attachment <?php if(!empty($class)) echo $class; ?>">
    <div class="input uploader uploader--attachment">

        <label for="<?php echo $uniqueId; ?>">
            <div class="label-heading">
				<?php echo $this->Backend->materialIcon('attach_file') ?>
				<?php echo $title; ?>
			</div>
        </label>

        <div class="upload <?php echo !empty($attachments[$scope]['path']) ? 'loaded' : ''; ?>"
            id="<?php echo $uniqueId; ?>">
            <div class="upload__area">
            
                <?php if(!empty($attachments[$scope]['path'])):?>
                    <div class="upload__file-preview loaded">
                        <div class="upload__ext">
                            <?php if(!empty($attachments[$scope]['path'])) echo $attachments[$scope]['ext']; ?>
                        </div>
                        <span class="material-symbols-rounded file-placeholder">file_present</span>
                        <div class="upload__controls loaded">
                            <div class="upload__controls__filename"><?php echo $attachments[$scope]['filename'] ?? ''; ?></div>
                            <div class="upload__controls__actions">
                                <span class="material-symbols-rounded" id="view-upload"><a href="<?php echo $attachments[$scope]['path'] ?? ''; ?>" target="blank">preview</a></span>
                                <span class="material-symbols-rounded" id="edit-upload" data-href="/admin/attachments/edit/<?php echo $attachments[$scope]['id'] ?? ''; ?>.json">edit</span>
                                <span class="material-symbols-rounded" id="delete-upload" data-href="/admin/attachments/delete/<?php echo $attachments[$scope]['id'] ?? ''; ?>.json">delete</span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="upload__file-preview">
                        <div class="upload__ext"></div>
                        <span class="material-symbols-rounded file-placeholder">file_present</span>
                        <div class="upload__controls">
                            <div class="upload__controls__filename"></div>
                            <div class="upload__controls__actions">
                                <span class="material-symbols-rounded" id="view-upload"><a href="#" target="blank">preview</a></span>
                                <span class="material-symbols-rounded" id="edit-upload" data-href="">edit</span>
                                <span class="material-symbols-rounded" id="delete-upload" data-href="">delete</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
			<div class="upload__text">
				<div class="upload__text--inner-label"><?php echo __d('admin', 'uploader upload file') ?></div>
				<span class="material-symbols-rounded">file_upload</span>
				<div><?php echo __d('admin', 'uploader upload drag file') ?></div>
            </div>
        </div>
    </div>
</div>

<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
(function(){

    var attachment;

    var attUppy = new Uppy.Core({
        locale: Uppy.locales.it_IT,
        id: '<?php echo $scope; ?>',
        restrictions: {
        maxFileSize: 8000000,
        maxNumberOfFiles : 1,
        //allowedFileTypes: ['.svg'],
    },
    onBeforeFileAdded: (currentFile, files) => {
        // rimuove i file precedenti dagli spazi di caricamento globale
        removeFilesFromQueue('<?php echo $scope; ?>');
        }
    })
    .use(Uppy.DragDrop, { target: '#<?php echo $uniqueId; ?> .upload__area' })

    attUppy.on('file-added', (file) => {

        attachment = file;
        var filename = file.name.replace(new RegExp('\.' + file.extension + '$'), '');
        $('#<?php echo $uniqueId; ?> .upload__controls__filename').text(filename);

        var attID = fileUppy.addFile({
            name: attachment.name, // file name
            type: attachment.type, // file type
            data: attachment.data, // file blob
            isRemote: false,
            source: '<?php echo $scope; ?>',
            meta: {
                relativePath: 'attachment_' + '<?php echo $scope; ?>',
                model : '<?php echo $modelName; ?>',
                scope : '<?php echo $scope; ?>',
                same : '<?php echo $same; ?>',
                //filename : $('#<?php echo $uniqueId; ?> input[name="_filename"]').val(),
                filename: filename
            }

        })


        $('#<?php echo $uniqueId; ?>').find('.upload__ext').text(file.extension);
        $('#<?php echo $uniqueId; ?>').find('.upload__controls__filename').text(filename);
        $('#<?php echo $uniqueId; ?> .upload__controls').attr('data-file-id', '<?php echo $scope; ?>_' + attID),
        $('#<?php echo $uniqueId; ?> .upload__file-preview').attr('data-file-id', '<?php echo $scope; ?>_' + attID),


        $('#<?php echo $uniqueId;?>').addClass('loaded');

        window.startUpload();
    })

    attUppy.on('error', (error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ': ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    attUppy.on('upload-error', (file, error, response) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader upload error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    attUppy.on('restriction-failed', (file, error) => {
        Swal.fire({
            icon: 'error',
            text: '<?= __d('admin', 'uploader restriction error') ?>' + ' "' + file.name + '": ' + error.message,
            confirmButtonColor: '#D81159',
        });
    });

    function removeFilesFromQueue(scope){

        // elimina immagini dello scope specificato dal fileUppy
        var toBeRemoved = fileUppy.getFiles().filter(function(item){ return item.source == scope}).map(function(item){
        return item.id });
        toBeRemoved.forEach((item, index) => fileUppy.removeFile(item));

    }


    $('#<?php echo $uniqueId; ?> #delete-upload').on('click', function(){

        let text = "<?php echo __d('admin', 'Sei sicuro di voler eliminare questo file?'); ?>";
        if (confirm(text) == true) {
            var trigger = $(this);

            $.ajax({
                dataType: "json",
                url: trigger.attr('data-href'),
                success: function(data) {
                $('#<?php echo $uniqueId; ?>').removeClass('loaded');
                attUppy.reset();
                    removeFilesFromQueue('<?php echo $scope; ?>');
                }
            });
        }

        return false;

    });

})()

<?php $this->Html->scriptEnd(); ?>