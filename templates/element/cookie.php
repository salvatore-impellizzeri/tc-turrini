<?php

	use Cake\Datasource\FactoryLocator;
	use Cake\ORM\Query;

	$cookiePolicyLastModified = FactoryLocator::get('Table')->get('Policies.Policies')->get(2);
	$cookiePolicyLastModified = strtotime($cookiePolicyLastModified->modified->toDateTimeString());

	$showCookie = true;
	$cookie_preferences = null;

	if(!empty($_COOKIE['cookie_preferences'])) {
		$cookie_preferences = json_decode($_COOKIE['cookie_preferences'], true);
		if($cookiePolicyLastModified < $cookie_preferences['date']) {
			$showCookie = false;
		}
	}

	$active_cookies = array();
	if(!empty($cookie_preferences['list'])) {
		foreach($cookie_preferences['list'] as $cookie_id => $is_active) {
			if($is_active) {
				$active_cookies[] = $cookie_id;
			}
		}
	}

	$cookieTypes = FactoryLocator::get('Table')->get('Cookies.CookieTypes')->find()
						->contain([
							'Cookies' => function (Query $query) {
								return $query->where(['Cookies.published' => true])->order(['Cookies.position' => 'ASC']);
							}
						])
						->order(['CookieTypes.id' => 'ASC'])
						->all()
						->toArray();

	$analyticsCookies = $marketingCookies = false;
	$extraCookies = array();
	foreach($cookieTypes as $cookieType) {
		if(!empty($cookieType['Cookie'])) {
			if($cookieType['CookieType']['id'] == 2) {
				if(empty($analyticsCookies)) {
					$analyticsCookies = strtolower($cookieType['CookieType']['title']);
					$extraCookies[] = $analyticsCookies;
				}
			}
			if($cookieType['CookieType']['id'] == 3) {
				if(empty($marketingCookies)) {
					$marketingCookies = strtolower($cookieType['CookieType']['title']);
					$extraCookies[] = $marketingCookies;
				}
			}
		}
	}
	if(!empty($extraCookies)) {
		$extraCookiesString = implode(' ' . __d('cookies', 'e') . ' ', $extraCookies);
	}
?>
<?php
	$disclaimerClass = empty($showCookie) ? 'hidden' : '';
?>
<?php if(!empty($showCookie) || !empty($forced)): ?>
<div id="cookiedisclaimer2022" class="<?php echo $disclaimerClass; ?>" data-lenis-prevent>
	<div class="inner_disclaimer">
		<h3><?php echo __d('cookies', 'cookie title'); ?></h3>
		<p><?php echo __d('cookies', 'cookie disclaimer');?> <?php if(!empty($extraCookiesString)) echo __d('cookies', 'more cookie {0}', $extraCookiesString); ?></p>
		<div class="cookie_options">
			<?php echo $this->Html->tag('span', __d('cookies', 'manage preferences'), array('id' => 'cookieselect')); ?>
			<?php echo $this->Html->tag('span', __d('cookies', 'deny'), array('id' => 'cookieko')); ?>
			<?php echo $this->Html->tag('span', __d('cookies', 'allow all'), array('id' => 'cookieok')); ?>
		</div>
		<div id="cookie_preferences">
			<?php
				if(!empty($cookie_preferences['readable_date'])){
					echo $this->Html->tag('p', __d('cookies', 'saved the {0}', $cookie_preferences['readable_date']));
				}
			?>

			<?php $form = FactoryLocator::get('Table')->get('Cookies.Cookies')->newEmptyEntity(); ?>

			<?php echo $this->Form->create($form, array('id' => 'cookieForm', 'url' => array('plugin' => 'cookies', 'controller' => 'cookies', 'action' => 'save'))); ?>
			<?php foreach($cookieTypes as $cookieType): ?>
				<?php if(!empty($cookieType->cookies) || $cookieType->id == 1): ?>
				<div class="cookie_type">
					<div class="cookie_flex">
						<h5><?php echo $cookieType->title; ?></h5>
						<label class="toggle">
							<?php $tecnici = $cookieType->id == 1 ? 'checked disabled' : ''; ?>
							<input class="toggle-checkbox maintoggle" type="checkbox" <?php echo $tecnici; ?> data-type="ct_<?php echo $cookieType->id; ?>">
							<div class="toggle-switch"></div>
						</label>
					</div>
					<div class="cookie_type_desc">
						<?php echo $cookieType->text; ?>
					</div>
					<div class="cookie_type_list">
						<?php if(!empty($cookieType->cookies)): ?>
							<?php foreach($cookieType->cookies as $cookie): ?>
							<div class="cookie_flex">
								<h6><?php echo $cookie->title; ?></h6>
								<label class="toggle">
									<?php if(!empty($tecnici)): ?>
										<?php $checked = $tecnici; ?>
									<?php else: ?>
										<?php $checked = in_array($cookie->id, $active_cookies) ? 'checked' : ''; ?>
									<?php endif; ?>
									<input class="toggle-checkbox subtoggle" <?php echo $checked; ?> type="checkbox" name="Cookie[<?php echo $cookie->id; ?>]" value="1" data-type="ct_<?php echo $cookieType->id; ?>">
									<div class="toggle-switch"></div>
								</label>
							</div>
							<div class="cookie_desc">
								<?php echo $cookie->text; ?>
							</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php echo $this->Form->button(__d('cookies', 'save preferences')); ?>
		</div>
		<?php echo $this->Frontend->seolink(__d('cookies', 'read more'), '/policies/view/2'); ?>
		<span id="cookie_close">x</span>
	</div>
</div>
<?php endif; ?>

<?php echo $this->Html->scriptStart(array('block' => 'scriptBottom', 'inline' => false)); ?>
$(function(){

	$(document).on('click', '#cookie_reload', function(){
		$.ajax({
			cache: false,
			async:   false,
			url:   '<?php echo $this->Frontend->url('/cookies/reload'); ?>',
			beforeSend : function(){
				$('#cookiedisclaimer2022').remove();
			},
			success: function(result) {
				$('body').append(result);
				setTimeout(function(){
					$('#cookiedisclaimer2022').removeClass('hidden');
				}, 50)
			}
		});
	})

	// mostra preferenze cookie
	$(document).on('click', '#cookieselect', function(){
		$('#cookie_preferences').toggleClass('show');
		$('.subtoggle').trigger('change');
	})

	// accetta tutti i cookie
	$(document).on('click', '#cookieok', function(){					
		$.ajax({url: '/cookies/acceptAll'}).done(function(resp){ 
			$('#cookiedisclaimer2022').addClass('hidden'); 
			let response = JSON.parse(resp);
			
			let gadsConsent = 'denied';
			let gaConsent = 'denied';
			
			if (response.hasOwnProperty('gads')) {
				gadsConsent = Boolean(response.gads) == true ? 'granted' : 'denied';					  		  
			}
			if (response.hasOwnProperty('ga')) {
				gaConsent = Boolean(response.ga) == true ? 'granted' : 'denied';					  			  
			}
			if(window.gtag != undefined) {
				gtag('consent', 'update', {
					'ad_user_data': gadsConsent,
					'ad_personalization': gadsConsent,
					'ad_storage': gadsConsent,
					'analytics_storage': gaConsent
				  });
			} 
		})
	})

	// rifiuta tutti i cookie
	$(document).on('click', '#cookieko', function(){					
		$.ajax({url: '/cookies/denyAll'}).done(function(resp){ 
			$('#cookiedisclaimer2022').addClass('hidden'); 
			let response = JSON.parse(resp);
			
			let gadsConsent = 'denied';
			let gaConsent = 'denied';
			
			if (response.hasOwnProperty('gads')) {				  				
				gadsConsent = Boolean(response.gads) == true ? 'granted' : 'denied';					  				  
			}
			if (response.hasOwnProperty('ga')) {				  
				gaConsent = Boolean(response.ga) == true ? 'granted' : 'denied';					  		  
			}
			if(window.gtag != undefined) {
				gtag('consent', 'update', {
					'ad_user_data': gadsConsent,
					'ad_personalization': gadsConsent,
					'ad_storage': gadsConsent,
					'analytics_storage': gaConsent
				  });
			} 
		})
	})

	// chiudi e mantieni attuali
	$(document).on('click', '#cookie_close', function(){
		$('#cookieForm').trigger('submit');
	})

	// salvataggio preferenze	
	$(document).on('submit', '#cookieForm', function(){
		
		$.ajax({
			cache: false,
			async: false,
			type : 'POST',
			url:   $(this).attr('action'),
			data : $(this).serialize(),
			success: function(resp) {
				$('#cookiedisclaimer2022').addClass('hidden');
				let response = JSON.parse(resp);
				
				let gadsConsent = 'denied';
				let gaConsent = 'denied';
			
				if (response.hasOwnProperty('gads')) {
					gadsConsent = Boolean(response.gads) == true ? 'granted' : 'denied';
				}
				if (response.hasOwnProperty('ga')) {
					gaConsent = Boolean(response.ga) == true ? 'granted' : 'denied';					
				}
				if(window.gtag != undefined) {
					gtag('consent', 'update', {
						'ad_user_data': gadsConsent,
						'ad_personalization': gadsConsent,
						'ad_storage': gadsConsent,
						'analytics_storage': gaConsent
					  });
				} 
			}
		});

		return false;
	})

	// click su toggle principale (attiva o disattiva tutti i toggle secondari)
	$(document).on('change', '.maintoggle', function(){
		var is_checked = $(this).is(':checked');
		var cookie_list = $(this).data('type');

		$('.subtoggle[data-type="' + cookie_list + '"]').prop('checked', is_checked);
	})


	// click su toggle secondario (attiva o disattiva il toggle primario)
	$(document).on('change', '.subtoggle', function(){
		var is_checked = $(this).is(':checked');
		var cookie_list = $(this).data('type');
		var atLeastOneChecked = false;

		if(is_checked) {
			$('.maintoggle[data-type="' + cookie_list + '"]').prop('checked', true);
		} else {
			$('.subtoggle[data-type="' + cookie_list + '"]').each(function(num, sibling){
				if($(sibling).is(':checked')) {
					atLeastOneChecked = true;
				}
			})
			$('.maintoggle[data-type="' + cookie_list + '"]').prop('checked', atLeastOneChecked);
		}
	})

})
<?php echo $this->Html->scriptEnd(); ?>
