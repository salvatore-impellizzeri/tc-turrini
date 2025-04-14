<?php
$this->extend('/Admin/Common/edit');

$this->set('controlsSettings', [
    'backUrl' => [
        'plugin' => $item['plugin'],
        'controller' => $item['model'],
        'action' => $item['plugin'] == 'CustomPages' ? 'edit' : 'compose',
        $item['ref']
    ]
]);
?>

<?= $this->fetch('content'); ?>
