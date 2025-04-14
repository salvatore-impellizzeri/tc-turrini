<?php
declare(strict_types=1);

namespace Contacts\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\I18n;



class ContactFieldsController extends AppController
{
    public array $types = [
        'text',
        'email',
        'tel',
        'number',
        'date',
        'textarea',
        'checkbox',
        'select',
        'hidden'
    ];


    public function add()
    {
        $contactFormId = $this->request->getQuery('contact_form_id');
        if (empty($contactFormId)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }

        $contactForm = $this->ContactFields->ContactForms->findById($contactFormId)->firstOrFail();

        if (empty($contactForm)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }


        if ($this->request->is('post')) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('contact_form_id')];
        }

        $this->set(compact('contactForm'));
        $this->set('types', array_combine($this->types, $this->types));
		parent::add();
    }

    public function edit($id = null)
    {

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('contact_form_id')];
        }

        $this->queryContain = [
			'ContactForms'
		];
        $this->set('types', array_combine($this->types, $this->types));
        parent::edit($id);
        
    }

    public function list($id = null)
    {
        $contactForm = $this->ContactFields->ContactForms->findById($id)->firstOrFail();

        $this->set(compact('contactForm'));
    }

}
