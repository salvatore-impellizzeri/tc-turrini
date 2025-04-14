<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;
use Cake\ORM\Query;


class BrandsController extends AppController
{



	public function get()
    {
		$this->queryOrder = ['Brands.position' => 'ASC'];
		parent::get();
	}



	public function edit($id = null)
    {

		$this->queryContain = [
			'Images' => 'ResponsiveImages',
		];
        parent::edit($id);
    }

    public function deleteRecord()
	{
        $this->deleteFailMessage = __dx('shop', 'admin', 'brand delete error');
        parent::deleteRecord();
    }



}
