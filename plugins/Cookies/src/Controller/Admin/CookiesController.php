<?php
declare(strict_types=1);

namespace Cookies\Controller\Admin;

use App\Controller\Admin\AppController;


class CookiesController extends AppController
{


    public function add()
    {
        $cookie_type_id = $this->request->getQuery('cookie_type_id');
        if (empty($cookie_type_id)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }

        $cookieType = $this->Cookies->CookieTypes->findById($cookie_type_id)->firstOrFail();

        if (empty($cookieType)) {
            throw new MethodNotAllowedException(__d('admin', 'not allowed exception'));
        }


        if ($this->request->is('post')) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('cookie_type_id')];
        }

        $this->set(compact('cookieType'));
		parent::add();
    }

    public function edit($id = null)
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->actionRedirect = ['action' => 'list', $this->request->getData('cookie_type_id')];
        }

        $this->queryContain = ['CookieTypes'];

        parent::edit($id);
    }

	public function list($id = null)
    {
        $cookieType = $this->Cookies->CookieTypes->findById($id)->firstOrFail();

        $items = $this->Cookies->find('list')
            ->where(['Cookies.cookie_type_id' => $id])
            ->order(['Cookies.position' => 'ASC'])
            ->toArray();

        $this->set(compact('cookieType', 'items'));
    }


}
