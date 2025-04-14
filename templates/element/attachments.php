<?php 
if (empty($attachments)) return;
?>

<ul class="attachments">
    <?php foreach ($attachments as $attachment): ?>
        <li>
            <a href="<?= $attachment->path ?>"><?= $attachment->filename ?></a>
        </li>
    <?php endforeach; ?>
</ul>