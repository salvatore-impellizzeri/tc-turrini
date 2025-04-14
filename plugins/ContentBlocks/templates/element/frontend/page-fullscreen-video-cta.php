<?= $this->element('ContentBlocks.frontend/page-fullscreen-common', [
    'extraClass' => 'page-fullscreen--video',
    'item' => $item,
    'video' => $item->title_3,
    'cta' => $item->title_1,
    'url' => $item->title_2,
    'text' => $item->text_1,
]); ?>