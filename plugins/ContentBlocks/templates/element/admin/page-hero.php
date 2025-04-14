<?php
$inputPrefix = "content_blocks.$blockIndex";
echo $this->Form->hidden("$inputPrefix.id", ['value' => $blockId]); 
?>

<?php
echo $this->Form->control("$inputPrefix.title_1", ['label' => 'Titoletto', 'extraClass' => 'span-8']);
echo $this->Form->control("$inputPrefix.flag_1", ['label' => __dx($po, 'admin', 'light text'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
echo $this->Form->control("$inputPrefix.flag_2", ['label' => __dx($po, 'admin', 'add overlay'), 'type' => 'checkbox', 'extraClass' => 'span-2']);
echo $this->Form->control("$inputPrefix.title_2", ['label' => 'Titolo']);
echo $this->Form->control("$inputPrefix.title_3", ['label' => 'CTA', 'extraClass' => 'span-6']);
echo $this->Form->control("$inputPrefix.title_4", ['label' => 'URL', 'extraClass' => 'span-6']);

echo $this->element('admin/uploader/image', [
    'scope' => "[$blockId]hero", 
    'width' => 2560, 
    'height' => 1440, 
    'mobile' => ['width' => 810, 'height' => 1080],
]);
?>