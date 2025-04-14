<?php
declare(strict_types=1);

namespace Clients\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Http\Exception\NotFoundException;


class ClientsController extends AppController
{

	public function view($id = null)
    {
		
		$this->queryContain = [
			'ClientShippingAddresses' => function ($q) {
							return $q->where(['trashed' => false])->order(['is_default' => 'DESC', 'id' => 'DESC']);
						},
			'ClientBillingAddresses'=> function ($q) {
							return $q->where(['trashed' => false])->order(['is_default' => 'DESC', 'id' => 'DESC']);
						},
			'Orders' => function ($q) {
							return $q->contain(['OrderStatuses'])->where(['Orders.confirmed' => true, 'Orders.complete' => true]);
						},
			'Subscriptions'
		];
		
		parent::view($id);
    }
	
    //blocco l'action add da admin
    public function add()
    {
        throw new NotFoundException();
    }

	//blocco l'action edit da admin
    public function edit($id = NULL)
    {
        throw new NotFoundException();
    }
	
	//blocco l'action delete da admin
    public function delete($id = NULL)
    {
        throw new NotFoundException();
    }

}
