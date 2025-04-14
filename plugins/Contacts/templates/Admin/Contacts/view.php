<?php
$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => $item->contact_form->title,
            'url' => ['action' => 'list', $item->contact_form_id]
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>


<fieldset class="input-group">
    <?php
    foreach ($fieldNames as $field => $label) {
        if (!empty($item->{$field})) echo $this->Form->control($field, ['label' => $label, 'disabled' => 'disabled', 'value' => $item->{$field}]);
    }
    ?>
</fieldset>


