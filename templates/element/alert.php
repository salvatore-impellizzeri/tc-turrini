<?php
$extraClass = $extraClass ?? '';
?>

<div class="alert <?= $extraClass ?>">

    <span class="alert__close" data-close-alert></span>

    <div class="alert__message">
        <?= $message ?>
    </div>
</div>
