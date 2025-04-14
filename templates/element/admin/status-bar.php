<?php
extract($options);
?>

<div class="status-bar <?php if(!empty($pageVar)) echo $pageVar ?>">
    <div class="status-bar__left">
        <?php echo $this->Backend->materialIcon($icon, ['class' => 'status-bar__icon']) ?>

        <?php if (!empty($pathway)): ?>
            <div class="status-bar__pathway">
                <?php if (!is_array($pathway)): ?>
                    <span class="status-bar__title"><?= $pathway ?></span>
                <?php else: ?>
                    <?php
                    foreach ($pathway as $crumb){
                        $tag = !empty($crumb['url']) ? 'a' : 'span';
                        $options = !empty($crumb['url']) ? ['href' => $this->Url->build($crumb['url'])] : [];
                        echo $this->Html->tag($tag, $crumb['title'], $options);
                    } ?>
                <?php endif; ?>

            </div>
        <?php endif; ?>


    </div>

    <?php  if ($this->request->getParam('action') == 'edit'): ?>
        <div class="status-bar__language">
            <span><?php echo __d('admin', 'editing in') ?></span>
            <div class="status-bar__language-active"><?= $this->request->getQuery('lang', DEFAULT_LANGUAGE) ?></div>
        </div>
    <?php endif; ?>
</div>
