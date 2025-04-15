<?php if (empty($url)) : ?>
    <button class="cta" type="submit">
        <?php if (!empty($icon)) : ?>
            <span class="button <?= $extraClass ?? '' ?>">
                <?= $this->Frontend->svg($icon) ?>
            </span>
        <?php endif; ?>
        <?php if (!empty($label)) : ?>
            <span class="cta__label">
                <?= $label ?>
            </span>
        <?php endif; ?>
    </button>
<?php else : ?>
    <a class="cta" href="<?= $url ?>">
        <?php if (!empty($icon)) : ?>
            <span class="button <?= $extraClass ?? '' ?>">
                <?= $this->Frontend->svg($icon) ?>
            </span>
        <?php endif; ?>
        <?php if (!empty($label)) : ?>
            <span class="cta__label <?= $labelClass ?? '' ?>">
                <?= $label ?>
            </span>
        <?php endif; ?>
    </a>
<?php endif; ?>
