<?= $this->element('ContentBlocks.frontend/page-fullscreen-common', [
    'extraClass' => 'page-fullscreen--gallery',
    'item' => $item,
    'gallery' => $images['gallery'] ?? null,
]); ?>