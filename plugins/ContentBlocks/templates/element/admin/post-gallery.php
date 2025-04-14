<?php
$inputPrefix = "content_blocks.$blockIndex";
echo $this->Form->hidden("$inputPrefix.id", ['value' => $blockId]); 
?>

<?php
echo $this->element('admin/uploader/gallery', [
    'scope' => "[$blockId]gallery"
]);
?>