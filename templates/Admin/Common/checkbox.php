<?php
if (!$total) {
    echo $this->Html->div('multiCheckbox__empty', __d('admin', 'multicheckbox no results found'));
} else {
    echo $this->element('multicheckbox-options', [
        'items' => $records,
        'checked' => $checked
    ]);
    
}

?>
