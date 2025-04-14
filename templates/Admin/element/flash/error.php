<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="alert alert--error">

	<div class="alert__heading">
		<div close-alert="">
			<span class="material-symbols-rounded">close</span>
		</div>
		<span class="material-symbols-rounded alert__heading__icon">error_outline</span>
		<div class="alert__heading__message">
			<div class="alert__heading__message--top">
				<?= $message ?>
			</div>
			<!--<div class="alert__heading__message--bottom">
				Ricontrolla i campi seguenti
			</div>-->
		</div>

	</div>
	<?php // non riesco a beccare il .po corretto ?>
	<?php /* ?>
	<div class="alert__description">
		<ul>
			<?php if(!empty($params['fields'])): ?>
				<?php foreach ($params['fields'] as $field): ?>
					<li><?php echo h(__d('admin', $field)); ?></li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
	<?php */ ?>
</div>
