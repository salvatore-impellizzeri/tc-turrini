<?php 
if(!empty($order)): 
$taxable = $order->order_billing_address->taxable ?? true;
?>
<div class="cart">
	<?php foreach($order->order_products as $item){ ?>
	<div class="row product">		
		<div class="title">
			<?php $productTitle = !empty($item->sku) ? $item->sku . ' - ' . $item->title : $item->title; ?>
			<b><?php echo $productTitle ?></b>
		</div>
		<div class="qty">
			<?php echo $item->quantity ?>
		</div>
		<div class="unitPrice">
			<span class="label"><?php echo __dx('orders', 'admin', 'price') ?>:</span>
			<span class="price"><?php echo !empty($item->discounted_price) ? $this->Frontend->formatPrice($item, 'discounted_price', $taxable) : $this->Frontend->formatPrice($item, 'price', $taxable); ?></span>
		</div>
		<div class="total">
			<?php echo __dx('orders', 'admin', 'subtotal') ?>:
			<span class="price"><?php echo $this->Frontend->formatPrice($item, 'subtotal_price', $taxable); ?></span>
		</div>
	</div>
	<?php } ?>
	<?php foreach($order->subscriptions as $item){ ?>
	<div class="row product">		
		<div class="title">
			<b><?php echo $item->title ?></b>
		</div>
		<div class="qty">1</div>
		<div class="unitPrice">
			<span class="label"><?php echo __dx('orders', 'admin', 'price') ?>:</span>
			<span class="price"><?php echo $this->Frontend->formatPrice($item, 'subtotal_price', $taxable); ?></span>
		</div>
		<div class="total">
			<?php echo __dx('orders', 'admin', 'subtotal') ?>:
			<span class="price"><?php echo $this->Frontend->formatPrice($item, 'subtotal_price', $taxable); ?></span>
		</div>
	</div>
	<?php } ?>
	<div class="cartFooter">
		<div class="cartResume">
			<?php if(!empty($order->coupon_discount) && $order->coupon_discount != 0 ): ?>
				<div>
					<?php echo __dx('orders', 'admin', 'coupon discount') ?>
					<span class="price">-<?php echo $this->Frontend->formatPrice($order, 'coupon_discount', $taxable); ?></span>
				</div>
			<?php endif; ?>

			<?php if(!empty($order->payment_method_variation) && $order->payment_method_variation != 0 ){ ?>
			<div>
				<?php echo __dx('orders', 'admin', 'payment method variation') ?>
				<span class="price"><?php echo $this->Frontend->formatPrice($order, 'payment_method_variation', $taxable); ?></span>
			</div>
			<?php } ?>
			<?php if(!empty($order->shipping_cost) && $order->shipping_cost != 0 ){ ?>
			<div>
				<?php echo __dx('orders', 'admin', 'shipping cost') ?>
				<span class="price"><?php echo $this->Frontend->formatPrice($order, 'shipping_cost', $taxable); ?></span>
			</div>
			<?php } ?>
			<div class="cartTotal big">
				<strong><?php echo __dx('orders', 'admin', 'total') ?></strong>
				<span class="price"><?php echo $this->Frontend->formatPrice($order, 'total', $taxable); ?></span>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
