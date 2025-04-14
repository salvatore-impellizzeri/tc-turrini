<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;


use Cake\Controller\Controller;
use Cake\Collection\Collection;
use Cake\Utility\Hash;
use Cake\Event\EventInterface;
use Cake\I18n\I18n;
use Cake\Utility\Inflector;
use Cake\Http\Exception\NotFoundException;
use Cake\Core\Configure;
use Cake\Http\Cookie\Cookie;
use Cake\Log\Log;
use Cake\Routing\Router;

// serve per recuperare il modello
use Cake\Datasource\FactoryLocator;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

	public $queryContain = []; //contain all'interno di una query su index o edit

	public $Session;
	public $po;
	public $queryBuilder;
	public $loggedClient = false;
	
	public $dbTable = null;
	public $modelName = null;
	
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {

        parent::initialize();

        // $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		// Cookie
		$this->loadComponent('Cookie2022');


        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
		$this->viewBuilder()->addHelper('Form', ['templates' => 'frontend-form']);

        //serve per recuperare il modello corrente per accedere al database
		if (empty($this->tableless)) {
			$this->dbTable = FactoryLocator::get('Table')->get($this->defaultTable);

			if(strpos($this->defaultTable, ".") !== false) {
				list($pluginName, $modelName) = explode('.', $this->defaultTable);
			} else {
				$modelName = $this->defaultTable;
				$pluginName = null;
			}

			$this->queryBuilder = FactoryLocator::get('Table');
			$this->modelName = $modelName;
			$this->set('modelName', $modelName);
			$this->set('queryBuilder', $this->queryBuilder);
		}


		$this->Session = $this->request->getSession();

		//preparo il domain per le traduzioni
		$domain = $this->request->getParam('plugin') ?? $this->request->getParam('controller');
		$domain = Inflector::underscore($domain);
		$this->set('po', $domain);
		$this->po = $domain;

		//definisco se siamo in home page
		$languages = array_merge([Configure::read('Setup.default_language')], Configure::read('Setup.languages'));

		$homeLinks = ['/', '/index.php'];
		foreach ($languages as $lang) {
			$homeLinks[] = '/'.$lang.'/';
		}
		define('IS_HOME', in_array(Router::url(), $homeLinks));


    }

	public function beforeFilter(EventInterface $event) {

		if (!$this->request->is('ajax') && !empty($this->Session)) { // genera il token per le richieste non ajax
			$random_number = mt_rand(0,999999);
			$this->Session->write('token', 'token_' . $random_number);
			$this->Session->write('token_time_start', microtime(true));
		}

		if($this->request->is(['ajax'])) {
			$this->viewBuilder()->setLayout('ajax');
		}

		//passo l'utente loggato alle viste e ai controller
		$loggedClient = $this->loggedClient = $this->getLoggedClient();
		$this->set(compact('loggedClient'));

		// salva la lingua utente in un cookie
		$cookie = (new Cookie('locale'))
			->withValue(ACTIVE_LANGUAGE)
			->withExpiry(new \DateTime('+1 year'));

		$this->response = $this->response->withCookie($cookie);

	}

	public function beforeRender(EventInterface $event)
	{
		parent::beforeRender($event);

		//layout popup
		if (!empty($this->request->getQuery('popup'))) {
			$this->viewBuilder()->setLayout('popup');
		}
		
		// aggiorna scadenza cookie usato per sessioni di login
		if(!empty($this->request->getCookie('WM'))) {			
			$cookie = (new Cookie('WM'))
				->withValue($this->request->getCookie('WM'))
				->withExpiry(new \DateTime('+1 month'));
			$this->response = $this->response->withCookie($cookie);
		}

        //cookie per mostrare popup
        $showPopup = $this->Session->read('popup');
        if (empty($showPopup) || $showPopup < strtotime('-1 hour')){
            $this->set('showPopup', true);
            $this->Session->write('popup', strtotime('now'));
        }

        if (!empty($this->request->getQuery('testPopup'))){
            $this->set('showPopup', true);
        }

	}




    public function view($id = null)
    {

        if(!isset($id)) throw new NotFoundException(__('Not found'));
			
				$conditions = [$this->modelName . '.id' => $id, $this->modelName . '.published' => true];
			
		//tolgo il controllo su published per la preview
		if (!empty($this->request->getQuery('forcePreview'))) {
			$conditions = ['id' => $id];
		}
		
		$item = $this->dbTable->find()
			->contain($this->queryContain)
			->where($conditions)
			->first();

        if(empty($item)) throw new NotFoundException(__('Not found'));

        $this->set(compact('item'));
		$this->setSeoFields($item);
		$this->setImages($item);
		$this->setAttachments($item);
    }


	public function getFlashMessage(){
		$this->viewBuilder()->setLayout('ajax');
		$this->viewBuilder()->setTemplate('/Common/flash');
	}

	// imposta le immagini
	protected function setImages($item) {

		if(!empty($item->images)) {

			/* riorganizza le immagini per scope ordinandole per position
			   in frontend la struttura di $images sarà un array
			   con lo scope come chiave e le immagini come valore */

			$images = new Collection($item->images);
			$scopedImages = $images->groupBy('scope');

			$scopedImagesSorted = $scopedImages->map(function ($scope) {
				$sortedImages = (new Collection($scope))->sortBy('position', SORT_ASC);
				return $sortedImages->toList();
			});

			$scopedImagesSorted	= $scopedImagesSorted->toArray();

			foreach($scopedImagesSorted as $scope => $images) {

				foreach($images as $key => $image) {
					$images[$key]['responsive_images'] = Hash::combine($image['responsive_images'], '{n}.device', '{n}');
				}

				if(empty($images[0]['multiple'])) {
					$scopedImagesSorted[$scope] = $images[0];
				}
			}
            $tmpImages = [];
            foreach ($scopedImagesSorted as $scope => $images) {
                if (preg_match('/^\[(\d*)\](.*)/', $scope, $match)){
                    $tmpImages[$match[1]][$match[2]] = $images;
                } else {
                    $tmpImages[$scope] = $images;
                }
            }

			$this->set('images', $tmpImages);
		}

	}

	// imposta gli allegati
	protected function setAttachments($item) {

		if(!empty($item->attachments)) {

			/* riorganizza gli allegati per scope ordinandoli per position
			   in frontend la struttura di $attachments sarà un array
			   con lo scope come chiave e i file come valore */

			$attachments = new Collection($item->attachments);
			$scopedAttachments = $attachments->groupBy('scope');

			$scopedAttachmentsSorted = $scopedAttachments->map(function ($scope) {
				$sortedAttachments = (new Collection($scope))->sortBy('position', SORT_ASC);
				return $sortedAttachments->toList();
			});

			$scopedAttachmentsSorted = $scopedAttachmentsSorted->toArray();

			foreach($scopedAttachmentsSorted as $scope => $attachments) {

				if(empty($attachments[0]['multiple'])) {
					$scopedAttachmentsSorted[$scope] = $attachments[0];
				}
			}

            $tmpAttachments = [];
            foreach ($scopedAttachmentsSorted as $scope => $attachments) {
                if (preg_match('/^\[(\d*)\](.*)/', $scope, $match)){
                    $tmpAttachments[$match[1]][$match[2]] = $attachments;
                } else {
                    $tmpAttachments[$scope] = $attachments;
                }
            }

			$this->set('attachments', $tmpAttachments);
		}

	}

	//imposta il title e i campi seo della pagina
	protected function setSeoFields($item) {
		$this->set('pageTitle', !empty($item->seotitle) ? $item->seotitle : $item->title . ' - ' . Configure::read('Setup.sitename'));
		$this->set('seoDescription', $item->seodescription ?? null);
		$this->set('seoKeywords', $item->seokeywords ?? null);
	}

	//restituisce i dati dell'utente loggato, false altrimenti oppure lo slogga se non è valido
	protected function getLoggedClient()
	{

		$client = $this->request->getAttribute('identity');
		$loggedClient = false;


		if(!empty($client) && !empty($client->get('id'))) {

			// controllo che l'utente esista e sia abilitato
			$enabledClient = FactoryLocator::get('Table')->get('Clients.Clients')->find()
				->where([
					'id' => $client->get('id'),
					'enabled' => true
				])
				->first();

			// utente eliminato, insesistente o disabilitato => fai il logout
			if(empty($enabledClient)) {
				return $this->redirect(['plugin' => 'Clients', 'controller' => 'Clients', 'action' => 'logout']);
			}
		}


        if (!empty($client)) {
            $loggedClient = [
                'id' => $enabledClient->id,
				'fullname' => $enabledClient->fullname,
				//'login_type' => $enabledClient->login_type,
                'email' => $enabledClient->email,
				'created' => $enabledClient->created,
				'locale' => $enabledClient->locale,
				'email_confirmed' => $enabledClient->email_confirmed,
				'enabled' => $enabledClient->enabled,
            ];

        }

		return $loggedClient;
	}


}
