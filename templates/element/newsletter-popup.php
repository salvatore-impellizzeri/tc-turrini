<?php
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Query;

$popup = FactoryLocator::get('Table')->get('Snippets.Snippets')->findByIdAndPublished(14,true)->contain('Preview')->first();
if (empty($popup)) return;
?>

<div class="popup popup--newsletter hidden" data-newsletter-popup>
    <span class="popup__close" data-newsletter-popup-close></span>
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
                <div class="popup__subscribe">
                    <form class="js-cm-form" id="subForm" action="https://www.createsend.com/t/subscribeerror?description=" method="post" data-id="A61C50BEC994754B1D79C5819EC1255C0AD40BD1EB97221946607EF4F58C2D2AFDB64D6BDF1F6E30F2CE83292BE725652AFEF8A976C0F106670FE6E1961236E8">
                        
                        <div class="input email required">
                            <input autocomplete="Email" aria-label="<?= __d('global', 'newsletter email') ?>" class="js-cm-email-input qa-input-email" id="fieldEmail" maxlength="200" name="cm-tyayhl-tyayhl" placeholder="<?= __d('global', 'newsletter email') ?>" required="" type="email">
                            <label for="fieldEmail"><?= __d('global', 'newsletter email') ?></label>
                        </div>
                        <?= $this->element('cta', [
                            'label' => $popup->title_3,
                            'icon' => 'icons/arrow-right.svg',
                        ]) ?>

                    </form>
                    <script type="text/javascript" src="https://js.createsend1.com/javascript/copypastesubscribeformlogic.js"></script>
                </div>
            </div>
            
        </div>
        
    </div>
</div>

