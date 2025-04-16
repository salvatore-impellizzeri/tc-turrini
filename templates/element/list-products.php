<div class="list-products">
    <div class="list-products__background">
        <img src="<?= $background ?>" alt="background">
        <h1 class="title-primary">
            <?= $title ?>
        </h1>     
    </div>  
    
    <div class="container-l m-auto">
        <div class="list-products__items">
        <?php
            $total = count($items);
            foreach ($items as $index => $item) {
                // Se è il 3°, 6°, 9°, ecc. (ogni terzo elemento)
                $isLastInRow = (($index + 1) % 3 === 0);

                // Se è uno degli ultimi elementi e siamo a fine lista
                if (!$isLastInRow && $index === $total - 1) {
                    $isLastInRow = true;
                }

                $extraClass = $isLastInRow ? 'last-in-row' : '';
            ?>
                <?= $this->element('product-preview', [
                    'label' => $item['label'],
                    'img' => $item['img'],
                    'url' => $item['url'],
                    'extraClass' => $extraClass,
                ]); ?>
        <?php } ?>
        </div>
    </div>
</div>