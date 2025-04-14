			<div class="tabs__tab" data-tab="seo">
                <fieldset class="input-group">
                    <?php
                    echo $this->Form->control('seotitle', ['label' => __d('admin', 'seotitle'), 'extraClass' => 'span-12']);
                    echo $this->Form->control('seodescription', ['label' => __d('admin', 'seodescription'), 'extraClass' => 'span-12']);
                    echo $this->Form->control('seokeywords', ['label' => __d('admin', 'seokeywords'), 'extraClass' => 'span-12']);
                    ?>
                </fieldset>
            </div>