<?php
declare(strict_types=1);

namespace Contacts\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\I18n;



class ContactsController extends AppController
{

    public function get()
	{
		$this->queryOrder = ['Contacts.id' => 'DESC'];
        $this->queryConditions = ['Contacts.published' => true];
		parent::get();
	}

    public function list($id = null)
    {
        $contactForm = $this->Contacts->ContactForms->findById($id)->firstOrFail();

        $this->set(compact('contactForm'));
    }

    public function view($id = null)
	{
		$item = $this->Contacts->findById($id)
            ->contain([
                'ContactForms' => [
                    'ContactFields'
                ]
            ])
            ->firstOrFail();

        if (empty($item->contact_form)) {
            $this->Flash->error(__d('admin', 'Not found'));
			return $this->redirect(['action' => 'list', $item->contact_form_id]);
        }

        $fieldNames = [];
        foreach ($item->contact_form->contact_fields as $field) {
            if ($field->show_in_mail) $fieldNames[$field->field] = $field->title;
        }


        $this->set(compact('item', 'fieldNames'));
	}

}
