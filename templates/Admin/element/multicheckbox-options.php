<?php foreach ($items as $item): 
    $extraAttr = !empty($checked) && in_array($item->id, $checked) ? ' data-checked="1"' : '';
    ?>
    <div class="multiCheckbox__option" data-value="<?= $item->id ?>" <?= $extraAttr ?>>
        <?php if (!empty($item->_title_path)): ?>
            <span class="multiCheckbox__option__label">
                <?= $item->_title_path ?>
                <span data-value-label style="display: none"><?= $item->title ?></span>
            </span>
        <?php else: ?>
            <span class="multiCheckbox__option__label" data-value-label>
                <?= $item->title ?>
            </span>
        <?php endif; ?>
        
        <span class="toggle">
            <span class="onoff"></span>
        </span>
    </div>
<?php endforeach; ?>

