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

<div class="alert alert--warning ">

	<div class="alert__heading">
		<div close-alert>
			<span class="material-symbols-rounded">close</span>
		</div>
		<span class="material-symbols-rounded alert__heading__icon">warning_amber</span>
		<div class="alert__heading__message">
			<div class="alert__heading__message--top">
				Qualcosa è andato storto!
			</div>
			<div class="alert__heading__message--bottom">
				<?= $message ?>
			</div>
		</div>

	</div>
	<div class="alert__description"></div>
</div>
