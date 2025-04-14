<?php
$this->extend('/Admin/Common/edit');
$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => $menu->title ?? $item->menu->title,
            'url' => ['action' => 'list', $menu->id ?? $item->menu_id]
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>

<?= $this->Form->create($item) ?>
    <fieldset class="input-group">
        <legend class="input-group__info">
            Impostazioni
        </legend>
        <?php
        echo $this->Form->control('title', ['label' => __dx($po, 'admin', 'title'), 'extraClass' => 'span-5']);
        echo $this->Form->control('parent_id', ['empty' => __dx($po, 'admin', 'nothing'), 'label' => __dx($po, 'admin', 'parent'), 'extraClass' => 'span-4']);
        echo $this->Form->control('blank', ['label' => __dx($po, 'admin', 'blank'), 'extraClass' => 'span-2']);
        echo $this->Form->control('published', ['label' => __dx($po, 'admin', 'published'), 'extraClass' => 'span-1']);
        ?>

        <div data-url class="span-5">
            <?php
            echo $this->Form->control('url', ['label' => __dx($po, 'admin', 'url')]);
            ?>
        </div>

        <div data-custom-url class="span-5">
            <?php
            echo $this->Form->control('url', ['type' => 'text', 'disabled' => true, 'label' => __dx($po, 'admin', 'url')]);
            ?>
        </div>

        <?php echo $this->Form->control('custom', ['data-custom-toggler', 'label' => __dx($po, 'admin', 'custom'), 'extraClass' => 'span-2']); ?>


        <?php
        echo $this->Form->hidden('menu_id', ['value' => $this->request->getQuery('menu_id')]);
        ?>
    </fieldset>

    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>

<?= $this->Html->scriptStart(['block' => 'scriptBottom']) ?>
$('[data-custom-toggler]').on('change', function(){
    let isChecked = $(this).is(':checked');
    $('[data-custom-url]').toggle(isChecked);
    $('[data-url]').toggle(!isChecked);
    $('[data-custom-url]').find('input').prop("disabled", !isChecked);

}).trigger('change');
<?= $this->Html->scriptEnd() ?>
