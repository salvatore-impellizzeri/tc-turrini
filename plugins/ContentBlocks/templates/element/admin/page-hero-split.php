<?php
$inputPrefix = "content_blocks.$blockIndex";
echo $this->Form->hidden("$inputPrefix.id", ['value' => $blockId]); 
?>

<?php
echo $this->Form->control("$inputPrefix.title_1", ['label' => 'Titoletto', 'extraClass' => 'span-12']);
echo $this->Form->control("$inputPrefix.title_2", ['label' => 'Titolo']);
echo $this->Form->control("$inputPrefix.title_3", ['label' => 'CTA', 'extraClass' => 'span-6']);
echo $this->Form->control("$inputPrefix.title_4", ['label' => 'URL', 'extraClass' => 'span-6']);

echo $this->element('admin/uploader/image', [
    'scope' => "[$blockId]hero", 
    'width' => 2560, 
    'height' => 1440/2, 
    'mobile' => ['width' => 810, 'height' => 1080/2],
]);
?>