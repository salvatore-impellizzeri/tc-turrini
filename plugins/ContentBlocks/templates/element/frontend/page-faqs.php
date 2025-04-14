<?php

use Cake\Datasource\FactoryLocator;

$faqs = FactoryLocator::get('Table')->get('Faqs.Faqs')->findByPublished(true)->all();
if(empty($faqs->count())) return;
?>

<div class="page-faqs page-section">

    <div class="page-faqs__intro">

        <?php if (!empty($item->title_2)) : ?>
            <h2 class="page-faqs__title"><?= $item->title_2 ?></h2>
        <?php endif; ?>

        <?php if (!empty($item->text_1)) : ?>
            <div class="page-faqs__content"><?= $item->text_1 ?></div>
        <?php endif; ?>

    </div>
    
    <div class="page-faqs__list">
        <?php foreach ($faqs as $faq) : ?>
            <?= $this->element('faq', ['faq' => $faq]); ?>
        <?php endforeach; ?>
    </div>
    
</div>