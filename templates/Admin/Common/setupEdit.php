<?php
$this->extend('/Admin/Common/setup');

$controlsDefaults = [
    'tableControls' => false,
    'actions' => [],
];
$controlsSettings = array_merge($controlsDefaults, $controlsSettings ?? []);
?>

<?php echo $this->element('admin/controls', ['options' => $controlsSettings]) ?>
<?= $this->fetch('content') ?>
