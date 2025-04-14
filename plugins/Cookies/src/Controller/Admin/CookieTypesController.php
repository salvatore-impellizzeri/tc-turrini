<?php
declare(strict_types=1);

namespace Cookies\Controller\Admin;

use App\Controller\Admin\AppController;


class CookieTypesController extends AppController
{
	
	public function index(){
		return $this->redirect(['action' => 'list']);
	}	
	
	public function list()
    {
        
        $items = $this->CookieTypes->find('list')            
            ->order(['CookieTypes.position' => 'ASC'])
            ->toArray();
        
        $this->set(compact('items'));
    }

}
