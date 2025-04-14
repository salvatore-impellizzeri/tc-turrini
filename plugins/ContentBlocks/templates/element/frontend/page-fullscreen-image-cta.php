<?= $this->element('ContentBlocks.frontend/page-fullscreen-common', [
    'extraClass' => 'page-fullscreen--image',
    'item' => $item,
    'images' => $images,
    'cta' => $item->title_1,
    'url' => $item->title_2,
    'text' => $item->text_1,
]); ?>