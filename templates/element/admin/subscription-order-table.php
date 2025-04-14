<?php if(!empty($order)): ?>
<div class="cart">
	<?php foreach($order->subscription_order_products as $item){ ?>
	<div class="row product">		
		<div class="title">
			<?php $productTitle = !empty($item->sku) ? $item->sku . ' - ' . $item->title : $item->title; ?>
			<b><?php echo $productTitle ?></b>
		</div>
		<div class="qty">
			<?php echo $item->quantity ?>
		</div>
		<div class="reward">
			<?php 
				if(!empty($item->friend_reward)) echo __dx('subscriptions', 'admin', 'friend reward');
				elseif(!empty($item->loyalty_reward)) echo __dx('subscriptions', 'admin', 'loyalty reward');
				else echo __dx('subscriptions', 'admin', 'monthly bottle');
			?>
		</div>
	</div>
	<?php } ?>
	<div class="cartFooter">
		<div class="cartResume">
			<div class="cartTotal big">
				<strong><?php echo __dx('subscriptions', 'admin', 'total') ?></strong>
				<span class="price"><?php echo $this->Number->currency($order->total, 'EUR') ?></span>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
