<?php $this->extend('/Admin/Common/edit'); ?>

<?php echo $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']); ?>
    <fieldset class="input-group">
        <legend class="input-group__info">
            Generali        
        </legend>
        <?php
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-12']);
        ?>
    </fieldset>
    
    <fieldset class="input-group">
        <legend class="input-group__info">
            Impostazioni 
        </legend>
        <?php
        echo $this->Form->control('mailto', ['label' => __dx($po, 'admin', 'mailto'), 'extraClass' => 'span-6']);
        echo $this->Form->control('subject', ['label' => __dx($po, 'admin', 'admin subject'), 'extraClass' => 'span-6']);
        ?>
        
    </fieldset>

    <fieldset class="input-group">
        <legend class="input-group__info">
            Risposta automatica 
        </legend>
        <?php
        echo $this->Form->control('send_reply', ['label' => __dx($po, 'admin', 'send_reply'), 'extraClass' => 'span-2']);
        echo $this->Form->control('reply_subject', ['label' => __dx($po, 'admin', 'reply_subject')]);
        echo $this->Form->editor('reply_text', ['label' => __dx($po, 'admin', 'reply_text')]);
        ?>
        
    </fieldset>

    <?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
