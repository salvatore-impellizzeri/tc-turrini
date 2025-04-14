<?php
declare(strict_types=1);

namespace Products\Controller\Admin;

use Cake\Event\EventInterface;
use App\Controller\Admin\AppController;


class ProductsController extends AppController
{

	public function beforeRender (EventInterface $event)
    {
        parent::beforeRender($event);

        if (in_array($this->request->getParam('action'), ['add', 'edit', 'index'])) {

            $productCategories = $this->Products->ProductCategories->find('threaded')
                ->order(['ProductCategories.lft' => 'ASC']);

            $productCategories = $this->Products->ProductCategories->formatTreeList($productCategories, spacer: '- ')->toArray();
			$this->set('productCategories', $productCategories);

        }
    }



	public function get()
    {
		$this->queryOrder = ['Products.position' => 'ASC'];
		$this->queryContain = [
			'ProductCategories' => [
				'fields' => [
					'id',
					'title'
				]
			]
		];
		parent::get();
	}


	public function edit($id = null)
    {

		$this->queryContain = [
			'Images' => 'ResponsiveImages',
		];

        parent::edit($id);
    }



}
