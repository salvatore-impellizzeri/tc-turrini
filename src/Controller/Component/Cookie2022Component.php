<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\I18n\I18n;
use Cake\Datasource\FactoryLocator;
use Cake\Controller\Component\Controller;


class Cookie2022Component extends Component
{
	

	public function initialize(array $config): void
	{	    
			
		// rende disponibili le preferenze relative ai cookie impostate dall'utente
		$cookie_preferences = null;
		if(!empty($_COOKIE['cookie_preferences'])) {
			$cookie_preferences = json_decode($_COOKIE['cookie_preferences'], true);
			$this->getController()->set('cookie_preferences', $cookie_preferences);	
		}
		
		// rende disponibile la lista (id => alias) dei cookie utilizzati attivamente dal sito per i controlli in frontend			
		$cookies = FactoryLocator::get('Table')->get('Cookies.Cookies')->find('list', keyField: 'id', valueField: 'alias')			
			->where([
				'published' => true
			])
			->all()			
			->toArray();

		$this->getController()->set('cookies', $cookies);
						
		// rende disponibile la lista degli alias dei cookie accettati dall'utente
		$userCookies = array();
		if(!empty($cookies) && !empty($cookie_preferences['list'])){
			foreach($cookie_preferences['list'] as $cookie_id => $is_active) {
				if($is_active && in_array($cookie_id, array_keys($cookies))) {
					$userCookies[$cookies[$cookie_id]] = true;
				}
			}
		}
		
		$this->getController()->set('userCookies', $userCookies);		
		
    }
	
}
