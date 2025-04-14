<?php
declare(strict_types=1);

namespace Clients\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Log\Log;
use Cake\I18n\FrozenTime;
use Cake\Event\EventInterface;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Stripe\Stripe;
use Cake\Http\Client;


class ClientsController extends AppController
{


	public function initialize(): void
    {
        parent::initialize();
		$this->loadComponent('Authentication.Authentication');
	}


	public function beforeFilter(EventInterface $event)
	{
	    parent::beforeFilter($event);
	    $this->Authentication->addUnauthenticatedActions(['add', 'login', 'recoverPassword']);
	}


	public function add()
	{
		$client = $this->Clients->newEmptyEntity();
        if ($this->request->is('post')) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());

			$client->enabled = 1;

            if ($this->Clients->save($client)) {
                $this->Flash->success(__d('clients', 'signup success'));

				// email di benvenuto
				$locale = $client->locale;


				if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
					$redirect = $this->Session->read('afterLoginRedirect') ?? '/' . ACTIVE_LANGUAGE . '/';
				} else {
					$redirect = $this->Session->read('afterLoginRedirect') ?? '/';
				}

				$this->Authentication->setIdentity($client);
				return $this->redirect($redirect);


            }
            $this->Flash->error(__d('clients', 'signup error'));
        }
        $this->set('client', $client);
	}

	public function login()
	{
		$this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();

		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {

			$loggedUser = $result->getData();



			// redirect to after login success
			// se c'è un redirect in sessione vai lì, altrimenti vai alla homepage
			// sarebbe il caso di mettere un flashmessage forse
			$redirect = $this->Session->read('afterLoginRedirect') ?? '/';

			return $this->redirect($redirect);

		} else if($this->request->is('post') && !empty($this->request->getData('email'))){
			// loggo tentativo di accesso fallito
			$log = "Tentativo d'accesso fallito | IP: {$_SERVER['REMOTE_ADDR']} | User-agent: {$_SERVER['HTTP_USER_AGENT']} | Email: {$this->request->getData('email')}";

			Log::info($log, 'access_fail_frontend');
		}
		// display error if user submitted and authentication failed
		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error(__d('clients', 'Invalid username or password'));
		}
	}


	public function logout()
	{
		$result = $this->Authentication->getResult();
		// regardless of POST or GET, redirect if user is logged in
		if ($result->isValid()) {
			$this->Authentication->logout();
			if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
				return $this->redirect('/' . ACTIVE_LANGUAGE . '/');
			} else {
				return $this->redirect('/');
			}
		}
	}

	public function recoverPassword()
	{
		if ($this->request->is(['post', 'put'])) {
			$email = $this->request->getData('email');

			$client = $this->Clients->find()
						->where([
							'email' => $email,
							'enabled' => true
						])
						->first();

			if(!empty($client)){
				$FrontendHelper = new \App\View\Helper\FrontendHelper(new \Cake\View\View());
				// $loginUrl = Configure::read('Setup.domain') . $FrontendHelper->url('/clients/edit-password') . '?token=' . $client->token;
				//
				// $this->Sib->sendmail([['email' => $client->email]], Configure::read('Sib.emails.recupero_password.' . $client->locale), ['url' => $loginUrl]);

				//invio email per recupero password
			}

		}
	}


	//riepilogo dati accesso cliente
	public function resume()
	{

	}


	//modifica dati
	public function edit()
	{
		//solo utenti non social possono accedere
		$loggedClient = $this->getLoggedClient();

		$client = $this->Clients
        ->findById($loggedClient['id'])
        ->firstOrFail();

	    if ($this->request->is(['post', 'put'])) {
			//blocco i campi di salvataggio per sicurezza
	        $this->Clients->patchEntity($client, $this->request->getData(), [
				'fields' => [
					'fullname',
				]
			]);
	        if ($this->Clients->save($client)) {
				$this->Authentication->setIdentity($client); //aggiorno i dati dell'utente loggato
	            $this->Flash->success(__d('clients', 'update success'));
	            return $this->redirect(['action' => 'resume']);
	        }
	        $this->Flash->error(__d('clients', 'update error'));
	    }

	    $this->set('client', $client);

	}

	//modifica email accesso
	public function editEmail()
	{
		$loggedClient = $this->getLoggedClient();

		$client = $this->Clients
        ->findById($loggedClient['id'])
        ->firstOrFail();


	    if ($this->request->is(['post', 'put'])) {
			$client->email_confirmed = false;
			//blocco i campi di salvataggio per sicurezza
			$this->Clients->patchEntity($client, $this->request->getData(), [
				'fields' => [
					'email',
					'email_confirmed',
				]
			]);

			if ($client->isDirty('email')){
				$client->email_confirmed = 0;
				if ($this->Clients->save($client)) {
					$this->Authentication->setIdentity($client); //aggiorno i dati dell'utente loggato
					$this->Flash->success(__d('clients', 'update success'));
					return $this->redirect(['action' => 'resume']);
				}
				$this->Flash->error(__d('clients', 'update error'));
			} else {
				$this->Flash->success(__d('clients', 'update success'));
				return $this->redirect(['action' => 'resume']);
			}



	    }

	    $this->set('client', $client);

	}

	//modifica password accesso
	public function editPassword()
	{
		$loggedClient = $this->getLoggedClient();

		$client = $this->Clients
        ->findById($loggedClient['id'])
        ->firstOrFail();

	    if ($this->request->is(['post', 'put'])) {
			//blocco i campi di salvataggio per sicurezza
	        $this->Clients->patchEntity($client, $this->request->getData(), [
				'validate' => 'password',
				'fields' => [
					'password'/*,
					'current_password',*/
				]
			]);


	        if ($this->Clients->save($client)) {
				$this->Authentication->setIdentity($client); //aggiorno i dati dell'utente loggato
	            $this->Flash->success(__d('clients', 'update success'));
	            return $this->redirect(['action' => 'resume']);
	        }
	        $this->Flash->error(__d('clients', 'update error'));
	    }

	    $this->set('client', $client);

	}



	//elimina account utente
	public function delete()
	{

		$loggedClient = $this->getLoggedClient();
		$clientId = $loggedClient['id'];
		$client = $this->Clients->get($clientId);

		// controllo che il link di cancellazione abbia il token dell'utente loggato per evitare cancellazioni "fraudolente"
		if(!empty($client)) {
			$this->Clients->delete($client);
			$this->Flash->success(__d('clients', 'delete success'));
			$this->Authentication->logout();

		}


		if(ACTIVE_LANGUAGE != DEFAULT_LANGUAGE) {
			return $this->redirect('/' . ACTIVE_LANGUAGE . '/');
		} else {
			return $this->redirect('/');
		}

	}


}
