<div class="list">
    <div class="font-23 fadeFromTop" data-animated>
        <?= $title ?>
    </div>
    <hr class="hr hr--blue borderWidth" data-animated>
    <ul>
        <?php foreach ($items as $item): ?>
            <li class="fadeFromLeft" data-animated>
                <div class="list__big">
                    <?= $this->Frontend->svg('icons/list.svg'); ?>
                    <span class="font-168">
                        <?= $item['big'] ?>
                    </span>
                </div>
                <p class="container-text font-18 fw-light">
                    <?= $item['text'] ?>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
</div>