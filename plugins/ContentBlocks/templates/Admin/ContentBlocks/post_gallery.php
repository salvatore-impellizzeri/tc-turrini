<?php
$this->extend('/Admin/ContentBlocks/common');
?>

<?= $this->Form->create($item) ?>

    <fieldset class="input-group">
        <?php
        echo $this->Form->control('title', ['extraClass' => 'span-12', 'label' => __dx($po, 'admin', 'title')]); // titolo blocco

        echo $this->element('admin/uploader/gallery', [
            'scope' => 'gallery',
            'title' => 'Gallery di immagini',
            'video' => true
        ]);
        ?>
    </fieldset>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
