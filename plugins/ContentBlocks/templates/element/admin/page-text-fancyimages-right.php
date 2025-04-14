<?php
$inputPrefix = "content_blocks.$blockIndex";
echo $this->Form->hidden("$inputPrefix.id", ['value' => $blockId]); 
?>

<?php
echo $this->Form->control("$inputPrefix.title_1", ['label' => 'Titoletto']);
echo $this->Form->control("$inputPrefix.title_2", ['label' => 'Titolo']);
echo $this->Form->editor("$inputPrefix.text_1", ['label' => 'Testo']);
echo $this->Form->control("$inputPrefix.title_3", ['label' => 'CTA', 'extraClass' => 'span-6']);
echo $this->Form->control("$inputPrefix.title_4", ['label' => 'URL', 'extraClass' => 'span-6']);

echo $this->element('admin/uploader/image', [
    'scope' => "[$blockId]image_1", 
    'title' => 'Immagine davanti',
    'width' => 300 * 1.5, 
    'height' => 400 * 1.5, 
]);

echo $this->element('admin/uploader/image', [
    'scope' => "[$blockId]image_2", 
    'title' => 'Immagine dietro',
    'width' => 600 * 1.5, 
    'height' => 400 * 1.5, 
]);
?>