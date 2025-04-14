<div class="page-three-text-blocks page-section">
    <div class="page-three-text-blocks__grid">
        <?php for ($i = 0; $i < 3; $i++): 
            $titleIndex = $i * 3 + 1;
            $textIndex = $i + 1;
            if (empty($item->{'title_'.$titleIndex}) || empty($item->{'text_'.$textIndex})) continue;    
            ?>
            <div class="page-three-text-blocks__section">
                <?php if (!empty($images['icon_'.($i + 1)])): ?>
                    <div class="page-three-text-blocks__icon">
                        <?php echo file_get_contents(WWW_ROOT.$images['icon_'.($i + 1)]->backend_path); ?>
                    </div>
                <?php endif; ?>
                <h3 class="page-three-text-blocks__title"><?= $item->{'title_'.($i * 3 + 1)} ?></h3>
                <div class="page-three-text-blocks__content">
                    <?= $item->{'text_'.($i + 1)} ?>
                </div>

                <?php if (!empty($item->{'title_'.($titleIndex + 1)}) && !empty($item->{'title_'.($titleIndex + 2)})): ?>
                    <div class="page-three-text-blocks__cta">
                        <?= $this->element('cta', [
                            'label' => $item->{'title_'.($titleIndex + 1)},
                            'url' => $item->{'title_'.($titleIndex + 2)},
                            'icon' => 'icons/arrow-right.svg',
                        ]); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
    
</div>