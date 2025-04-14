<?php
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Query;

if (empty($showPopup)) return;
$popup = FactoryLocator::get('Table')->get('Snippets.Snippets')->findByIdAndPublished(13,true)->contain('Preview')->first();
if (empty($popup)) return;
?>

<div class="popup popup--image" data-popup>
    <span class="popup__close" data-popup-close></span>
    <div class="popup__bg">
        <?php if (!empty($popup->preview->path)): ?>
            <img src="<?= $this->Frontend->image($popup->preview->path) ?>" alt="">
        <?php endif; ?>
    </div>
    <div class="popup__fg">
        <div class="popup__fg-wrapper">
            <h4 class="popup__title"><?= $popup->title_2 ?></h4>
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