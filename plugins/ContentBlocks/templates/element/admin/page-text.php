<?php
$inputPrefix = "content_blocks.$blockIndex";
echo $this->Form->hidden("$inputPrefix.id", ['value' => $blockId]); 
?>

<?php
echo $this->Form->editor("$inputPrefix.text_1", ['label' => 'Testo']); 
echo $this->element('admin/uploader/file-set',[
    'scope' => "[$blockId]attachments"
]);
?>