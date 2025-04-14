<?php
$bodyClasses = [];
if (!empty($item->fixed_header)) $bodyClasses[] = 'body--fixed-header';
if (!empty($item->light_header)) $bodyClasses[] = 'body--light-header';
$this->assign('bodyClass', implode(' ', $bodyClasses));
?>

<?= $this->Frontend->renderContentBlocks($item->content_blocks) ?>
