<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\Log\Log;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Core\Configure;

class UsersController extends AppController
{


	public function beforeFilter(EventInterface $event)
	{
		parent::beforeFilter($event);
		// Configure the login action to not require authentication, preventing
		// the infinite redirect loop issue
		$this->Authentication->addUnauthenticatedActions(['login']);

	}

	public function beforeRender(EventInterface $event){
		parent::beforeRender($event);

		if (!in_array($this->request->getParam('action'), ['login', 'logout', 'setNewPassword'])) {

			// solo superadmin ha accesso all'edit degli utenti
	        if (empty($this->loggedUser['group_id']) || $this->loggedUser['group_id'] != 1){
				throw new NotFoundException(__('Not found'));
			}
		}

		if ($this->request->getParam('action') == 'add' || $this->request->getParam('action') == 'edit') {
			$groups = $this->Users->Groups->find('list', order: 'Groups.position ASC')->all();
       		$this->set(compact('groups'));
		}

		//forzo la view ad add per gli utenti
		if ($this->request->getParam('action') == 'add') {
			$this->viewBuilder()->setTemplate('add');
		}
	}

	public function login()
	{
		$this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();

		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {

			$loggedUser = $result->getData();

			if(empty($loggedUser->active)){
				$this->Flash->login(__('User is not active.'));
				$this->Authentication->logout();
				$redirect = ['action' => 'login'];
			} else {
				// redirect con login effettuato con successo
				$redirect = $this->request->getQuery('redirect', [
					'plugin' => 'CustomPages',
					'controller' => Configure::read('Setup.adminHomeController'),
					'action' => 'index',
				]);
			}
			return $this->redirect($redirect);

		} else if($this->request->is('post') && !empty($this->request->getData('username'))){
			// loggo tentativo di accesso fallito
			$log = "Tentativo d'accesso fallito | IP: {$_SERVER['REMOTE_ADDR']} | User-agent: {$_SERVER['HTTP_USER_AGENT']} | Username: {$this->request->getData('username')}";
			// verifico se esiste un utente
			$user = $this->Users->findByUsername($this->request->getData('username'))->first();
			if(!empty($user)){
				if($user->failed_login_attempts <= 30){
					// incrementa il numero di tentativi di accesso falliti
					$user->failed_login_attempts++;
					$this->Users->save($user);
				}else{
					// dop 30 tentativi errati, blocca l'utente
					$user->active = false;
					$this->Users->save($user);
				}
			}

			Log::info($log, 'access_fail');
		}
		// display error if user submitted and authentication failed
		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->login(__d('admin', 'Invalid username or password'));
		}
	}

	public function logout()
	{
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {
			$this->Authentication->logout();
			return $this->redirect(['controller' => 'Users', 'action' => 'login']);
		}
	}


    public function get()
    {

		$this->queryContain = ['Groups'];
		parent::get();
    }

	public function changePassword($id = null)
    {

        $user = $this->dbTable->findById($id)->first();

		if(!empty($user)){

			$item = $this->dbTable->newEmptyEntity();
			$item->id = $id;
			$item->setNew(false);
			$this->set('item', $item);

			if ($this->request->is(['patch', 'post', 'put']) && $this->request->is('ajax')) {

				$response = [];

				$this->dbTable->patchEntity($item, $this->request->getData(), [
					'validate' => 'password',
					'fields' => [
						'password',
						'user_password',
					]
				]);

                if ($this->dbTable->save($item)) {
                    $this->Flash->success(__d('admin', 'Save success'));
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
					'errors' => $validationErrors
				];

				$this->set('response', $response);
				$this->viewBuilder()->setOption('serialize', 'response');
            }

		} else{
			$this->Flash->error(__d('admin', 'Not found'));
			return $this->redirect(['action' => 'index']);
		}
    }

	// cambio password personale
	public function setNewPassword(){
		$user = $this->dbTable->findById($this->loggedUser['id'])->first();

		if(!empty($user)){

			$item = $this->dbTable->newEmptyEntity();
			$item->id = $this->loggedUser['id'];
			$item->setNew(false);
			$this->set('item', $item);

			if ($this->request->is(['patch', 'post', 'put']) && $this->request->is('ajax')) {

				$response = [];

				$this->dbTable->patchEntity($item, $this->request->getData(), [
					'validate' => 'password',
					'fields' => [
						'password',
						'user_password',
					]
				]);


                if ($this->dbTable->save($item)) {
                    $this->Flash->success(__d('admin', 'Save success'));
                } else {

					$this->Flash->error(
						__d('admin', 'Save error'),
						['params' => ['fields' => array_keys($item->getInvalid())]]
					);
				}

                $validationErrors = empty($item->getErrors()) ? null : $item->getErrors();
				$response = [
					'item' => $item,
					'redirect' => Router::url([
						'plugin' => 'CustomPages',
						'controller' => 'CustomPages',
						'action' => 'index',
					]),
					'errors' => $validationErrors
				];

				$this->set('response', $response);
				$this->viewBuilder()->setOption('serialize', 'response');
            }

		} else{
			throw new NotFoundException(__('Not found'));
		}
	}

}
