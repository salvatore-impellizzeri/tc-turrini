<?php
$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['controller' => 'ContactForms', 'action' => 'index']
        ],
        [
            'title' => $contactForm->title ?? $item->contact_form->title,
            'url' => ['action' => 'list', $contactForm->id ?? $item->contact_form_id]
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
        Generali
    </legend>
        <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-4']);
        echo $this->Form->control('type', ['label' => __dx($po, 'admin', 'type'), 'extraClass' => 'span-4']);
        echo $this->Form->control('required', ['label' => __dx($po, 'admin', 'required'), 'extraClass' => 'span-2']);
        echo $this->Form->control('show_in_mail', ['label' => __dx($po, 'admin', 'show_in_mail'), 'extraClass' => 'span-2', 'default' => 1]);
        echo $this->Form->control('label', ['label' => __dx($po, 'admin', 'label'), 'extraClass' => 'span-6']);
        echo $this->Form->control('field', ['label' => __dx($po, 'admin', 'field'), 'extraClass' => 'span-6']);
		echo $this->Form->hidden('contact_form_id', ['value' => $this->request->getQuery('contact_form_id') ?? $item->contact_form_id]);
        echo $this->Form->control('multiple_values', ['label' => __dx($po, 'admin', 'multiple_values'), 'extraClass' => 'multivalues']);
        ?>
    </fieldset>

    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>

<?= $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
$('#type').on('change', function(){
    $('.multivalues').toggle($(this).val() == 'select');
}).trigger('change');
<?= $this->Html->scriptEnd(); ?>
