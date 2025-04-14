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

<div class="alert alert--success">

	<div class="alert__heading">
		<div close-alert="">
			<span class="material-symbols-rounded">close</span>
		</div>
		<div class="">
			<span class="material-symbols-rounded alert__heading__icon">check_circle_outline</span>
		</div>
		<div class="alert__heading__message">
			<div class="alert__heading__message--top">
				<?= $message ?>
			</div>
			<div class="alert__heading__message--bottom">
			</div>
		</div>

	</div>
	<div class="alert__description"></div>
</div>
