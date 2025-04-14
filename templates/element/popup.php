<?php
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Query;

if (empty($showPopup)) return;
$popup = FactoryLocator::get('Table')->get('Snippets.Snippets')->findByIdAndPublished(12,true)->contain('Preview')->first();
if (empty($popup)) return;
?>

<div class="popup" data-popup>
    <span class="popup__close" data-popup-close></span>
    <div class="popup__grid">
        <div class="popup__image">
            <?php if (!empty($popup->preview->path)): ?>
                <img src="<?= $this->Frontend->image($popup->preview->path) ?>" alt="">
            <?php endif; ?>
        </div>
        <div class="popup__content">
            <div class="popup__wrapper">
                <h4 class="popup__title"><?= $popup->title_2 ?></h4>
                <div class="popup__text">
                    <?= $popup->text ?>
                </div>
                <?php if (!empty($popup->title_3) && !empty($popup->title_4)): ?>
                    <div class="popup__cta">
                        <?= $this->element('cta', [
                            'label' => $popup->title_3, 
                            'url' => $popup->title_4,
                            'icon' => 'icons/arrow-right.svg',
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
        
    </div>
</div>