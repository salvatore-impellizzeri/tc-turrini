<?php
$phone = $phone ?? null;
$email = $email ?? null;
$address = $address ?? null;
?>
<div class="contact-info">
    <?php if (!empty($phone)) : ?>
        <div class="contact-info__item">
            <span class="contact-info__icon">
                <?= $this->Frontend->svg('icons/phone.svg') ?>
            </span>
            <a href="tel:<?= str_replace(' ', '', $phone) ?>">
                <?= $phone ?>
            </a>
        </div>
    <?php endif; ?>
    <?php if (!empty($email)) : ?>
        <div class="contact-info__item">
            <span class="contact-info__icon">
                <?= $this->Frontend->svg('icons/email.svg') ?>
            </span>
            <a href="mailto:<?= $email ?>">
                <?= $email ?>
            </a>
        </div>
    <?php endif; ?>
    <?php if (!empty($address)) : ?>
        <div class="contact-info__item">
            <span class="contact-info__icon">
                <?= $this->Frontend->svg('icons/location.svg') ?>
            </span>
            <?= $address ?>
        </div>
    <?php endif; ?>
</div>