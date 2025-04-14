<?php
$inputPrefix = "content_blocks.$blockIndex";
echo $this->Form->hidden("$inputPrefix.id", ['value' => $blockId]); 
?>

<?php
echo $this->Form->control("$inputPrefix.title_1", ['label' => 'Titolo']);
echo $this->Form->editor("$inputPrefix.text_1", ['label' => 'Testo']);
?>