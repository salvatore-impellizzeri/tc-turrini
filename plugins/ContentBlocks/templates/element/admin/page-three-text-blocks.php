<?php
$inputPrefix = "content_blocks.$blockIndex";
echo $this->Form->hidden("$inputPrefix.id", ['value' => $blockId]); 
?>

<?php
echo $this->element('admin/uploader/icon', ['scope' => "[$blockId]icon_1", 'title' => 'Icona']);
echo $this->Form->control("$inputPrefix.title_1", ['label' => 'Titolo']);
echo $this->Form->editor("$inputPrefix.text_1", ['label' => 'Testo']);
echo $this->Form->control("$inputPrefix.title_2", ['label' => 'CTA', 'extraClass' => 'span-6']);
echo $this->Form->control("$inputPrefix.title_3", ['label' => 'URL', 'extraClass' => 'span-6']);
?>

<?php
echo $this->element('admin/uploader/icon', ['scope' => "[$blockId]icon_2", 'title' => 'Icona']);
echo $this->Form->control("$inputPrefix.title_4", ['label' => 'Titolo']);
echo $this->Form->editor("$inputPrefix.text_2", ['label' => 'Testo']);
echo $this->Form->control("$inputPrefix.title_5", ['label' => 'CTA', 'extraClass' => 'span-6']);
echo $this->Form->control("$inputPrefix.title_6", ['label' => 'URL', 'extraClass' => 'span-6']);
?>

<?php
echo $this->element('admin/uploader/icon', ['scope' => "[$blockId]icon_3", 'title' => 'Icona']);
echo $this->Form->control("$inputPrefix.title_7", ['label' => 'Titolo']);
echo $this->Form->editor("$inputPrefix.text_3", ['label' => 'Testo']);
echo $this->Form->control("$inputPrefix.title_8", ['label' => 'CTA', 'extraClass' => 'span-6']);
echo $this->Form->control("$inputPrefix.title_9", ['label' => 'URL', 'extraClass' => 'span-6']);
?>