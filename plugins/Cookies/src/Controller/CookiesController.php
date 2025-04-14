<?php
declare(strict_types=1);

namespace Cookies\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use Cake\ORM\Query;


class CookiesController extends AppController
{
	public string | bool $layout;
	
	public function save() {
		
		if(!empty($this->request->getData('Cookie'))) {
			$active_cookie_ids = array_keys($this->request->getData('Cookie'));			
			$date = time();
			$readable_date = date("d/m/Y H:i:s");		
			
			$preferences = array();		
			$preferences['saved'] = true;
			$preferences['readable_date'] = $readable_date;
			$preferences['date'] = $date;
			$preferences['list'] = array();
			
			$cookieAliases = array();
			
			$cookies = $this->Cookies->find()
							->where(['Cookies.published' => true])
							->all()
							->toArray();			
			
			foreach($cookies as $cookie) {
				if(in_array($cookie->id, $active_cookie_ids)) {
					$preferences['list'][$cookie->id] = true;
					$cookieAliases[$cookie->alias] = true;
				} else {
					// cookie tecnici sempre attivi
					if($cookie->cookie_type_id == 1) {
						$preferences['list'][$cookie->id] = true;
						$cookieAliases[$cookie->alias] = true;
					} else {
						$preferences['list'][$cookie->id] = false;
						$cookieAliases[$cookie->alias] = false;						
					}
				}
			}
						
			$time = 86400 * 365 * 10; // 10 anni
			setcookie('cookie_preferences', json_encode($preferences), time() + $time, "/");
						
			$log = $this->request->clientIp() . ' - ' . $_SERVER['HTTP_USER_AGENT'] . ' - ' . json_encode($this->request->getData());
			Log::info($log, 'cookie_preferences');
		} else {
			$this->denyAll();
		}

		echo json_encode($cookieAliases);		
		die();		
	}
	
	public function acceptAll() {
		
		$date = time();
		$readable_date = date("d/m/Y H:i:s");		
		
		$preferences = array();		
		$preferences['saved'] = true;
		$preferences['readable_date'] = $readable_date;
		$preferences['date'] = $date;
		$preferences['list'] = array();
		
		$cookieAliases = array();
		
		$cookieTypes = $this->Cookies->CookieTypes->find()
								->contain([
									'Cookies' => function (Query $query) {
										return $query->where(['Cookies.published' => true])->order(['Cookies.title' => 'ASC']);
									}
								])
								->order(['CookieTypes.id' => 'ASC'])
								->all()
								->toArray();
		
		if(!empty($cookieTypes)){
			foreach($cookieTypes as $cookieType) {
				if(!empty($cookieType->cookies)) {
					foreach($cookieType->cookies as $cookie) {
						$preferences['list'][$cookie->id] = true;
						$cookieAliases[$cookie->alias] = true;
					}
				}	
			}
		}
		
		$time = 86400 * 365 * 10; // 10 anni
		setcookie('cookie_preferences', json_encode($preferences), time() + $time, "/");
				
		$log = $this->request->clientIp() . ' - ' . $_SERVER['HTTP_USER_AGENT'] . ' - ' . 'PREFERENZE: ' . json_encode($preferences);
		Log::info($log, 'cookie_preferences');
		
		echo json_encode($cookieAliases);
		die();		
	}
	
	public function denyAll() {	
	
		$date = time();
		$readable_date = date("d/m/Y H:i:s");		
		
		$preferences = array();		
		$preferences['saved'] = true;
		$preferences['readable_date'] = $readable_date;
		$preferences['date'] = $date;
		$preferences['list'] = array();
		
		$cookieAliases = array();
		
		$cookieTypes = $this->Cookies->CookieTypes->find()
						->contain([
							'Cookies' => function (Query $query) {
								return $query->where(['Cookies.published' => true])->order(['Cookies.title' => 'ASC']);
							}
						])
						->order(['CookieTypes.id' => 'ASC'])
						->all()
						->toArray();
		
		if(!empty($cookieTypes)){
			foreach($cookieTypes as $cookieType) {
				if(!empty($cookieType->cookies)) {
					foreach($cookieType->cookies as $cookie) {						
						// cookie tecnici sempre attivi
						if($cookieType->id == 1) {
							$preferences['list'][$cookie->id] = true;
							$cookieAliases[$cookie->alias] = true;
						} else {
							$preferences['list'][$cookie->id] = false;
							$cookieAliases[$cookie->alias] = false;	
						}
					}
				}	
			}
		}
		
		$time = 86400 * 365 * 10; // 10 anni
		setcookie('cookie_preferences', json_encode($preferences), time() + $time, "/");
		
		$log = $this->request->clientIp() . ' - ' . $_SERVER['HTTP_USER_AGENT'] . ' - ' . 'PREFERENZE: ' . json_encode($preferences);
		Log::info($log, 'cookie_preferences');
		
		echo json_encode($cookieAliases);
		die();		
	}
	
	public function keepExisting() {	
		
		$cookie_preferences = $_COOKIE['cookie_preferences'];
		
		// se ci sono delle preferenze impostate teniamo quelle, altrimenti si tratta della prima chiusura del popup e quindi equivale a negare tutti i cookie facoltativi (situazione di default)
		if(empty($cookie_preferences)) {
			$this->denyAll();
		}
		die();		
	}
	
	public function reload() {
		$this->layout = false;		
	}
   
}
