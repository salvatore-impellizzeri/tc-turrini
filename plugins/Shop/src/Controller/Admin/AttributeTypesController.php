<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\ORM\Query;


class AttributeTypesController extends AppController
{

	public function get()
    {
		$this->queryOrder = ['AttributeTypes.position' => 'ASC'];
		parent::get();
	}

    public function deleteRecord()
	{
        $this->deleteFailMessage = __dx('shop', 'admin', 'connected attributes delete error');
        parent::deleteRecord();
    }

}
