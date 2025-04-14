<?php
$this->extend('/Admin/ContentBlocks/common');
?>

<?= $this->Form->create($item) ?>

    <fieldset class="input-group">
        <?php
        echo $this->Form->control('title', ['extraClass' => 'span-12', 'label' => __dx($po, 'admin', 'title')]); // titolo blocco
        echo $this->Form->editor('text_1', ['extraClass' => 'span-12', 'label' => 'Testo']); 
        ?>
    </fieldset>


<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
