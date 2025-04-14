<?php if (empty($label)) return; ?>

<?php if (!empty($url)) : ?>
    <a class="cta <?= $extraClass ?? '' ?>" href="<?= $url ?>">
        <span class="cta__label">
            <?= $label ?>
        </span>
        <?php if (!empty($icon)) : ?>
            <span class="cta__icon">
                <?= $this->Frontend->svg($icon) ?>
            </span>
        <?php endif; ?>
    </a>
<?php else : ?>
    <button class="cta <?= $extraClass ?? '' ?>" type="submit">
        <span class="cta__label">
            <?= $label ?>
        </span>
        <?php if (!empty($icon)) : ?>
            <span class="cta__icon">
                <?= $this->Frontend->svg($icon) ?>
            </span>
        <?php endif; ?>
    </button>
<?php endif; ?>