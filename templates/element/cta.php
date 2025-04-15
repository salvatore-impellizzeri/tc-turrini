<?php if (empty($label)) return; ?>

<?php if (!empty($url)) : ?>
    <a class="cta" href="<?= $url ?>">
        <?php if (!empty($icon)) : ?>
            <span class="button <?= $extraClass ?? '' ?>">
                <?= $this->Frontend->svg($icon) ?>
            </span>
        <?php endif; ?>
        <span class="cta__label">
            <?= $label ?>
        </span>
    </a>
<?php else : ?>
    <button class="cta" type="submit">
        <?php if (!empty($icon)) : ?>
            <span class="button <?= $extraClass ?? '' ?>">
                <?= $this->Frontend->svg($icon) ?>
            </span>
        <?php endif; ?>
        <span class="cta__label">
            <?= $label ?>
        </span>
    </button>
<?php endif; ?>