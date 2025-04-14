<?php
$this->extend('/Admin/Common/view');
?>

<div id="client" class="tabs" data-tabs>
    <?= $this->Backend->tabsHeader([
        [
            'title' => __d('admin', 'tabs details'),
            'icon' => 'visibility',
            'hash' => 'details'
        ],
        [
            'title' => __d('admin', 'tabs orders'),
            'icon' => 'shopping_bag',
            'hash' => 'orders'
        ],
        [
            'title' => __d('admin', 'tabs subscriptions'),
            'icon' => 'card_membership',
            'hash' => 'subscriptions'
        ],
    ]); ?>
    <div class="tabs__content">
        <div class="tabs__tab" data-tab="details">
			
			<div class="block">
				<label><?php echo __dx($po, 'admin', 'client infos'); ?></label>
				<div class="details">
					<div><b><?php echo __dx($po, 'admin', 'fullname') ?>:</b> <?php echo $item->fullname ?? '-' ?></div>
					<div><b><?php echo __dx($po, 'admin', 'email') ?>:</b> <?php echo $item->email ?? '-' ?></div>
					<div><b><?php echo __d('admin', 'created') ?>:</b> <?php echo $item->created ?? '-' ?></div>
					<div><b><?php echo __dx($po, 'admin', 'login type') ?>:</b> <?php echo $item->login_type ?? '-' ?></div>
					<div><b><?php echo __dx($po, 'admin', 'guest') ?>:</b> <?php echo !empty($item->guest) ? __d('admin', 'yes') : __d('admin', 'no'); ?></div>
					<div><b><?php echo __dx($po, 'admin', 'reward') ?>:</b> <?php echo !empty($item->reward) ? __d('admin', 'yes') : __d('admin', 'no'); ?></div>
					<div><b><?php echo __dx($po, 'admin', 'invitations') ?>:</b> <?php echo $item->successful_invitations; ?></div>
					<div><b><?php echo __dx($po, 'admin', 'is company') ?>:</b> <?php echo !empty($item->is_company) ? __d('admin', 'yes') : __d('admin', 'no'); ?></div>
					<div><b><?php echo __dx($po, 'admin', 'newsletter') ?>:</b> <?php echo !empty($item->newsletter) ? __d('admin', 'yes') : __d('admin', 'no'); ?></div>
					<div><b><?php echo __dx($po,'admin', 'locale') ?>:</b> <?php echo $item->locale ?? '-' ?></div>
					<div><b><?php echo __dx($po,'admin', 'birth date') ?>:</b> <?php echo $item->birth_date ?? '-' ?></div>
					<div><b><?php echo __dx($po,'admin', 'affiliation code') ?>:</b> <?php echo !empty($item->affiliation_code) ? $item->affiliation_code : '-' ?></div>
					<div><b><?php echo __dx($po,'admin', 'profiling questionnaire sent') ?>:</b> <?php echo !empty($item->profiling_questionnaire_sent) ? __d('admin', 'yes') : __d('admin', 'no'); ?></div>
					<div><b><?php echo __dx($po,'admin', 'profiling questionnaire answered') ?>:</b> <?php echo !empty($item->profiling_questionnaire_answered) ? __d('admin', 'yes') : __d('admin', 'no'); ?></div>
				</div>
			</div>
			
			<div class="block">
				<label><?php echo __dx($po, 'admin', 'shipping addresses'); ?></label>
				<table class="table">
					<thead>
						<th>
							<?php echo __dx($po, 'admin', 'full address') ?>
						</th>
						<th>
							<?php echo __dx($po, 'admin', 'is default') ?>
						</th>
					</thead>
					<tbody>
					<?php if(!empty($item->client_shipping_addresses)) { ?>
						<?php foreach($item->client_shipping_addresses as $i => $address){ ?>
						<tr>
							<th scope="row">
								<?= $address->full_address ?>
							</th>
							<td>
								<?= !empty($address->is_default) ? __d('admin', 'yes') : __d('admin', 'no') ?>
							</td>
						</tr>
						<?php } ?>					
					<?php } else { ?>
						<tr><td colspan="100" class="table__empty"><?= __d('admin', 'empty table') ?></td></tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			
			<div class="block">
				<label><?php echo __dx($po, 'admin', 'billing addresses'); ?></label>
				<table class="table">
					<thead>
						<th>
							<?php echo __dx($po, 'admin', 'full address') ?>
						</th>
						<th>
							<?php echo __dx($po, 'admin', 'is default') ?>
						</th>
					</thead>
					<tbody>
					<?php if(!empty($item->client_billing_addresses)) { ?>
						<?php foreach($item->client_billing_addresses as $i => $address){ ?>
						<tr>
							<th scope="row">
								<?= $address->full_address ?>
							</th>
							<td>
								<?= !empty($address->is_default) ? __d('admin', 'yes') : __d('admin', 'no') ?>
							</td>
						</tr>
						<?php } ?>					
					<?php } else { ?>
						<tr><td colspan="100" class="table__empty"><?= __d('admin', 'empty table') ?></td></tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			
        </div>

        <div class="tabs__tab" data-tab="orders">
			<div class="block">
				<label><?php echo __dx('orders', 'admin', 'orders'); ?></label>
				<table class="table">
					<thead>
						<th>
							<?php echo __dx('orders', 'admin', 'nr') ?>
						</th>
						<th>
							<?php echo __dx('orders', 'admin', 'order date') ?>
						</th>
						<th>
							<?php echo __dx('orders', 'admin', 'order total') ?>
						</th>
						<th>
							<?php echo __dx('orders', 'admin', 'order status') ?>
						</th>
					</thead>
					<tbody>
					<?php if(!empty($item->orders)) { ?>
						<?php foreach($item->orders as $i => $order){ ?>
						<tr>
							<td>
								<?= $order->nr ?>
							</td>
							<th scope="row">
								<?= $this->Html->link(!empty($order->payment_date) ? $order->payment_date : $order->confirmation_date , '/admin/orders/view/' . $order->id ) ?>
							</th>
							<td>
								<?= $order->total ?>
							</td>
							<td>
								<?= $order->order_status->title ?>
							</td>
						</tr>
						<?php } ?>					
					<?php } else { ?>
						<tr><td colspan="100" class="table__empty"><?= __d('admin', 'empty table') ?></td></tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
        </div>

        <div class="tabs__tab" data-tab="subscriptions">
			<div class="block">
				<label><?php echo __dx('subscriptions', 'admin', 'subscriptions'); ?></label>
				<table class="table">
					<thead>
						<th>
							<?php echo __dx('subscriptions', 'admin', 'nr') ?>
						</th>
						<th>
							<?php echo __dx('subscriptions', 'admin', 'title') ?>
						</th>
						<th>
							<?php echo __dx('subscriptions', 'admin', 'gift') ?>
						</th>						
						<th>
							<?php echo __dx('subscriptions', 'admin', 'active') ?>
						</th>
						<th>
							<?php echo __dx('subscriptions', 'admin', 'activation date') ?>
						</th>
						<th>
							<?php echo __dx('subscriptions', 'admin', 'expiration date') ?>
						</th>
						<th>
							<?php echo __dx('subscriptions', 'admin', 'cancellation date') ?>
						</th>
					</thead>
					<tbody>
					<?php if(!empty($item->subscriptions)) { ?>
						<?php foreach($item->subscriptions as $i => $subscription){ ?>
						<tr>
							<td>
								<?= $subscription->nr ?>
							</td>
							<th scope="row">
								<?= $this->Html->link($subscription->title , '/admin/subscriptions/view/' . $subscription->id ) ?>
							</th>
							<td>
								<?= !empty($subscription->gift) ? __d('admin', 'yes') : __d('admin', 'no')  ?>
							</td>
							<td>
								<?= !empty($subscription->active) ? __d('admin', 'yes') : __d('admin', 'no')  ?>
							</td>
							<td>
								<?= $subscription->activation_date ?? '-' ?>
							</td>
							<td>
								<?= $subscription->expiration_date ?? '-' ?>
							</td>
							<td>
								<?= $subscription->cancellation_date ?? '-' ?>
							</td>
						</tr>
						<?php } ?>					
					<?php } else { ?>
						<tr><td colspan="100" class="table__empty"><?= __d('admin', 'empty table') ?></td></tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
        </div>
    </div>
</div>
