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

namespace App\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Collection\Collection;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Event\EventInterface;
use Cake\I18n\I18n;
use Cake\Utility\Inflector;
use Cake\Core\Configure;
use Cake\Http\Exception\UnauthorizedException;
use Cake\View\JsonView;
use Cake\View\XmlView;

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

	public $Session;
	public $po;
    public $dbTable;
    public $loggedUser;

	public $actionRedirect = ['action' => 'index']; //redirect dopo un salvataggio, edit o cancellazione
	public $queryContain = []; //contain all'interno di una query su index o edit
	public $queryOrder = null; //order all'interno di una query su index
	public $queryConditions = null; //condizioni all'interno di una query su index
	public $queryFields = null; //campi selezionati nel get
    public $queryGroup = []; //group by nel get
    public $queryMatchings = []; //matching per condizioni complesse nel get belongsToMany
    public $queryNotMatchings = []; //notMatching per condizioni complesse nel get belongsToMany
    public $queryInnerJoins = []; //joins per condizioni complesse nel get belongsToMany
    public $deleteFailMessage = null; // messaggio in caso di fail del delete
    public $ignoreFilters = []; //ignora questi filtri nel get se si vogliono impostare condizioni custom nel controller specifico
	
	public function initialize(): void
	{
		parent::initialize();

		date_default_timezone_set('Europe/Rome');

		// $this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');


		// Add this line to check authentication result and lock your site
		$this->loadComponent('Authentication.Authentication');
		$this->viewBuilder()->addHelper('Form', ['templates' => 'form']);

		/*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
		//$this->loadComponent('FormProtection');

		if (empty($this->tableless)) {
			//serve per recuperare il modello anche all'interno dei Plugin
			$this->dbTable = FactoryLocator::get('Table')->get($this->defaultTable);

			if (strpos($this->defaultTable, ".") !== false) {
				list($pluginName, $modelName) = explode('.', $this->defaultTable);
			} else {
				$modelName = $this->defaultTable;
				$pluginName = null;
			}

			$this->set('modelName', $modelName);

			//setto la variabile translatable
			$isTranslatable = $this->dbTable->hasBehavior('MultiTranslatable');
		}

		$this->set('isTranslatable', !empty($isTranslatable));

		//preparo il domain per le traduzioni
		$domain = $this->request->getParam('plugin') ?? $this->request->getParam('controller');
		$domain = Inflector::underscore($domain);
		$this->set('po', $domain);
		$this->po = $domain;

		$this->Session = $this->request->getSession();


		// imposto il layout admin
		$this->viewBuilder()->setLayout('admin');

		//passo l'utente loggato alle viste
		$user = $this->Authentication->getIdentity();
		if (!empty($user)) {

			$this->loggedUser = [
				'id' => $user->get('id'),
				'group_id' => $user->get('group_id'),
				'username' => $user->get('username'),
				'email' => $user->get('email'),
			];

			$this->set('loggedUser', $this->loggedUser);
		}

        $this->deleteFailMessage = __d('admin', 'Delete error');


		define('TRANSLATION_ACTIVE', !empty(Configure::read('Setup.languages')));
        define('CURRENCY', Configure::read('Shop.currency'));
	}

	public function viewClasses(): array
	{
		return [JsonView::class];
	}


	public function index()
	{
		// ci serve per caricare la vista che poi carica in AJAX gli elementi
	}

	public function add()
	{

		$item = $this->dbTable->newEmptyEntity();

		// il salvataggio lo gestiamo solo in AJAX
		if ($this->request->is('post') && $this->request->is('ajax')) {

			$response = [];
			$forceRedirect = false;

			$item = $this->dbTable->patchEntity($item, $this->request->getData());

			if ($this->dbTable->save($item)) {
				// TODO: da settare solo se non restiamo nella stessa pagina
				$this->Flash->success(__d('admin', 'Save success'));
				$forceRedirect = Router::url(['action' => 'edit', $item->id]);
			} else {
				$this->Flash->error(
					__d('admin', 'Save error'),
					['params' => ['fields' => array_keys($item->getInvalid())]]
				);
			}

			$validationErrors = empty($item->getErrors()) ? null : $item->getErrors();

			$response = [
				'item' => $item,
				'redirect' => Router::url($this->actionRedirect),
				'errors' => $validationErrors,
				'forceRedirect' => $forceRedirect
			];

			$this->set('response', $response);
			$this->viewBuilder()->setOption('serialize', 'response');
		}

		$this->set('item', $item);
		$this->viewBuilder()->setTemplate('edit');
	}

	public function view($id = null)
	{
		$item = $this->dbTable->findById($id)->contain($this->queryContain)->first();
		if (!empty($item)) {
			$this->set('item', $item);
		} else {
			$this->Flash->error(__d('admin', 'Not found'));
			return $this->redirect($this->actionRedirect);
		}
	}

	public function edit($id = null)
	{

        $associations = $this->dbTable->associations();

		if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE && $this->dbTable->hasBehavior('MultiTranslatable')) {
			$this->dbTable->setLocale(ACTIVE_ADMIN_LANGUAGE);

            if ($associations->has('ContentBlocks') && $this->dbTable->ContentBlocks->hasBehavior('MultiTranslatable')) {
                $this->dbTable->ContentBlocks->setLocale(ACTIVE_ADMIN_LANGUAGE);
            }
		}


		$item = $this->dbTable->findById($id)->contain($this->queryContain)->first();
    

		if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE && $this->dbTable->hasBehavior('MultiTranslatable')) {
			$translatableFields = $this->dbTable->behaviors()->get('MultiTranslatable')->getConfig('fields');
			

            
            if ($associations->has('ContentBlocks')) {
                //gestione traduzioni modelli collegati
                if ($this->dbTable->ContentBlocks->hasBehavior('MultiTranslatable')) {
                    $translatableFields['content_blocks'] = $this->dbTable->ContentBlocks->behaviors()->get('MultiTranslatable')->getConfig('fields');
                }
            }
            $this->set('translatableFields', $translatableFields);
			$item->_translatableFields = $translatableFields;


		}

		if (!empty($item)) {

			// il salvataggio lo gestiamo solo in AJAX
			if ($this->request->is(['patch', 'post', 'put']) && $this->request->is('ajax')) {

				if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE && $this->dbTable->hasBehavior('MultiTranslatable')) {
					I18n::setLocale(ACTIVE_ADMIN_LANGUAGE);
				}

				if (!empty($this->request->getData('_stay'))) {
					$this->Session->write('_stay', $this->request->getData('_stay'));
				}

				$response = [];

				$this->dbTable->patchEntity($item, $this->request->getData());

				if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE && $this->dbTable->hasBehavior('MultiTranslatable')) {
					// se siamo in una traduzione, impostiamo tutti i campi traducibili a dirty, altrimenti non li salva
					foreach ($translatableFields as $field) {
                        if (is_array($field)) continue;
						$item->setDirty($field, true);
					}

                    //verifica modelli colleggati
                   
                    if ($associations->has('ContentBlocks')) {
                        //gestione traduzioni modelli collegati
                        if ($this->dbTable->ContentBlocks->hasBehavior('MultiTranslatable') && !empty($translatableFields['content_blocks']) && !empty($item->content_blocks)) {
                            $item->setDirty('content_blocks', true);
                            foreach ($item->content_blocks as $i => $contentBlock) {
                                foreach ($translatableFields['content_blocks'] as $field) {
                                    $item->content_blocks[$i]->setDirty($field, true);
                                }
                            }
                        }
                    }
				}

				if ($this->dbTable->save($item)) {
					// TODO: da settare solo se non restiamo nella stessa pagina

					if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE && $this->dbTable->hasBehavior('MultiTranslatable')) {
						I18n::setLocale(DEFAULT_LANGUAGE);
					}
					$this->Flash->success(__d('admin', 'Save success'));
				} else {
					if (ACTIVE_ADMIN_LANGUAGE != DEFAULT_LANGUAGE && $this->dbTable->hasBehavior('MultiTranslatable')) {
						I18n::setLocale(DEFAULT_LANGUAGE);
					}
					$this->Flash->error(
						__d('admin', 'Save error'),
						['params' => ['fields' => array_keys($item->getInvalid())]]
					);
				}
				$validationErrors = empty($item->getErrors()) ? null : $item->getErrors();

            
                
                if (array_key_exists('eval', $this->actionRedirect)) {
                    $actionRedirect = eval('return '.$this->actionRedirect['eval']);
                } else {
                    $actionRedirect = $this->actionRedirect;
                }

				$response = [
					'item' => $item,
					'redirect' => Router::url($actionRedirect),
					'errors' => $validationErrors
				];

				$this->set('response', $response);
				$this->viewBuilder()->setOption('serialize', 'response');
			} else {

				if (!empty($item->images)) {


					/* riorganizza le immagini per scope ordinandole per position
					   in frontend la struttura di $images sarà un array
					   con lo scope come chiave e le immagini come valore
					*/

					$images = new Collection($item->images);
					$scopedImages = $images->groupBy('scope');

                

					$scopedImagesSorted = $scopedImages->map(function ($scope) {
						$sortedImages = (new Collection($scope))->sortBy('position', SORT_ASC);
						return $sortedImages->toList();
					});
                    
					$scopedImagesSorted	= $scopedImagesSorted->toArray();

					foreach ($scopedImagesSorted as $scope => $images) {
                        
						foreach ($images as $key => $image) {
							$images[$key]['responsive_images'] = Hash::combine($image['responsive_images'], '{n}.device', '{n}');
						}

						if (empty($images[0]['multiple'])) {
							$scopedImagesSorted[$scope] = $images[0];
						}
					}

					$this->set('images', $scopedImagesSorted);
				}

				if (!empty($item->attachments)) {

					/* riorganizza gli allegati per scope ordinandole per position
					   in frontend la struttura di $attachments sarà un array
					   con lo scope come chiave e i file come valore
					*/

					$attachments = new Collection($item->attachments);
					$scopedAttachments = $attachments->groupBy('scope');

					$scopedAttachmentsSorted = $scopedAttachments->map(function ($scope) {
						$sortedAttachments = (new Collection($scope))->sortBy('position', SORT_ASC);
						return $sortedAttachments->toList();
					});

					$scopedAttachmentsSorted = $scopedAttachmentsSorted->toArray();

					foreach ($scopedAttachmentsSorted as $scope => $attachments) {

						if (empty($attachments[0]['multiple'])) {
							$scopedAttachmentsSorted[$scope] = $attachments[0];
						}
					}

					$this->set('attachments', $scopedAttachmentsSorted);
				}

			}

			$this->set('item', $item);
		} else {
			$this->Flash->error(__d('admin', 'Not found'));
			return $this->redirect($this->actionRedirect);
		}

		I18n::setLocale(DEFAULT_LANGUAGE);
	}

	//page builder
	public function compose($id)
	{
		$item = $this->dbTable->findById($id)->contain($this->queryContain)->firstOrFail();
		$this->set('model', $this->dbTable->getAlias());
		$this->set(compact('item'));
		$this->viewBuilder()->setTemplate('/Admin/Common/compose');
	}

	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);

		$item = $this->dbTable->findById($id)->first();

		if (!empty($item)) {
			if ($this->dbTable->delete($item)) {
				$this->Flash->success(__d('admin', 'Delete success'));
				return $this->redirect($this->actionRedirect);
			} else {
				$this->Flash->error($this->deleteFailMessage);
				return $this->redirect($this->actionRedirect);
			}
		} else {
			$this->Flash->error(__d('admin', 'Not found'));
			return $this->redirect($this->actionRedirect);
		}
	}

	// chiamate ajax per tabelle admin
	public function get()
	{
		$default = [
			'order' => null,
			'search' => false,
			'contain' => [],
			'fields' => null
		];

		$query = $this->request->getQuery();

		if (isset($query['order'])) {

			$queryOrder = json_decode($query['order'], true);

			if (empty($queryOrder)) {
				unset($query['order']);
			} else {
				$query['order'] = [$queryOrder['field'] => $queryOrder['dir']];
			}
		}

		if (isset($query['filters'])) {
            
			$jsonFilters = json_decode($query['filters'], true);
            
            if (count($jsonFilters) <= 0) {
				unset($query['filters']);
			} else {
                if (!empty($this->ignoreFilters)) {
                    foreach($this->ignoreFilters as $filter) {
                        unset($jsonFilters[$filter]);
                    }
                }
            
                $query['filters'] = json_encode($jsonFilters);
            }
            
		}

		//se non c'è un ordinamento passato via query setto l'ordinamento di default se presente
		if (empty($query['order']) && !empty($this->queryOrder)) {
			$query['order'] = $this->queryOrder;
		}

		//se non c'è una condizione passato via query setto la condizione di default se presente
		if (empty($query['filters']) && !empty($this->queryConditions)) {
			$query['filters'] = json_encode($this->queryConditions); //traformo in json per coerenza con i parametri in get
		}

		//setto il contain di default se presente
		if (!empty($this->queryContain)) {
			$query['contain'] = $this->queryContain; //traformo in json per coerenza con i parametri in get
		}

		//setto i campi se presenti
		if (!empty($this->queryFields)) {
			$query['fields'] = $this->queryFields;
		}

		$options = array_merge($default, $query);
		extract($options);


		if (empty(strlen((string)$search))) {
			if ($this->dbTable->hasBehavior('MultiTranslatable')) {
				$records = $this->dbTable->find('translations');
			} else {
				$records = $this->dbTable->find();
			}
		} else {
			if ($this->dbTable->hasBehavior('MultiTranslatable')) {
				$records = $this->dbTable->find('filtered', options: ['key' => $search])->find('translations');
			} else {
				$records = $this->dbTable->find('filtered', options: ['key' => $search]);
			}
		}


		//imposto il contain
		if (!empty($contain)) {
			$records->contain($contain);
		}

		//imposto le conditions
		if (!empty($filters)) {
			$records->where(json_decode($filters, true));
		}

        //imposto i matchings
        if (!empty($this->queryMatchings)) {
            foreach ($this->queryMatchings as $matchingModel => $matchingConditions){
                $records->matching($matchingModel, function($q) use ($matchingConditions){
                    return $q->where($matchingConditions);
                });
            }
        }

        if (!empty($this->queryNotMatchings)) {
            foreach ($this->queryNotMatchings as $matchingModel => $matchingConditions){
                $records->notMatching($matchingModel, function($q) use ($matchingConditions){
                    return $q->where($matchingConditions);
                });
            }
        }

        if (!empty($this->queryInnerJoins)) {
            foreach ($this->queryInnerJoins as $matchingModel => $matchingConditions){
                $records->innerJoinWith($matchingModel, function($q) use ($matchingConditions){
                    return $q->where($matchingConditions);
                });
            }
        }

        $records->contain($this->queryContain);

        //imposto i fields
        if (!empty($fields)) {
            $records->select($fields);
        }

        if (!empty($order)) {
            $records->order($order);
        }

        if (!empty($this->queryGroup)) {
            $records->group($this->queryGroup);
        }

        $this->set(compact('records'));
        $this->viewBuilder()->setOption('serialize', 'records');
		
	}


	public function toggleField()
	{
		$query = $this->request->getQuery();
		if (!empty($query['id']) && !empty($query['field'])) {
			$item = $this->dbTable->findById($query['id'])->first();
			$prev = $item->{$query['field']};
			$item->{$query['field']} = !$prev;

			if ($this->dbTable->save($item, ['checkRules' => false])) {
				$this->set('result', ['newValue' => $item->{$query['field']}]);
				$this->viewBuilder()->setOption('serialize', 'result');
				return;
			}
		}
		throw new UnauthorizedException("Error Processing Request");
	}


	public function updatePosition()
	{

		$params = $this->request->getData('params');

		if (!empty($params['position'])) {
			$positions = $params['position'];
			foreach ($positions as $id => $position) {
				$item = $this->dbTable->get($id);
				$success = true;
				if (!empty($item)) {
					$item->position = $position;
					if (!$this->dbTable->save($item)) $success = false;
				}
			}

			$this->set('result', ['save' => $success]);
			$this->viewBuilder()->setOption('serialize', 'result');
			return;
		}

		throw new UnauthorizedException("Error Processing Request");
	}


	public function deleteRecord()
	{

		$query = $this->request->getQuery();
		if (!empty($query['id'])) {
			$item = $this->dbTable->findById($query['id'])->first();
			if ($this->dbTable->delete($item)) {
				$this->set('result', ['delete' => true]);
			} else {
                $this->set('result', [
                    'delete' => false,
                    'message' => $this->deleteFailMessage
                ]);
            }
            $this->viewBuilder()->setOption('serialize', 'result');
            return;
		}

		throw new UnauthorizedException("Error Processing Request");
	}


	public function treeMove()
	{

		$params = $this->request->getQuery();

		if (empty($params) || empty($params['id']) || !isset($params['newParent']) || !isset($params['oldParent']) || !isset($params['offset'])) {
			return false;
		}

		extract($params);

		settype($offset, 'integer');

		$item = $this->dbTable->findById($id)->firstOrFail();


		if ($oldParent != $newParent) {

			// l'elemento è stato associato ad un altro parent
			$parent_id = $newParent === 'root' ? null : $newParent;

			// prima aggiorna il parent_id

			$item->parent_id = $parent_id;
			if ($this->dbTable->save($item)) {
				$this->dbTable->moveUp($item, abs($offset));
			}
		} else {
			if ($offset > 0) {
				$this->dbTable->moveDown($item, abs($offset));
			} else {
				$this->dbTable->moveUp($item, abs($offset));
			}
		}

		$this->set('result', ['save' => true]);
		$this->viewBuilder()->setOption('serialize', 'result');
	}


	//salvataggio filtri per index
	public function saveIndexFilters()
	{
		if (!empty($this->request->getQueryParams())) {

			$defaults = [
				'page' => 0,
				'rowsPerPage' => 10,
				'order' => null,
				'search' => ''
			];

			$settings = array_merge($defaults, $this->request->getQueryParams());
			$plugin = $this->request->getParam('plugin');
			$controller = $this->request->getParam('controller');
			$sessionKey = !empty($plugin) ? $plugin . '.' . $controller : $controller;
			$this->Session->write('Filters.' . $sessionKey, $settings);
		}
		die;
	}

	//checkbox per habtm
	public function checkbox()
	{
		$default = [
			'page' => 0,
			'rowsPerPage' => 50,
			'search' => false,
			'checked' => []
		];  

		$query = $this->request->getData();
		$options = array_merge($default, $query);
		extract($options);

		if (empty($search)) {
			$records = $this->dbTable->find();
		} else {
			$records = $this->dbTable->find('filtered', options: ['key' => $search]);
		}

        if ($this->dbTable->hasBehavior('Tree')) {
            $records->where([
                'lft = rght - 1' //nodo foglia
            ]);
        } 

		//restituisce le righe
		$records->contain($this->queryContain);
		$this->set('total', $records->count());


        $records->limit((int)$rowsPerPage);
        $records->offset($page * (int)$rowsPerPage);
        $records = $records->all()->toArray();

        if ($this->dbTable->hasBehavior('Tree') && !empty($records)) {
            foreach ($records as $record) {
                if (empty($record->parent_id)) continue;
                $path = $this->dbTable->find('path', for: $record->id)->select('title')->all();
                $record->_title_path = implode(' / ', $path->extract('title')->toList());
            }

        }


		$this->set(compact('checked', 'records'));
		$this->viewBuilder()->setLayout('ajax');
		$this->viewBuilder()->setTemplate('/Admin/Common/checkbox');
	}


	// fine chiamate ajax

}
