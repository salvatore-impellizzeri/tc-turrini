<?php
use Cake\Core\Configure;

$this->assign('headerShowProgress', true);
$this->assign('headerTitle', $item->title);
$this->assign('mainClass', 'main--no-padding');

if (!empty($item->color)) $this->assign('mainBg', $item->color);

$overlapStyle = !empty($item->color) ? ' style="background-color: '.$item->color.'"' : '';

if(!empty($images['hero']->path)) {
	$this->set('og_image_for_layout', $this->Frontend->image($images['hero']->path));
}
?>

<article class="post">

	<?= $this->element('post-header', ['post' => $item]) ?>


	<?= $this->Frontend->renderContentBlocks($item->content_blocks) ?>


</article>
