			<div class="tabs__tab" data-tab="social">
                <fieldset class="input-group">
                    <?php
    				echo $this->element('admin/uploader/image', [
    					'scope' => 'social_preview',
    					'title' => __d('admin', 'social preview'),
                        'width' => 1920,
					    'height' => 1080,
    				]);
                    ?>
                </fieldset>
            </div>