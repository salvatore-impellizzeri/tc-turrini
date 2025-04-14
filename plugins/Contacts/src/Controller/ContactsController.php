<?php
declare(strict_types=1);

namespace Contacts\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Validation\Validator;
use Cake\Utility\Security;
use Cake\Collection\Collection;


class ContactsController extends AppController
{

	public function req()
	{

		$this->request->allowMethod('ajax');

			$nospam = false;
			$error_message = __d('contacts', 'message not sent error');
			$resp = [];

			if (!empty($this->request->getData())) {

				$token = $this->Session->read('token');
				$time_taken = microtime(true) - $this->Session->read('token_time_start');

				$dataToken = $this->request->getData($token);

				// se il tempo di invio è minore di 1.5s ed è presente il token,
				// ma è vuoto (ecco perché il doppio controllo isset ed empty)
				// allora non dovrebbe essere spam
				if(
					!empty($token) &&
					!empty($time_taken) &&
					$time_taken > 1.5 &&
					isset($dataToken) &&
					empty($dataToken)
				) {
					$nospam = true;
				}

				if($nospam) {
                    
                    $data = $this->request->getData();
					$item = $this->Contacts->newEntity($data);
            
                    //recupero la form con i campi da DB
                    if (empty($data['contact_form_id'])){
                        $log = $this->request->clientIp() . ' - ' . 'ID FORM ASSENTE: ' . json_encode($this->request->getData());
						Log::info($log, 'contacts');
                        die;
                    }

                    $contactForm = $this->Contacts->ContactForms->findById($data['contact_form_id'])
                        ->contain('ContactFields')
                        ->first();

                    if (empty($contactForm)){
                        $log = $this->request->clientIp() . ' - ' . 'ID FORM ASSENTE: ' . json_encode($this->request->getData());
                        Log::info($log, 'contacts');
                        die;
                    }

                    // valido la form
                    $validator = $this->buildValidator($contactForm);
                    $errors = $validator->validate($data);
                    
					if (empty($errors) && $this->Contacts->save($item)) {

                        
                        //recupero i valori campo -> nome per la mail
                        $fieldNames = [];
                        foreach ($contactForm->contact_fields as $field) {
                            if ($field->show_in_mail) $fieldNames[$field->field] = $field->title;
                        }



						$email = new Mailer('default');

						// prima il formato era both, se si può evitare senza punteggi spam alti meglio
						$email
                            ->setEmailFormat('html')
                            ->setFrom([Configure::read('Setup.mailfrom') => Configure::read('Setup.sitename')])
                            ->setTo($contactForm->mailto)
                            ->setSubject($contactForm->subject)
                            ->viewBuilder()
                            ->setTemplate('default')
                            ->setLayout('default');

                        //TODO migliorare questa cosa -> per ora do per scontato che ci sia un campo email
                        if (!empty($data['email'])){
                            $email->setReplyTo($data['email']);
                        }    

						$email->setViewVars([
							'title' => $contactForm->subject,
							'data' => $data,
                            'fieldNames' => $fieldNames
						]);

						// errore in invio
						try {
							$emailsent = $email->deliver();
						} catch (Exception $e) {

							// loggo tentativo di invio fallito
							$log = $this->request->clientIp() . ' - ' . 'ERRORE IN INVIO: ' . json_encode($this->request->getData());
							Log::info($log, 'contacts');

							$resp['sent'] = false;
							$resp['message'] = $error_message;

							$this->Flash->error($error_message);
						}
						

						// email inviata
						if(!empty($emailsent)) {

                            //invia risposta automatica se impostata -> anche qui do per scontato che esista un campo email
                            if ($contactForm->send_reply && !empty($data['email'])) {
                                $clientEmail = new Mailer('default');
                                
                                $clientEmail
                                    ->setEmailFormat('html')
                                    ->setFrom([Configure::read('Setup.mailfrom') => Configure::read('Setup.sitename')])
                                    ->setTo($data['email'])
                                    ->setSubject($contactForm->reply_subject)
                                    ->viewBuilder()
                                    ->setTemplate('reply')
                                    ->setLayout('default');

                                $clientEmail->setViewVars([
                                    'content' => $contactForm->reply_text,
                                ]);

                                try {
                                    $clientEmail->deliver();
                                } catch (Exception $e) {
        
                                    // loggo tentativo di invio fallito
                                    $log = $this->request->clientIp() . ' - ' . 'ERRORE IN INVIO RISPOSTA: ' . $data['email'];
                                    Log::info($log, 'contacts');

                                }
                            }

							// reset form. Necessario? Corretto?
							$this->request = $this->request->withParsedBody([]);

							$resp['sent'] = true;
							$resp['message'] = __d($this->po, 'message sent');
							$this->Flash->success(__d($this->po, 'message sent'));
							//$this->actionRedirect = ['action' => 'sent'];

						}
					// salvataggio dei dati fallito
					} else {

						$log = $this->request->clientIp() . ' - ' . 'ERRORE NEL SALVATAGGIO: ' . json_encode($this->request->getData());
						Log::info($log, 'contacts');

						$resp['sent'] = false;
						$resp['message'] = $error_message;
						$resp['errors'] = empty($errors) ? null : $errors;

						$this->Flash->error($error_message);
					}
				// rilevato tentativo di spam
				} else {

					$log = $this->request->clientIp() . ' - ' . 'RILEVATO TENTATIVO DI SPAM: ' . json_encode($this->request->getData());
					Log::info($log, 'contacts');

					$resp['sent'] = false;
					$resp['message'] = $error_message;
					$resp['spam'] = 1;
					$resp['errors'] = empty($errors) ? null : $errors;

					$this->Flash->error($error_message);
				}

			}

			$this->set('resp', $resp);
	}


    //costruisco il validatore per la form
    protected function buildValidator($contactForm)
    {
        $validator = new Validator();

        //regole di validazione di default
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('contact_form_id')
            ->notEmptyString('contact_form_id');

        $validator
            ->add('contact_form_hash', 'custom', [
                'rule' => function ($value, $context){
                    //controllo di sicurezza
                    return Security::hash($context['data']['contact_form_id'], 'md5', true) == $value;
                }
            ]);


        //regole di validazione dinamiche    
        foreach ($contactForm->contact_fields as $field){


            switch ($field->type) {
                case 'text':
                case 'hidden':
                case 'tel': //TODO valutare se mettere una regola piu specifica
                    $validator
                        ->scalar($field->field)
                        ->maxLength($field->field, 255);

                    if ($field->required) {
                        $validator
                            ->requirePresence($field->field, 'create')
                            ->notEmptyString($field->field);
                    }    
                        
                    break;    

                case 'email':
                    $validator
                        ->email($field->field)
                        ->maxLength($field->field, 255);

                    if ($field->required) {
                        $validator
                            ->requirePresence($field->field, 'create')
                            ->notEmptyString($field->field);
                    }    
                        
                    break;   

                case 'textarea':
                    $validator
                        ->scalar($field->field)
                        ->maxLength($field->field, 3000);

                    if ($field->required) {
                        $validator
                            ->requirePresence($field->field, 'create')
                            ->notEmptyString($field->field);
                    }    
                        
                    break; 

                case 'checkbox':
                    $validator
                        ->boolean($field->field);

                    if ($field->required) {
                        $validator
                            ->requirePresence($field->field, 'create')
                            ->notEmptyString($field->field)
                            ->add($field->field, 'checked', [
                                'rule' => function ($value, $context) {
                                    return $value == 1;
                                },
                                'message' => __d('cake', 'This field is required')
                            ]);
                    }    
                        
                    break; 

                case 'select': //TODO valutare se validare il valore passato
                    $validator
                        ->scalar($field->field)
                        ->maxLength($field->field, 255);

                    if ($field->required) {
                        $validator
                            ->requirePresence($field->field, 'create')
                            ->notEmptyString($field->field);
                    }    
                        
                    break;   


                case 'date':
                    $validator
                        ->date($field->field)
                        ->maxLength($field->field, 255);

                    if ($field->required) {
                        $validator
                            ->requirePresence($field->field, 'create')
                            ->notEmptyString($field->field);
                    }    
                        
                    break;   

                case 'number':
                    $validator
                        ->naturalNumber($field->field)
                        ->maxLength($field->field, 255);

                    if ($field->required) {
                        $validator
                            ->requirePresence($field->field, 'create')
                            ->notEmptyString($field->field);
                    }    
                        
                    break;
    
            }
           
        }

        

        return $validator;
    }

}
