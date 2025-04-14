<?php
$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'categories'),
            'url' => ['action' => 'index']
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>
<?= $this->Form->create($item) ?>
<div class="tabs" data-tabs>
    <?php echo $this->element('admin/tabs-menu');?>
    <div class="tabs__content">
        <div class="tabs__tab" data-tab="edit">
            <fieldset class="input-group">
                <legend class="input-group__info">
                    Generali    
                </legend>
                <?php
                echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-5']);
                echo $this->Form->control('parent_id', ['label' => __dx($po, 'admin', 'parent'), 'options' => $parents, 'empty' => __d('admin', 'Select'), 'extraClass' => 'span-5']);
                echo $this->Form->control('published', ['label' => __d('admin', 'published'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
                ?>
        
                <?php
                // echo $this->Form->control('excerpt', ['label' => __d('admin', 'excerpt')]);
                // echo $this->Form->control('text', ['label' => __d('admin', 'text')]);
                ?>
            </fieldset>
        </div>

        <?php echo $this->element('admin/tab-seo');?>
        <?php echo $this->element('admin/tab-social');?>
    </div>
</div>

<?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
